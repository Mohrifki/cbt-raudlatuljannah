<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Imports\StudentsImport;
use App\Exports\StudentsTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('schoolClass')->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::pluck('name');
        $classes = SchoolClass::orderBy('grade')->orderBy('name')->get();
        $electives = Subject::where('type', 'pilihan')->orderBy('name')->get();
        return view('admin.users.create', compact('roles', 'classes', 'electives'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                => 'required|string|max:255',
            'email'               => 'required|email|unique:users,email',
            'password'            => 'required|min:6',
            'role'                => 'required|exists:roles,name',
            'nis'                 => 'nullable|required_if:role,siswa|unique:users,nis',
            'class_id'            => 'nullable|required_if:role,siswa|exists:school_classes,id',
            'elective_subjects'   => 'nullable|array',
            'elective_subjects.*' => 'exists:subjects,id',
        ]);

        $isSiswa = $request->role === 'siswa';

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'nis'      => $isSiswa ? $request->nis : null,
            'class_id' => $isSiswa ? $request->class_id : null,
        ]);

        $user->assignRole($request->role);

        // Simpan mapel pilihan hanya untuk siswa
        $user->electiveSubjects()->sync($isSiswa ? ($request->elective_subjects ?? []) : []);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $roles = Role::pluck('name');
        $classes = SchoolClass::orderBy('grade')->orderBy('name')->get();
        $electives = Subject::where('type', 'pilihan')->orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'roles', 'classes', 'electives'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'                => 'required|string|max:255',
            'email'               => 'required|email|unique:users,email,' . $user->id,
            'password'            => 'nullable|min:6',
            'role'                => 'required|exists:roles,name',
            'nis'                 => 'nullable|required_if:role,siswa|unique:users,nis,' . $user->id,
            'class_id'            => 'nullable|required_if:role,siswa|exists:school_classes,id',
            'elective_subjects'   => 'nullable|array',
            'elective_subjects.*' => 'exists:subjects,id',
        ]);

        $isSiswa = $request->role === 'siswa';

        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->nis      = $isSiswa ? $request->nis : null;
        $user->class_id = $isSiswa ? $request->class_id : null;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        $user->syncRoles([$request->role]);
        $user->electiveSubjects()->sync($isSiswa ? ($request->elective_subjects ?? []) : []);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('success', 'Anda tidak bisa menghapus akun sendiri.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }

    public function importForm()
    {
        return view('admin.users.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $import = new StudentsImport();
        Excel::import($import, $request->file('file'));

        $msg = "Import selesai: {$import->created} siswa ditambahkan, {$import->skipped} dilewati.";
        if (!empty($import->errors)) {
            $msg .= ' Catatan: ' . implode('; ', array_slice($import->errors, 0, 5));
        }

        return redirect()->route('admin.users.index')->with('success', $msg);
    }

    public function importTemplate()
    {
        return Excel::download(new StudentsTemplateExport, 'template_import_siswa.xlsx');
    }
}
