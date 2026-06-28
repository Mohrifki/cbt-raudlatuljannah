<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subjects = Subject::all();
        return view('admin.subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.subjects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'type' => 'required|in:wajib,pilihan'
        ]);

        Subject::create($validatedData);

        return redirect()->route('admin.subjects.index')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }
    public function edit(Subject $subject)
    {
        return view('admin.subjects.edit', compact("subject"));
    }
    public function update(Request $request, Subject $subject)
    {
        $validated = $request -> validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'type' => 'required|in:wajib,pilihan'
        ]);
        $subject->update($validated);
        return redirect()->route('admin.subjects.index')
        ->with('success', 'Mata pelajaran berhasil diperbarui.');
    }
    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('admin.subjects.index')
        ->with('success', 'Mata pelajaran berhasil dihapus.');
    }

}
