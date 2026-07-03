<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlotSession;
use Illuminate\Http\Request;

class PlotSessionController extends Controller
{
    // Tampilkan daftar + form kelola jadwal plot
    public function index()
    {
        $sessions = PlotSession::orderBy('grade')->orderBy('plot')->get();
        return view('admin.plot-sessions.index', compact('sessions'));
    }

    // Tambah jadwal plot baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'plot'     => 'required|integer|min:1|max:4',
            'grade'    => 'nullable|string',
            'label'    => 'nullable|string|max:100',
            'start_at' => 'required|date',
            'end_at'   => 'required|date|after:start_at',
        ]);

        PlotSession::create($data);

        return back()->with('success', 'Jadwal plot ditambahkan.');
    }

    // Perbarui jadwal plot
    public function update(Request $request, PlotSession $plotSession)
    {
        $data = $request->validate([
            'plot'     => 'required|integer|min:1|max:4',
            'grade'    => 'nullable|string',
            'label'    => 'nullable|string|max:100',
            'start_at' => 'required|date',
            'end_at'   => 'required|date|after:start_at',
        ]);

        $plotSession->update($data);

        return back()->with('success', 'Jadwal plot diperbarui.');
    }

    // Hapus jadwal plot
    public function destroy(PlotSession $plotSession)
    {
        $plotSession->delete();

        return back()->with('success', 'Jadwal plot dihapus.');
    }
}