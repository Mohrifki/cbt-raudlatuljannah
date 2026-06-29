<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MediaUploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:20480|mimes:jpg,jpeg,png,gif,webp,svg,mp3,wav,ogg,m4a,mp4,webm,mov',
        ]);

        $path = $request->file('file')->store('soal', 'public');

        return response()->json([
            'location' => asset('storage/' . $path),
        ]);
    }
}