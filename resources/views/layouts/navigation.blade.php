@php $u = auth()->user(); @endphp

@if ($u && $u->hasRole('siswa'))
    <nav x-data="{ open: false }" class="bg-white border-b border-gray-100 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="<?= route('dashboard') ?>" class="flex items-center gap-2.5">
                    <img src="<?= asset('images/logo.png') ?>" alt="Logo" class="h-10 w-10 object-contain">
                    <span class="font-bold text-gray-800 text-lg">CBT <span class="text-green-600">Smaradja</span></span>
                </a>
                <button @click="open = true" class="p-2.5 rounded-lg text-gray-600 hover:bg-gray-100 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <div x-show="open" x-transition.opacity @click="open = false" class="fixed inset-0 bg-black/40 z-40"
            style="display:none;"></div>

        <aside x-show="open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="fixed top-0 right-0 h-full w-72 bg-white shadow-2xl z-50 flex flex-col" style="display:none;"
            @keydown.escape.window="open = false">
            <div class="p-5 bg-gradient-to-br from-green-600 to-emerald-500 text-white">
                <div class="flex items-center justify-between mb-4">
                    <span class="font-semibold">Menu</span>
                    <button @click="open = false" class="p-1.5 hover:bg-white/20 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="flex items-center gap-3">
                    <?php $fotoNav = $u->photo ? asset('storage/' . $u->photo) : null; ?>
                    @if ($fotoNav)
                        <img src="<?= $fotoNav ?>" alt="Foto"
                            class="w-11 h-11 rounded-full object-cover ring-2 ring-white/30">
                    @else
                        <div
                            class="w-11 h-11 rounded-full bg-white/20 flex items-center justify-center font-bold ring-2 ring-white/30">
                            <?= strtoupper(mb_substr($u->name, 0, 1)) ?></div>
                    @endif
                    <div class="min-w-0">
                        <p class="font-medium text-sm truncate"><?= e($u->name) ?></p>
                        <p class="text-xs text-green-50 truncate"><?= e($u->email) ?></p>
                    </div>
                </div>
            </div>
            <div class="flex-1 p-4 space-y-1 overflow-y-auto">
                <a href="<?= route('dashboard') ?>"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-700 transition"><i
                        class="fa-solid fa-house w-5 text-center"></i> Dashboard</a>
                <a href="<?= route('siswa.exams.index') ?>"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-700 transition"><i
                        class="fa-solid fa-clipboard-list w-5 text-center"></i> Daftar Ujian</a>
                <a href="<?= route('profile.edit') ?>"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-700 transition"><i
                        class="fa-solid fa-user w-5 text-center"></i> Profil</a>
            </div>
            <div class="p-4 border-t border-gray-100">
                <form method="POST" action="<?= route('logout') ?>">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-red-600 hover:bg-red-50 transition"><i
                            class="fa-solid fa-right-from-bracket w-5 text-center"></i> Keluar</button>
                </form>
            </div>
        </aside>
    </nav>
@else
    <nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="shrink-0 flex items-center">
                        <a href="<?= route('dashboard') ?>">
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                        </a>
                    </div>
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-nav-link>
                    </div>
                </div>
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                <div><?= e($u->name) ?></div>
                                <svg class="ms-2 -me-0.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">Profil</x-dropdown-link>
                            <form method="POST" action="<?= route('logout') ?>">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">Keluar</x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-responsive-nav-link>
            </div>
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800"><?= e($u->name) ?></div>
                    <div class="font-medium text-sm text-gray-500"><?= e($u->email) ?></div>
                </div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">Profil</x-responsive-nav-link>
                    <form method="POST" action="<?= route('logout') ?>">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">Keluar</x-responsive-nav-link>
                    </form>
                </div>
            </div>
        </div>
    </nav>
@endif
