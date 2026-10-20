<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Raja Academy Portal L&D</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Racademy.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        },
                        surface: '#ffffff',
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI',
                            'Roboto', 'sans-serif'
                        ],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
        }

        .form-input {
            @apply w-full px-5 py-3.5 rounded-2xl border-2 border-slate-100 bg-slate-50/50 text-sm font-semibold text-slate-700 focus:bg-white focus:ring-0 focus:border-primary-500 outline-none transition-all placeholder:font-medium placeholder:text-slate-400 hover:border-slate-200;
        }

        .form-label {
            @apply block text-[11px] font-extrabold text-slate-500 uppercase tracking-widest mb-2.5 ml-1;
        }

        .custom-checkbox {
            @apply w-5 h-5 rounded-[6px] border-2 border-slate-300 text-primary-600 focus:ring-primary-500 focus:ring-offset-0 cursor-pointer transition-all;
        }

        .bg-pattern {
            background-image: radial-gradient(rgba(255, 255, 255, 0.2) 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>
</head>

<body class="text-slate-800 antialiased h-screen overflow-hidden flex">

    <!-- Left Panel (Branding) -->
    <div class="hidden lg:flex lg:w-1/2 bg-primary-600 relative overflow-hidden flex-col justify-between p-12">
        <!-- Background Decorations -->
        <div class="absolute inset-0 bg-pattern opacity-30 z-0"></div>
        <div
            class="absolute top-0 right-0 w-[500px] h-[500px] bg-white opacity-10 rounded-full blur-[100px] -mr-40 -mt-40 z-0 pointer-events-none">
        </div>
        <div
            class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-primary-400 opacity-30 rounded-full blur-[100px] -ml-20 -mb-20 z-0 pointer-events-none">
        </div>

        <!-- Logo & Header -->
        <div class="relative z-10">
            <a href="{{ url('/') }}"
                class="inline-flex items-center bg-white px-4 py-2 rounded-2xl shadow-lg hover:shadow-xl hover:bg-slate-50 transition-all">
                <img src="{{ asset('images/Racademy.png') }}" alt="Racademy" class="h-8 w-auto object-contain">
            </a>
        </div>

        <!-- Illustration/Text Area -->
        <div class="relative z-10 mb-20 max-w-lg">
            <div
                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/20 text-white text-[10px] font-bold uppercase tracking-widest mb-6 backdrop-blur-sm">
                <i class="ph-fill ph-shield-check text-emerald-400 text-sm"></i>
                Portal Internal Perusahaan
            </div>
            <h2 class="text-4xl md:text-5xl font-extrabold text-white tracking-tight leading-[1.1] mb-6">
                Kembangkan <br>Kompetensi Anda.
            </h2>
            <p class="text-lg text-primary-100 font-medium leading-relaxed mb-8">
                Masuk ke sistem manajemen pembelajaran (L&D) terintegrasi. Pantau sertifikasi, akses modul pelatihan,
                dan ajukan request training dengan mudah.
            </p>

            <!-- Dashboard Preview Snippet -->
            <div
                class="bg-white/10 backdrop-blur-md border border-white/20 rounded-[24px] p-5 flex items-center gap-5 shadow-2xl">
                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-primary-600 shrink-0">
                    <i class="ph-fill ph-chart-line-up text-2xl"></i>
                </div>
                <div>
                    <p class="text-white font-bold mb-1">Tingkatkan Skor Kompetensi</p>
                    <p class="text-primary-100 text-sm font-medium">Selesaikan pelatihan bulan ini dan penuhi target KPI
                        Anda.</p>
                </div>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="relative z-10 flex items-center gap-6 text-primary-200 text-xs font-semibold">
            <a href="#" class="hover:text-white transition-colors">SOP Portal</a>
            <a href="#" class="hover:text-white transition-colors">Helpdesk IT</a>
            <span>© 2024 HR L&D Dept.</span>
        </div>
    </div>

    <!-- Right Panel (Login Form) -->
    <div class="w-full lg:w-1/2 flex flex-col items-center justify-center bg-white relative overflow-y-auto">

        <!-- Back to Home (Mobile Only) -->
        <div class="absolute top-6 left-6 lg:hidden">
            <a href="{{ url('/') }}"
                class="flex items-center gap-2 text-slate-500 text-sm font-bold hover:text-primary-600 transition-colors">
                <i class="ph-bold ph-arrow-left text-lg"></i> Kembali
            </a>
        </div>

        <div class="w-full max-w-md px-6 py-12 sm:px-10 flex flex-col justify-center min-h-screen lg:min-h-0">

            <!-- Mobile Logo -->
            <div class="flex items-center mb-10 lg:hidden">
                <a href="{{ url('/') }}" class="inline-flex items-center">
                    <img src="{{ asset('images/Racademy.png') }}" alt="Racademy" class="h-10 w-auto object-contain">
                </a>
            </div>

            <!-- Form Header -->
            <div class="mb-10 text-center lg:text-left">
                <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight mb-2">Selamat Datang 👋</h2>
                <p class="text-sm text-slate-500 font-medium">Silakan login menggunakan akun korporat Anda.</p>
            </div>

            <!-- Form -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label
                        class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-widest mb-2.5 ml-1">Email
                        Perusahaan</label>
                    <div class="relative">
                        <div
                            class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400">
                            <i class="ph-fill ph-envelope-simple text-xl"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@raja.id"
                            class="w-full px-5 py-3.5 pl-12 rounded-2xl border-2 border-slate-200 bg-slate-50/50 text-sm font-semibold text-slate-700 focus:bg-white focus:ring-0 focus:border-primary-500 outline-none transition-all placeholder:font-medium placeholder:text-slate-400 hover:border-slate-300"
                            required autofocus autocomplete="username">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-xs font-bold" />
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2.5">
                        <label
                            class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-widest ml-1">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-[11px] font-extrabold text-primary-600 hover:text-primary-700 transition-colors uppercase tracking-wide">Lupa
                                Password?</a>
                        @endif
                    </div>
                    <div class="relative">
                        <div
                            class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400">
                            <i class="ph-fill ph-lock-key text-xl"></i>
                        </div>
                        <input type="password" name="password" id="passwordInput" placeholder="••••••••"
                            class="w-full px-5 py-3.5 pl-12 pr-12 rounded-2xl border-2 border-slate-200 bg-slate-50/50 text-sm font-semibold text-slate-700 focus:bg-white focus:ring-0 focus:border-primary-500 outline-none transition-all placeholder:font-medium placeholder:text-slate-400 hover:border-slate-300"
                            required autocomplete="current-password">
                        <button type="button" onclick="togglePassword()"
                            class="absolute inset-y-0 right-0 pr-5 flex items-center text-slate-400 hover:text-slate-600 transition-colors focus:outline-none">
                            <i class="ph-fill ph-eye text-xl" id="eyeIcon"></i>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-xs font-bold" />
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <input type="checkbox" name="remember" id="remember"
                        class="w-5 h-5 rounded-[6px] border-2 border-slate-300 text-primary-600 focus:ring-primary-500 focus:ring-offset-0 cursor-pointer transition-all accent-primary-600">
                    <label for="remember" class="text-sm font-semibold text-slate-600 cursor-pointer select-none">Ingat
                        saya di perangkat ini</label>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full py-4 bg-primary-600 text-white font-bold rounded-2xl shadow-[0_8px_24px_rgba(37,99,235,0.25)] hover:bg-primary-700 hover:shadow-[0_12px_32px_rgba(37,99,235,0.35)] hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 text-base">
                        Masuk ke Portal
                        <i class="ph-bold ph-sign-in text-lg"></i>
                    </button>
                </div>
            </form>
            <!-- Divider -->
            <div class="mt-8 mb-8 relative flex items-center">
                <div class="flex-grow border-t border-slate-200"></div>
                <span class="flex-shrink-0 mx-4 text-xs font-bold text-slate-400 uppercase tracking-widest">ATAU</span>
                <div class="flex-grow border-t border-slate-200"></div>
            </div>

            <!-- SSO Button -->
            <button type="button"
                class="w-full py-3.5 bg-white text-slate-700 font-bold rounded-2xl border-2 border-slate-200 hover:bg-slate-50 hover:border-slate-300 transition-all flex items-center justify-center gap-3">
                <!-- Microsoft Windows Icon (often used for enterprise SSO) -->
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M11.3999 11.4902H0.999908V0L11.3999 1.48805V11.4902Z" fill="#00A4EF" />
                    <path d="M23 11.4901H12.6001V1.65604L23 3.19503V11.4901Z" fill="#00A4EF" />
                    <path d="M11.3999 22.5029L0.999908 23.9909V12.4909H11.3999V22.5029Z" fill="#00A4EF" />
                    <path d="M23 22.5029H12.6001V12.4909H23V20.8039V22.5029Z" fill="#00A4EF" />
                </svg>
                Masuk dengan SSO Microsoft
            </button>

            <!-- Help/Troubleshoot -->
            <p class="mt-10 text-center text-xs font-medium text-slate-400">
                Mengalami kendala saat login? <br class="sm:hidden">
                <a href="#"
                    class="text-primary-600 hover:text-primary-700 font-bold underline decoration-primary-200 underline-offset-4">Hubungi
                    tim IT Helpdesk</a>
            </p>

        </div>
    </div>

    <!-- Toggle Password Visibility Script -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('passwordInput');
            const eyeIcon = document.getElementById('eyeIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('ph-eye');
                eyeIcon.classList.add('ph-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('ph-eye-slash');
                eyeIcon.classList.add('ph-eye');
            }
        }
    </script>
</body>

</html>
