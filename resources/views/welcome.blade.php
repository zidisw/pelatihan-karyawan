<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raja Academy - Training Management System</title>
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
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #ffffff; }
        .gradient-text {
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero-pattern {
            background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="text-slate-800 antialiased overflow-x-hidden">

    <!-- Navigation Bar -->
    <nav class="fixed top-0 inset-x-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-100 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    <img src="{{ asset('images/Racademy.png') }}" alt="Racademy" class="h-10 w-auto object-contain">
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="#katalog" class="text-sm font-semibold text-slate-500 hover:text-primary-600 transition-colors">Katalog Pelatihan</a>
                    <a href="#alur" class="text-sm font-semibold text-slate-500 hover:text-primary-600 transition-colors">Alur Pengajuan</a>
                    <a href="#panduan" class="text-sm font-semibold text-slate-500 hover:text-primary-600 transition-colors">Panduan Pengguna</a>
                    <a href="#helpdesk" class="text-sm font-semibold text-slate-500 hover:text-primary-600 transition-colors">Helpdesk HR</a>
                </div>

                <!-- CTA Buttons -->
                <div class="hidden md:flex items-center gap-4">
                    <a href="{{ route('login') }}" class="px-5 py-2.5 bg-primary-600 text-white text-sm font-bold rounded-xl shadow-[0_4px_12px_rgba(37,99,235,0.2)] hover:bg-primary-700 hover:shadow-[0_6px_16px_rgba(37,99,235,0.3)] hover:-translate-y-0.5 transition-all flex items-center gap-2">
                        <i class="ph-bold ph-sign-in"></i>
                        Login Karyawan
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button class="text-slate-500 hover:text-slate-700 p-2">
                        <i class="ph ph-list text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <!-- Decorative Backgrounds -->
        <div class="absolute inset-0 hero-pattern opacity-50 z-0"></div>
        <div class="absolute top-20 right-0 w-[500px] h-[500px] bg-primary-300/30 rounded-full blur-[100px] -z-10 mix-blend-multiply"></div>
        <div class="absolute top-40 left-10 w-[400px] h-[400px] bg-purple-300/20 rounded-full blur-[100px] -z-10 mix-blend-multiply"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-8 items-center">

                <!-- Hero Text -->
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50 border border-blue-100 text-primary-600 text-[11px] font-bold uppercase tracking-widest mb-6">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-500"></span>
                        </span>
                        Sistem Informasi Karyawan
                    </div>
                    <h1 class="text-5xl md:text-6xl font-extrabold text-slate-900 tracking-tight leading-[1.1] mb-6">
                        Pusat Pengembangan <br>
                        <span class="bg-gradient-to-r from-primary-600 to-blue-400 gradient-text">Kompetensi Internal.</span>
                    </h1>
                    <p class="text-lg text-slate-500 font-medium mb-10 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                        Akses modul pelatihan terbaru, pantau masa berlaku sertifikasi Anda, dan ajukan request training dengan mudah melalui portal terintegrasi Raja Academy.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                        <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 bg-primary-600 text-white font-bold rounded-2xl shadow-[0_8px_24px_rgba(37,99,235,0.25)] hover:bg-primary-700 hover:shadow-[0_12px_32px_rgba(37,99,235,0.35)] hover:-translate-y-1 transition-all flex items-center justify-center gap-2 text-lg">
                            Masuk ke Portal
                            <i class="ph-bold ph-arrow-right"></i>
                        </a>
                        <a href="#" class="w-full sm:w-auto px-8 py-4 bg-white text-slate-700 font-bold rounded-2xl border-2 border-slate-200 hover:bg-slate-50 hover:border-slate-300 transition-all flex items-center justify-center gap-2 text-lg">
                            <i class="ph-fill ph-book-open text-xl text-slate-400"></i>
                            Baca Panduan
                        </a>
                    </div>
                    <p class="mt-6 text-sm text-slate-400 font-medium"><i class="ph-fill ph-lock-key text-slate-300 mr-1"></i> Gunakan email perusahaan (@raja.id) untuk login</p>
                </div>

                <!-- Hero Image/Mockup -->
                <div class="relative mx-auto w-full max-w-lg lg:max-w-none">
                    <!-- Dashboard Mockup Frame -->
                    <div class="relative rounded-[32px] bg-slate-800 p-2 shadow-[0_20px_50px_rgba(0,0,0,0.15)] transform lg:rotate-[-2deg] hover:rotate-0 transition-transform duration-500 border border-slate-700/50">
                        <div class="absolute top-4 left-4 flex gap-1.5 z-20">
                            <div class="w-2.5 h-2.5 rounded-full bg-slate-600"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-slate-600"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-slate-600"></div>
                        </div>
                        <div class="bg-slate-50 rounded-[24px] overflow-hidden border border-slate-700 relative h-[400px]">
                            <!-- Mockup Content (Abstract Dashboard) -->
                            <div class="absolute inset-0 p-6 flex flex-col pt-10">
                                <div class="w-1/3 h-6 bg-slate-200 rounded-lg mb-6"></div>
                                <div class="flex gap-4 mb-6">
                                    <div class="flex-1 h-24 bg-white rounded-xl shadow-sm border border-slate-100 p-4">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg mb-2"></div>
                                        <div class="w-16 h-4 bg-slate-200 rounded mb-1"></div>
                                        <div class="w-10 h-6 bg-slate-800 rounded"></div>
                                    </div>
                                    <div class="flex-1 h-24 bg-white rounded-xl shadow-sm border border-slate-100 p-4">
                                        <div class="w-8 h-8 bg-emerald-100 rounded-lg mb-2"></div>
                                        <div class="w-16 h-4 bg-slate-200 rounded mb-1"></div>
                                        <div class="w-10 h-6 bg-slate-800 rounded"></div>
                                    </div>
                                </div>
                                <div class="flex-1 bg-white rounded-xl shadow-sm border border-slate-100 p-4">
                                    <div class="w-full h-8 bg-slate-100 rounded-lg mb-4"></div>
                                    <div class="w-full h-8 bg-slate-50 rounded-lg mb-2"></div>
                                    <div class="w-full h-8 bg-slate-50 rounded-lg mb-2"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Floating Element 1 -->
                        <div class="absolute -bottom-6 -left-8 bg-white p-4 rounded-2xl shadow-xl border border-slate-100 flex items-center gap-4 animate-bounce" style="animation-duration: 3s;">
                            <div class="w-12 h-12 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-500">
                                <i class="ph-fill ph-check-circle text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase">Status Sertifikasi</p>
                                <p class="text-sm font-bold text-slate-800">Ahli K3 Umum - <span class="text-emerald-500">Aktif</span></p>
                            </div>
                        </div>

                        <!-- Floating Element 2 -->
                        <div class="absolute -top-6 -right-6 bg-white p-4 rounded-2xl shadow-xl border border-slate-100 flex items-center gap-4 animate-bounce" style="animation-duration: 4s; animation-delay: 1s;">
                            <div class="w-10 h-10 bg-primary-50 rounded-full flex items-center justify-center text-primary-600">
                                <i class="ph-fill ph-calendar-check text-xl"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase">Jadwal Terdekat</p>
                                <p class="text-sm font-bold text-slate-800">Fire Safety - 15 Feb</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Internal Department Cloud -->
    <div class="py-10 border-y border-slate-100 bg-slate-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">Portal Pembelajaran Terintegrasi Untuk Seluruh Departemen</p>
            <div class="flex flex-wrap justify-center items-center gap-4 md:gap-6">
                <span class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl shadow-sm text-sm">HRGA</span>
                <span class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl shadow-sm text-sm">TC</span>
                <span class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl shadow-sm text-sm">CIVIL</span>
                <span class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl shadow-sm text-sm">PLM</span>
                <span class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl shadow-sm text-sm">MED</span>
                <span class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl shadow-sm text-sm">LOGISTIK</span>
                <span class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl shadow-sm text-sm">MOD</span>
                <span class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl shadow-sm text-sm">HSE</span>
            </div>
        </div>
    </div>

    <!-- Internal Features Section -->
    <div id="fasilitas" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-primary-600 font-bold tracking-widest text-xs uppercase mb-3">Fasilitas Karyawan</h2>
                <h3 class="text-3xl md:text-4xl font-extrabold text-slate-800 mb-4 tracking-tight">Semua akses L&D dalam satu akun.</h3>
                <p class="text-slate-500 font-medium">Tinggalkan proses manual berbasis kertas. Sekarang Anda bisa mendaftar pelatihan dan melihat sertifikasi secara mandiri.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                <!-- Feature 1 -->
                <div class="bg-surface p-8 rounded-[32px] border border-slate-100 shadow-[0_8px_30px_rgba(0,0,0,0.03)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.08)] hover:-translate-y-1 transition-all group">
                    <div class="w-14 h-14 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-purple-600 group-hover:text-white transition-all">
                        <i class="ph-fill ph-book-open text-3xl"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-800 mb-3">Katalog Pelatihan</h4>
                    <p class="text-slate-500 text-sm leading-relaxed font-medium">Lihat jadwal pelatihan internal yang diselenggarakan perusahaan. Daftar ke program yang sesuai dengan jalur karir Anda.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-surface p-8 rounded-[32px] border border-slate-100 shadow-[0_8px_30px_rgba(0,0,0,0.03)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.08)] hover:-translate-y-1 transition-all group">
                    <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                        <i class="ph-fill ph-medal text-3xl"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-800 mb-3">Monitor Sertifikasi</h4>
                    <p class="text-slate-500 text-sm leading-relaxed font-medium">Pantau lisensi dan sertifikasi yang Anda miliki. Sistem akan mengirim email pengingat 30 hari sebelum masa berlaku habis.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-surface p-8 rounded-[32px] border border-slate-100 shadow-[0_8px_30px_rgba(0,0,0,0.03)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.08)] hover:-translate-y-1 transition-all group">
                    <div class="w-14 h-14 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-orange-600 group-hover:text-white transition-all">
                        <i class="ph-fill ph-paper-plane-right text-3xl"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-800 mb-3">Pengajuan (Request)</h4>
                    <p class="text-slate-500 text-sm leading-relaxed font-medium">Butuh pelatihan khusus eksternal? Ajukan form Request Training secara online agar dapat langsung ditinjau oleh atasan dan HR.</p>
                </div>

            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="py-20 relative">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-primary-600 rounded-[40px] p-10 md:p-16 text-center relative overflow-hidden shadow-[0_20px_50px_rgba(37,99,235,0.3)]">
                <!-- Decorative Circles -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl -mr-20 -mt-20"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-primary-400 opacity-20 rounded-full blur-3xl -ml-20 -mb-20"></div>

                <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-6 tracking-tight relative z-10">Siap mengembangkan <br>kompetensi Anda?</h2>
                <p class="text-primary-100 text-lg mb-10 max-w-2xl mx-auto relative z-10 font-medium">Login menggunakan akun Single Sign-On (SSO) perusahaan untuk mengakses kalender pelatihan dan mengunduh sertifikat Anda.</p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 relative z-10">
                    <a href="#" class="w-full sm:w-auto px-8 py-4 bg-white text-primary-600 font-extrabold rounded-2xl shadow-xl hover:bg-slate-50 hover:-translate-y-1 transition-all text-lg flex items-center justify-center gap-2">
                        <i class="ph-bold ph-lock-key"></i>
                        Login SSO Perusahaan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Internal Footer -->
    <footer class="bg-slate-900 pt-16 pb-8 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-1">
                    <div class="flex items-center mb-6">
                        <div class="bg-white px-3.5 py-2 rounded-xl shadow-sm inline-flex items-center">
                            <img src="{{ asset('images/Racademy.png') }}" alt="Racademy" class="h-7 w-auto object-contain">
                        </div>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed font-medium mb-6">Fasilitas portal pengembangan kompetensi yang dikelola oleh Departemen HR L&D Raja Academy.</p>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-6">Akses Cepat</h4>
                    <ul class="space-y-4 text-sm font-medium text-slate-400">
                        <li><a href="#" class="hover:text-primary-400 transition-colors">Dashboard Karyawan</a></li>
                        <li><a href="#" class="hover:text-primary-400 transition-colors">Jadwal Pelatihan</a></li>
                        <li><a href="#" class="hover:text-primary-400 transition-colors">Unduh Sertifikat</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-6">Bantuan & Informasi</h4>
                    <ul class="space-y-4 text-sm font-medium text-slate-400">
                        <li><a href="#" class="hover:text-primary-400 transition-colors">Panduan Penggunaan Portal</a></li>
                        <li><a href="#" class="hover:text-primary-400 transition-colors">FAQ Karyawan</a></li>
                        <li><a href="#" class="hover:text-primary-400 transition-colors">Helpdesk IT (Lupa Password)</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-6">Kebijakan Internal</h4>
                    <ul class="space-y-4 text-sm font-medium text-slate-400">
                        <li><a href="#" class="hover:text-primary-400 transition-colors">SOP Pelatihan Karyawan</a></li>
                        <li><a href="#" class="hover:text-primary-400 transition-colors">Aturan Lisensi & Sertifikasi</a></li>
                        <li><a href="#" class="hover:text-primary-400 transition-colors">Kebijakan Privasi Data</a></li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-800 text-center md:text-left flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-slate-500 text-sm font-medium">© 2024 HR Learning & Development - Raja Academy.</p>
                <div class="flex items-center gap-2 text-slate-500 text-sm font-medium">
                    <span>Internal Use Only</span>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
