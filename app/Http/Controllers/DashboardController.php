<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Subject;

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

    public function admin()
    {
        $stats = [
            'siswa' => User::role('siswa')->count(),
            'guru'  => User::role('guru')->count(),
            'mapel' => Subject::count(),
            'kelas' => SchoolClass::count(),
        ];
        $users = User::with('roles')->latest()->take(8)->get();
        return view('admin.dashboard', compact('stats', 'users'));
    }
}
