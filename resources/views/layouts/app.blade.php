<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Raja Academy - Admin Panel</title>

    <!-- Google Fonts & Phosphor Icons (Optimized) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- Pre-compiled Vite CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom CSS -->
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Responsive tweaks */
        @media (max-width: 1024px) {
            .max-w-7xl { padding-left: 1rem; padding-right: 1rem; }
        }
        @media (max-width: 768px) {
            .p-8 { padding: 1rem !important; }
            .p-6 { padding: 0.75rem !important; }
            .text-4xl { font-size: 1.5rem !important; }
            .text-3xl { font-size: 1.25rem !important; }
            .rounded-[32px] { border-radius: 1rem !important; }
            main { padding-bottom: 5.5rem; }
        }
        @media (max-width: 480px) {
            .text-4xl { font-size: 1.25rem !important; }
            .text-3xl { font-size: 1.125rem !important; }
            .w-64 { width: 14rem !important; }
        }
    </style>
</head>
<body class="text-slate-800 h-screen overflow-hidden flex antialiased">

    <!-- Overlay untuk sidebar mobile -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/50 z-20 hidden md:hidden transition-opacity opacity-0 duration-300 backdrop-blur-sm"></div>

    <!-- ========================================== -->
    <!-- SIDEBAR KIRI -->
    <!-- ========================================== -->
    <aside id="sidebar" class="w-64 bg-surface border-r border-slate-100 flex flex-col h-full shrink-0 z-30 absolute md:relative transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out shadow-[4px_0_24px_rgba(0,0,0,0.02)]">

        <!-- Logo -->
        <div class="h-20 flex items-center px-6 border-b border-slate-50 bg-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center shrink-0 text-white shadow-md shadow-primary-500/20">
                    <i class="ph-fill ph-graduation-cap text-2xl"></i>
                </div>
                <div>
                    <h1 class="font-bold text-slate-800 text-base leading-tight tracking-tight">Raja Academy</h1>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Admin Panel</p>
                </div>
            </div>
        </div>

        <!-- Navigation Links Dinamis Berdasarkan Role -->
        <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-2 bg-surface">

            <!-- MENU GLOBAL -->
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-colors {{ request()->routeIs('dashboard') || request()->routeIs('*.dashboard') ? 'bg-primary-50 text-primary-600 font-bold shadow-[inset_0_1px_2px_rgba(255,255,255,0.5)] relative' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700 font-semibold' }}">
                @if(request()->routeIs('dashboard') || request()->routeIs('*.dashboard')) <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-8 bg-primary-600 rounded-r-full shadow-[0_0_12px_rgba(37,99,235,0.4)]"></div> @endif
                <i class="ph-fill ph-squares-four text-xl"></i>
                <span>Dashboard</span>
            </a>

            <!-- MENU ADMIN PORTAL & SUPERADMIN (5 MENU UTAMA PRD 5.1) -->
            @if(auth()->check() && in_array(auth()->user()->role, ['superadmin', 'hrd']))
                <a href="{{ route('hrd.employees.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-colors {{ request()->routeIs('hrd.employees.*') ? 'bg-primary-50 text-primary-600 font-bold relative' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700 font-semibold' }}">
                    @if(request()->routeIs('hrd.employees.*')) <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-8 bg-primary-600 rounded-r-full"></div> @endif
                    <i class="ph-fill ph-users text-xl"></i><span>Data Karyawan</span>
                </a>
                <a href="{{ route('hrd.participants.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-colors {{ request()->routeIs('hrd.participants.*') ? 'bg-primary-50 text-primary-600 font-bold relative' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700 font-semibold' }}">
                    @if(request()->routeIs('hrd.participants.*')) <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-8 bg-primary-600 rounded-r-full"></div> @endif
                    <i class="ph-fill ph-users-three text-xl"></i><span>Riwayat Pelatihan</span>
                </a>
                <a href="{{ route('hrd.trainings.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-colors {{ request()->routeIs('hrd.trainings.*') ? 'bg-primary-50 text-primary-600 font-bold relative' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700 font-semibold' }}">
                    @if(request()->routeIs('hrd.trainings.*')) <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-8 bg-primary-600 rounded-r-full"></div> @endif
                    <i class="ph-fill ph-graduation-cap text-xl"></i><span>Katalog Pelatihan</span>
                </a>
                <a href="{{ route('hrd.requests.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-colors {{ request()->routeIs('hrd.requests.*') ? 'bg-primary-50 text-primary-600 font-bold relative' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700 font-semibold' }}">
                    @if(request()->routeIs('hrd.requests.*')) <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-8 bg-primary-600 rounded-r-full"></div> @endif
                    <i class="ph-fill ph-clipboard-text text-xl"></i><span>Request Training</span>
                </a>
            @endif

            <!-- MENU KHUSUS SUPERADMIN (MASTER DEPARTEMEN & MANAJEMEN AKUN) -->
            @if(auth()->check() && auth()->user()->role == 'superadmin')
                <div class="pt-3 pb-1 px-4">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Sistem Superadmin</p>
                </div>
                <a href="{{ route('superadmin.departments.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-colors {{ request()->routeIs('superadmin.departments.*') ? 'bg-primary-50 text-primary-600 font-bold relative' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700 font-semibold' }}">
                    @if(request()->routeIs('superadmin.departments.*')) <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-8 bg-primary-600 rounded-r-full"></div> @endif
                    <i class="ph-fill ph-buildings text-xl"></i><span>Master Departemen</span>
                </a>
                <a href="{{ route('superadmin.users.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-colors {{ request()->routeIs('superadmin.users.*') ? 'bg-primary-50 text-primary-600 font-bold relative' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700 font-semibold' }}">
                    @if(request()->routeIs('superadmin.users.*')) <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-8 bg-primary-600 rounded-r-full"></div> @endif
                    <i class="ph-fill ph-shield-check text-xl"></i><span>Manajemen Akun</span>
                </a>
            @endif

            <!-- MENU USER PORTAL / ADMIN DEPARTEMEN (PRD 5.2) -->
            @if(auth()->check() && auth()->user()->role == 'departemen')
                <a href="{{ route('department.trainings.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-colors {{ request()->routeIs('department.trainings.index') || request()->routeIs('department.trainings.request') ? 'bg-primary-50 text-primary-600 font-bold relative' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700 font-semibold' }}">
                    @if(request()->routeIs('department.trainings.index') || request()->routeIs('department.trainings.request')) <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-8 bg-primary-600 rounded-r-full"></div> @endif
                    <i class="ph-fill ph-book-open text-xl"></i><span>Daftar Pelatihan</span>
                </a>
                <a href="{{ route('department.trainings.history') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-colors {{ request()->routeIs('department.trainings.history') ? 'bg-primary-50 text-primary-600 font-bold relative' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700 font-semibold' }}">
                    @if(request()->routeIs('department.trainings.history')) <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-8 bg-primary-600 rounded-r-full"></div> @endif
                    <i class="ph-fill ph-clock-counter-clockwise text-xl"></i><span>Riwayat Pengajuan</span>
                </a>
                <a href="{{ route('department.employees.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-colors {{ request()->routeIs('department.employees.*') ? 'bg-primary-50 text-primary-600 font-bold relative' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700 font-semibold' }}">
                    @if(request()->routeIs('department.employees.*')) <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-8 bg-primary-600 rounded-r-full"></div> @endif
                    <i class="ph-fill ph-users text-xl"></i><span>Data Karyawan</span>
                </a>
            @endif
        </nav>

        <!-- Logout Form -->
        <div class="p-4 border-t border-slate-50 bg-surface">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3.5 text-red-500 hover:bg-red-50 rounded-2xl font-semibold transition-colors">
                    <i class="ph-bold ph-sign-out text-xl"></i>
                    <span>Log Out</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- ========================================== -->
    <!-- AREA KONTEN UTAMA (KANAN) -->
    <!-- ========================================== -->
    <main class="flex-1 flex flex-col h-full relative overflow-hidden bg-[#f8fafc]">

        <!-- HEADER ATAS -->
        <header class="h-20 bg-transparent flex items-center justify-between px-4 md:px-6 z-10 shrink-0">
            <div class="flex items-center gap-4 w-full max-w-xl">
                <!-- Tombol Mobile Menu -->
                <button id="mobileMenuBtn" class="md:hidden p-2.5 text-slate-500 hover:bg-slate-100 hover:text-slate-700 rounded-xl transition-colors">
                    <i class="ph ph-list text-2xl"></i>
                </button>

                <!-- Menampilkan judul header dari slot nama halaman (jika ada) -->
                @if (isset($header))
                    <h2 class="text-xl font-bold text-slate-800 hidden sm:block">
                        {{ $header }}
                    </h2>
                @endif
            </div>

            <div class="flex items-center gap-5 shrink-0 ml-4">
                @auth
                    @php
                        $headerNotifications = \App\Models\Notification::where('user_id', Auth::id())
                            ->latest()
                            ->take(10)
                            ->get();
                        $unreadNotificationsCount = \App\Models\Notification::where('user_id', Auth::id())
                            ->where('is_read', false)
                            ->count();
                    @endphp
                    <div class="relative" id="notificationDropdownWrapper">
                        <button type="button"
                                id="notificationBellBtn"
                                onclick="toggleNotificationDropdown()"
                                class="relative text-slate-500 hover:text-primary-600 transition-colors p-2.5 rounded-full hover:bg-slate-100"
                                title="Notifikasi Sistem">
                            <i class="ph ph-bell text-2xl"></i>
                            @if($unreadNotificationsCount > 0)
                                <span class="absolute top-1.5 right-1.5 min-w-[18px] h-[18px] px-1 bg-rose-500 text-white text-[10px] font-extrabold rounded-full border-2 border-white flex items-center justify-center">
                                    {{ $unreadNotificationsCount > 99 ? '99+' : $unreadNotificationsCount }}
                                </span>
                            @endif
                        </button>

                        <!-- Dropdown Panel Notifikasi In-App (PRD Bab 12) -->
                        <div id="notificationDropdownPanel"
                             class="hidden absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-xl border border-slate-100 z-50 overflow-hidden">
                            <div class="px-4 py-3.5 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Notifikasi</h3>
                                    @if($unreadNotificationsCount > 0)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-700">
                                            {{ $unreadNotificationsCount }} Baru
                                        </span>
                                    @endif
                                </div>

                                @if($unreadNotificationsCount > 0)
                                    <form action="{{ route('notifications.readAll') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="text-[11px] font-bold text-primary-600 hover:underline">
                                            Tandai Semua Dibaca
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <div class="max-h-80 overflow-y-auto divide-y divide-slate-50">
                                @forelse($headerNotifications as $notif)
                                    <div class="p-3.5 hover:bg-slate-50/80 transition-colors {{ !$notif->is_read ? 'bg-blue-50/40' : '' }}">
                                        <div class="flex items-start justify-between gap-2">
                                            <div class="flex items-start gap-2.5 min-w-0">
                                                <span class="mt-1 w-2 h-2 rounded-full shrink-0 {{ !$notif->is_read ? 'bg-primary-600' : 'bg-slate-300' }}"></span>
                                                <div class="min-w-0">
                                                    <p class="text-xs font-extrabold text-slate-800 leading-snug">{{ $notif->judul }}</p>
                                                    <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">{{ $notif->pesan }}</p>
                                                    <span class="text-[10px] font-semibold text-slate-400 mt-1 block">
                                                        {{ $notif->created_at?->diffForHumans() }}
                                                    </span>
                                                </div>
                                            </div>

                                            @if(!$notif->is_read)
                                                <form action="{{ route('notifications.read', $notif->id) }}" method="POST" class="shrink-0 m-0">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            title="Tandai sudah dibaca"
                                                            class="px-2 py-1 rounded-lg bg-white border border-slate-200 text-[10px] font-bold text-slate-600 hover:bg-primary-600 hover:text-white hover:border-primary-600 transition-colors">
                                                        Baca
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="py-8 px-4 text-center text-slate-400 text-xs">
                                        <i class="ph-fill ph-bell-slash text-2xl text-slate-300 mb-1.5 block"></i>
                                        Belum ada notifikasi terbaru.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endauth

                <div class="h-8 w-px bg-slate-200 mx-1 hidden sm:block"></div>

                <!-- Profil User -->
                @auth
                <div class="flex items-center gap-3.5 group cursor-default">
                    <div class="hidden md:block text-right">
                        <p class="text-sm font-extrabold text-slate-800 tracking-tight">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-primary-600 font-bold tracking-widest mt-0.5 uppercase">{{ Auth::user()->role }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-slate-200 overflow-hidden border-2 border-white shadow-sm group-hover:shadow-md transition-all">
                        <img src="https://api.dicebear.com/7.x/notionists/svg?seed={{ urlencode(Auth::user()->name) }}&backgroundColor=e2e8f0" alt="Avatar" class="w-full h-full object-cover">
                    </div>
                </div>
                @endauth
            </div>
        </header>

        <!-- ========================================== -->
        <!-- KONTEN DINAMIS DARI MASING-MASING HALAMAN -->
        <!-- ========================================== -->
        <div class="flex-1 overflow-y-auto px-4 md:px-6 py-2">
            {{ $slot }}
        </div>

    </main>

    <!-- SCRIPT SIDEBAR MOBILE & DROPDOWN NOTIFIKASI -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const overlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            if (!sidebar) return;
            const isClosed = sidebar.classList.contains('-translate-x-full');
            if (isClosed) {
                sidebar.classList.remove('-translate-x-full');
                if (overlay) {
                    overlay.classList.remove('hidden');
                    setTimeout(() => overlay.classList.remove('opacity-0'), 10);
                }
            } else {
                sidebar.classList.add('-translate-x-full');
                if (overlay) {
                    overlay.classList.add('opacity-0');
                    setTimeout(() => overlay.classList.add('hidden'), 300);
                }
            }
        }

        if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', toggleSidebar);
        if (overlay) overlay.addEventListener('click', toggleSidebar);

        function toggleNotificationDropdown() {
            const panel = document.getElementById('notificationDropdownPanel');
            if (panel) {
                panel.classList.toggle('hidden');
            }
        }

        document.addEventListener('click', function (e) {
            const wrapper = document.getElementById('notificationDropdownWrapper');
            const panel = document.getElementById('notificationDropdownPanel');
            if (wrapper && panel && !wrapper.contains(e.target)) {
                panel.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
