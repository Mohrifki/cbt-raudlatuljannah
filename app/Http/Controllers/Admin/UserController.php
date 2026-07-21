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
use Illuminate\Support\Facades\Storage;

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
            'photo'               => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
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
            'password' => Hash::make($isSiswa ? $request->nis : $request->password),
            'nis'      => $isSiswa ? $request->nis : null,
            'class_id' => $isSiswa ? $request->class_id : null,
        ]);

        $user->assignRole($request->role);
        if ($request->hasFile('photo')) {
            $user->update(['photo' => $request->file('photo')->store('siswa', 'public')]);
        }
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
            'photo'               => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
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
        if ($isSiswa) {
            // Password siswa selalu = NIS (dicetak di Kartu Ujian)
            $user->password = Hash::make($request->nis);
        } elseif ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Foto — hapus lama, simpan baru (hanya jika ada upload)
        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $user->photo = $request->file('photo')->store('siswa', 'public');
        }
        $user->save();

        $user->syncRoles([$request->role]);
        if ($isSiswa) {
            // ambil plot yang sudah tersimpan: [subject_id => plot]
            $existing = $user->electiveSubjects()->get()->pluck('pivot.plot', 'id')->toArray();

            $sync = [];
            foreach ($request->input('elective_subjects', []) as $sid) {
                $sync[$sid] = ['plot' => $existing[$sid] ?? null]; // pertahankan plot lama
            }
            $user->electiveSubjects()->sync($sync);
        } else {
            $user->electiveSubjects()->detach(); // kalau role diubah jadi non-siswa
        }

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

    public function photoImportForm()
    {
        return view('admin.users.photo-import');
    }

    public function photoImport(Request $request)
    {
        $request->validate([
            'photos'   => 'required|array',
            'photos.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $matched = 0;
        $unmatched = [];

        foreach ($request->file('photos') as $file) {
            // Nama file (tanpa ekstensi) = NIS. Contoh: 2024001.jpg
            $nis = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $user = User::where('nis', $nis)->first();

            if (!$user) {
                $unmatched[] = $nis;
                continue;
            }

            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $user->update(['photo' => $file->store('siswa', 'public')]);
            $matched++;
        }

        $msg = "Import foto selesai: {$matched} foto berhasil dicocokkan.";
        if (!empty($unmatched)) {
            $msg .= ' Tidak ketemu NIS: ' . implode(', ', array_slice($unmatched, 0, 10)) . (count($unmatched) > 10 ? ' ...' : '');
        }

        return redirect()->route('admin.users.index')->with('success', $msg);
    }

    public function plotForm(User $user)
    {
        $subjects = Subject::where('type', 'pilihan')->orderBy('name')->get();
        $current  = $user->electiveSubjects()->get()->mapWithKeys(fn($s) => [$s->pivot->plot => $s->id]);
        return view('admin.users.plot', compact('user', 'subjects', 'current'));
    }

    public function plotStore(Request $request, User $user)
    {
        $request->validate([
            'plot_1' => 'nullable|exists:subjects,id',
            'plot_2' => 'nullable|exists:subjects,id',
            'plot_3' => 'nullable|exists:subjects,id',
            'plot_4' => 'nullable|exists:subjects,id',
        ]);

        $chosen = array_filter([$request->plot_1, $request->plot_2, $request->plot_3, $request->plot_4]);
        if (count($chosen) !== count(array_unique($chosen))) {
            return back()->withErrors(['plot' => 'Mapel tidak boleh sama antar plot.'])->withInput();
        }

        $sync = [];
        foreach ([1, 2, 3, 4] as $p) {
            $sid = $request->input('plot_' . $p);
            if ($sid) $sync[$sid] = ['plot' => $p];
        }
        $user->electiveSubjects()->sync($sync);

        return redirect()->route('admin.users.index')->with('success', 'Plot peminatan ' . $user->name . ' disimpan.');
    }
}