<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CBT Smaradja</title>
    <link rel="icon" type="image/png" href="<?= asset('images/logo.png') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 h-screen overflow-hidden flex items-center justify-center p-4">

    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-2xl overflow-hidden grid md:grid-cols-2 max-h-[calc(100vh-2rem)]">

        <!-- KIRI: FORM -->
        <div class="p-6 sm:p-10 flex flex-col justify-center overflow-y-auto">
            <div class="flex items-center gap-3 mb-6">
                <img src="<?= asset('images/logo.png') ?>" alt="Logo" class="w-11 h-11 object-contain">
                <span class="font-bold text-xl text-gray-800">CBT <span class="text-green-600">Smaradja</span></span>
            </div>

            <h1 class="text-2xl font-bold text-gray-800 mb-1">Selamat Datang 👋</h1>
            <p class="text-gray-500 text-sm mb-6">Masuk untuk mulai mengerjakan ujian atau mengelola sistem.</p>

            @if (session('status'))
                <div class="mb-4 rounded bg-green-100 text-green-800 px-4 py-2 text-sm"><?= session('status') ?></div>
            @endif

            <form method="POST" action="<?= route('login') ?>" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fa-solid fa-envelope"></i></span>
                        <input type="email" name="email" value="<?= old('email') ?>" required autofocus class="w-full pl-10 border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="email@sekolah.sch.id">
                    </div>
                    @error('email')<p class="text-red-600 text-sm mt-1"><?= $message ?></p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" name="password" required class="w-full pl-10 border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="Masukkan password">
                    </div>
                    @error('password')<p class="text-red-600 text-sm mt-1"><?= $message ?></p>@enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                        Ingat saya
                    </label>
                    @if (Route::has('password.request'))
                        <a href="<?= route('password.request') ?>" class="text-sm text-green-600 hover:underline">Lupa password?</a>
                    @endif
                </div>

                <button type="submit" class="w-full bg-gray-900 hover:bg-gray-800 text-white font-semibold py-3 rounded-lg transition">Masuk</button>
            </form>

            <p class="text-center text-xs text-gray-400 mt-6">© <?= date('Y') ?> CBT Smaradja. Hak cipta dilindungi.</p>
        </div>

        <!-- KANAN: FOTO SEKOLAH -->
        <div class="hidden md:block relative">
            <img src="<?= asset('images/logosekolah.jpg') ?>" alt="Sekolah" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
        </div>

    </div>

</body>
</html>