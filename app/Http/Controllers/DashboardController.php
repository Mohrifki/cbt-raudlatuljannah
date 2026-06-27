<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;   

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */ 
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->hasRole('guru')) {
            return redirect()->route('guru.dashboard');
        }
        return redirect()->route('siswa.dashboard');
    }
}
