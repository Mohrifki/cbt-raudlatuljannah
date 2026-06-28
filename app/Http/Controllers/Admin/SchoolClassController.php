<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

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
        $validated = $request -> validate([
            'name' => 'required|string|max:255',
            'grade' => 'nullable|string|max:255',
    
        ]);
        SchoolClass::create($validated);

        return redirect() -> route('admin.classes.index') 
        -> with('success', 'Kelas berhasil ditambahkan');
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
        $validated = $request -> validate([
            'name' => 'required|string|max:255',
            'grade' => 'nullable|string|max:50',
        ]);
        $class -> update($validated);
        return redirect() -> route('admin.classes.index')
            -> with('success', 'Kelas berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SchoolClass $class)
    {
        $class -> delete();
        return redirect() -> route('admin.classes.index')
            -> with('success', 'Kelas berhasil dihapus');
    }
}
