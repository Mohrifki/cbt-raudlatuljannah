<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use App\Models\User;

class SchoolClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = SchoolClass::latest()->get();
        return view('admin.classes.index', compact('classes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.classes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'nullable|string|max:255',

        ]);
        SchoolClass::create($validated);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Kelas berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SchoolClass $class)
    {
        return view('admin.classes.edit', compact('class'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SchoolClass $class)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'nullable|string|max:50',
        ]);
        $class->update($validated);
        return redirect()->route('admin.classes.index')
            ->with('success', 'Kelas berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SchoolClass $class)
    {
        $class->delete();
        return redirect()->route('admin.classes.index')
            ->with('success', 'Kelas berhasil dihapus');
    }

    public function students(SchoolClass $class)
    {
        $students = User::role('siswa')->with('schoolClass')->orderBy('name')->get();
        $current = $class->students()->pluck('id')->all();
        return view('admin.classes.students', compact('class', 'students', 'current'));
    }

    public function syncStudents(Request $request, SchoolClass $class)
    {
        $data = $request->validate([
            'student_ids'   => 'nullable|array',
            'student_ids.*' => 'exists:users,id',
        ]);
        $ids = $data['student_ids'] ?? [];

        // 1) Siswa terpilih -> masuk ke kelas ini (memindah jika sebelumnya di kelas lain)
        if (!empty($ids)) {
            User::whereIn('id', $ids)->update(['class_id' => $class->id]);
        }

        // 2) Siswa yang tadinya di kelas ini tapi kini tidak dicentang -> dikeluarkan
        $remove = User::where('class_id', $class->id);
        if (!empty($ids)) {
            $remove->whereNotIn('id', $ids);
        }
        $remove->update(['class_id' => null]);

        return redirect()->route('admin.classes.students', $class)->with('success', 'Data siswa kelas berhasil diperbarui.');
    }
}
