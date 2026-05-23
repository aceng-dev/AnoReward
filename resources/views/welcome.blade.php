<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AnoReward - Smart Project Management & Reward System</title>
    
    <!-- Fonts & Tailwind (Breeze Default) -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-[#fafafa] text-[#1b1b18] font-['Figtree']">

    <!-- Navbar Section -->
    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center gap-2">
                <span class="text-2xl font-black tracking-wider text-[#780000]">Ano<span class="text-[#1b1b18]">Reward</span></span>
            </div>

            <!-- Auth Navigation Links -->
            <div class="flex items-center gap-4">
                @if (Route::has('login'))
                    @auth
                        <!-- Jika Sudah Login, Arahkan ke Dashboard Sesuai Role Berdasarkan Helper Auth -->
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-[#780000] hover:underline">Go to Dashboard</a>
                        @elseif(auth()->user()->role === 'manager')
                            <a href="{{ route('manager.dashboard') }}" class="text-sm font-semibold text-[#780000] hover:underline">Go to Dashboard</a>
                        @else
                            <a href="{{ route('developer.dashboard') }}" class="text-sm font-semibold text-[#780000] hover:underline">Go to Dashboard</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-[#4a4a4a] hover:text-[#1b1b18] transition">Log in</a>
                        
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex justify-center py-2.5 px-5 rounded-xl text-sm font-semibold text-white bg-[#780000] hover:bg-[#5f0000] shadow-sm active:scale-[0.98] transition-all duration-150">
                                Get Started
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="max-w-7xl mx-auto px-6 pt-16 pb-24 text-center lg:pt-24">
        <div class="max-w-3xl mx-auto">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#780000]/10 text-[#780000] mb-4">
                🚀 Smart Decision System for Agile Teams
            </span>
            <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight text-[#1b1b18] leading-none">
                Manage Tasks. Evaluate Performance. <span class="text-[#780000]">Get Rewarded.</span>
            </h1>
            <p class="mt-6 text-lg text-[#4a4a4a] leading-relaxed">
                AnoReward merevolusi cara industri menilai produktivitas developer. Gabungan platform manajemen proyek kolaboratif dengan kalkulasi sistem cerdas berbobot secara objektif untuk penentuan insentif dan kenaikan gaji berkala.
            </p>
            <div class="mt-10 flex justify-center items-center gap-4">
                <a href="{{ route('register') }}" class="py-3.5 px-8 rounded-xl text-base font-semibold text-white bg-[#780000] hover:bg-[#5f0000] shadow-lg shadow-[#780000]/20 active:scale-[0.98] transition-all duration-150">
                    Mulai Sekarang
                </a>
                <a href="#features" class="py-3.5 px-6 rounded-xl text-base font-semibold text-[#4a4a4a] hover:text-[#1b1b18] hover:bg-gray-100 transition">
                    Pelajari Fitur ↓
                </a>
            </div>
        </div>
    </header>

    <!-- Features Section (Menjelaskan Isi & Multi-role Aplikasi) -->
    <section id="features" class="bg-white border-y border-gray-100 py-24">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl font-bold text-[#1b1b18]">Satu Ekosistem, Tiga Akses Peran</h2>
                <p class="mt-4 text-[#4a4a4a]">Dirancang khusus untuk menciptakan transparansi antara manajemen perusahaan dan tim teknis eksekutor.</p>
            </div>

            <!-- Grid 3 Roles -->
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Card Developer -->
                <div class="bg-[#fafafa] border border-gray-100 p-8 rounded-2xl transition hover:shadow-md">
                    <div class="w-12 h-12 rounded-xl bg-gray-900 text-white flex items-center justify-center font-bold text-lg mb-6">🧑‍💻</div>
                    <h3 class="text-xl font-bold text-[#1b1b18]">Developer Dashboard</h3>
                    <p class="mt-3 text-sm text-[#4a4a4a] leading-relaxed">
                        Kelola tugas harian melalui antarmuka Kanban Board yang interaktif. Setiap kontribusi dan ketepatan waktu Anda dikonversi menjadi poin produktivitas yang transparan.
                    </p>
                </div>

                <!-- Card Manager -->
                <div class="bg-[#fafafa] border border-gray-100 p-8 rounded-2xl transition hover:shadow-md">
                    <div class="w-12 h-12 rounded-xl bg-[#780000] text-white flex items-center justify-center font-bold text-lg mb-6">📊</div>
                    <h3 class="text-xl font-bold text-[#1b1b18]">Project Manager Suite</h3>
                    <p class="mt-3 text-sm text-[#4a4a4a] leading-relaxed">
                        Delegasikan tugas, pantau tenggat waktu, dan berikan penilaian kualitas hasil repositori kode tim Anda secara objektif langsung setelah tugas diselesaikan.
                    </p>
                </div>

                <!-- Card Owner/Admin -->
                <div class="bg-[#fafafa] border border-gray-100 p-8 rounded-2xl transition hover:shadow-md">
                    <div class="w-12 h-12 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold text-lg mb-6">👑</div>
                    <h3 class="text-xl font-bold text-[#1b1b18]">Executive Decision Center</h3>
                    <p class="mt-3 text-sm text-[#4a4a4a] leading-relaxed">
                        Pantau performa makro bisnis. Terima rekomendasi keputusan otomatis dari sistem cerdas mengenai siapa developer yang layak mendapatkan bonus atau kenaikan gaji tetap.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Intelligent System Section (Menjelaskan Algoritma Cerdas UAS) -->
    <section class="max-w-7xl mx-auto px-6 py-24">
        <div class="bg-gradient-to-br from-[#1b1b18] to-[#2c2c27] text-white rounded-3xl p-8 md:p-16 flex flex-col lg:flex-row items-center justify-between gap-12 shadow-xl">
            <div class="max-w-xl">
                <span class="text-xs font-bold uppercase tracking-widest text-[#780000] bg-white px-3 py-1 rounded-full">Intelligent Algorithm</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-4 leading-tight">Penilaian Performa Objektif dengan Sistem Aturan Berbobot</h2>
                <p class="mt-4 text-gray-300 text-sm md:text-base leading-relaxed">
                    AnoReward mengeliminasi bias subjektif dalam pemberian bonus karyawan. Algoritma kami secara otomatis mengkalkulasi skor gabungan dari <span class="text-white font-semibold">Kualitas Kode (Bobot 60%)</span> dan <span class="text-white font-semibold">Ketepatan Waktu (Bobot 40%)</span> di setiap periode proyek.
                </p>
            </div>
            <div class="w-full lg:w-auto bg-white/5 border border-white/10 backdrop-blur p-6 rounded-2xl class-step">
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-[#780000] text-xs flex items-center justify-center font-bold shrink-0 mt-0.5">1</span>
                        <p class="text-xs text-gray-200"><strong class="text-white">Developer</strong> menyelesaikan tugas tepat waktu.</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-[#780000] text-xs flex items-center justify-center font-bold shrink-0 mt-0.5">2</span>
                        <p class="text-xs text-gray-200"><strong class="text-white">Manager</strong> mengunci nilai kualitas review tugas.</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-[#780000] text-xs flex items-center justify-center font-bold shrink-0 mt-0.5">3</span>
                        <p class="text-xs text-gray-200"><strong class="text-white">Sistem</strong> memicu usulan penyesuaian insentif ke Direktur.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-gray-100 bg-white py-8 text-center text-xs text-[#4a4a4a]">
        <p>&copy; {{ date('Y') }} AnoReward Project. All rights reserved. Dibuat untuk pemenuhan Tugas UAS Pengembangan Aplikasi Web.</p>
    </footer>

</body>
</html>