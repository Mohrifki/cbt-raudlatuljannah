<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $settings = [
            'nama_sekolah'    => Setting::get('nama_sekolah', 'SMA RAUDLATUL JANNAH'),
            'tahun_pelajaran' => Setting::get('tahun_pelajaran', '2025/2026'),
            'mode_ujian'      => Setting::get('mode_ujian', 'PTS'),
            'semester'        => Setting::get('semester', 'Ganjil'),
        ];

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'nama_sekolah'    => 'required|string|max:255',
            'tahun_pelajaran' => 'required|string|max:20',
            'mode_ujian'      => 'required|in:PTS,SAS,USEK',
            'semester'        => 'required|in:Ganjil,Genap',
        ]);

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
