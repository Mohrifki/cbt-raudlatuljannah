<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Panel Siswa</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-8">
                <h1 class="text-2xl font-bold text-blue-600">Dashboard Siswa 🎓</h1>
                <p class="mt-2 text-gray-600">Selamat datang, <?= auth()->user()->name ?></p>
            </div>
        </div>
    </div>
</x-app-layout>