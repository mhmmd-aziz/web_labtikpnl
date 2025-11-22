<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LabTIK-PNL - Sistem Manajemen Laboratorium Jurusan TIK PNL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    animation: {
                        'fade-in-up': 'fadeInUp 0.6s ease-out',
                        'fade-in-down': 'fadeInDown 0.6s ease-out',
                        'bounce-slow': 'bounce 2s infinite',
                        'pulse-slow': 'pulse 3s infinite'
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {

                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .gradient-bg {
            /* * KOMENTAR: INI BAGIAN UTAMA YANG DIUBAH.
             * Kita menggunakan 'background-image' untuk menumpuk beberapa lapisan background.
             * Lapisan pertama adalah gradien biru semi-transparan. Kita pakai 'rgba' 
             * untuk mengatur warna dan opacity (angka terakhir, misal 0.85).
             * Semakin kecil angkanya (mendekati 0), semakin transparan warnanya dan semakin jelas fotonya.
             * * Lapisan kedua adalah gambar Anda (url('...')).
             */
            background-image: 
               /* linear-gradient(135deg, #9112BC, #E9E294), */
                linear-gradient(135deg, rgba(0, 0, 0, 0.8), #9112bcdd), 
                url("{{ asset('images/gedungtik.jpg') }}"); /* <-- GANTI DENGAN URL/PATH FOTO GEDUNG ANDA (misal: 'gedung-pnl.jpg') */
            
            /* * KOMENTAR: Properti tambahan untuk memastikan gambar ditampilkan dengan baik.
             */
            background-size: cover;       /* Membuat gambar menutupi seluruh area section */
            background-position: center;  /* Memposisikan gambar di tengah */
            background-repeat: no-repeat; /* Mencegah gambar berulang-ulang */
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        /* [GANTI CSS LAMA ANDA DENGAN INI] */

.scroll-container {
    width: 100%;
    overflow: hidden;
    background-color: #1F2937; /* Warna dark mode */
    padding: 1rem 0;
    border-radius: 1rem;
}

/* Mengatur warna background untuk light mode */
html.light .scroll-container {
    background-color: white;
}

/* Wrapper yang akan kita gerakkan dengan JavaScript */
.scroll-content {
    display: flex; /* Menggunakan flexbox agar tabel pasti berjajar */
    width: fit-content; /* Lebar sesuai isi */
}

.scroll-content table {
    flex-shrink: 0; /* Mencegah tabel menyusut */
}

.back-btn {
  display: inline-block;
  padding: 15px 25px;
  border: none;
  border-radius: 15px;
  color: #ffffffff;
  z-index: 50;
  background: #8d06bfff;
  position: relative;
  font-weight: 1000;
  font-size: 17px;
  text-decoration: none;
  -webkit-box-shadow: 4px 8px 19px -3px rgba(0, 0, 0, 0.27);
  box-shadow: 4px 8px 19px -3px rgba(0, 0, 0, 0.27);
  transition: all 250ms;
  overflow: hidden;
}

.back-btn::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  width: 0;
  border-radius: 15px;
  background-color: #760481ff;
  z-index: -1;
  -webkit-box-shadow: 4px 8px 19px -3px rgba(0, 0, 0, 0.27);
  box-shadow: 4px 8px 19px -3px rgba(0, 0, 0, 0.27);
  transition: all 250ms;
}

.back-btn:hover {
  color: #e8e8e8;
}

.back-btn:hover::before {
  width: 100%;
}

/*laoding*/




    </style>
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <!-- Navigation -->
    <nav class="bg-white dark:bg-gray-900 shadow-lg fixed w-full top-0 z-50 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center">

                                <img src="images/tik.png" alt="tik" class="w-6 h-6 rounded-full mr-2">
                            </div>
                            <span class="ml-2 text-xl font-bold text-gray-800 dark:text-white">LabTIK-PNL</span>
                        </div>
                    </div>
                </div>
                
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="#home" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 px-3 py-2 rounded-md text-sm font-medium transition-colors">Beranda</a>
                        <a href="#jadwal-hari-ini" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 block px-3 py-2 rounded-md text-base font-medium">Jadwal</a>
                        <a href="#about" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 px-3 py-2 rounded-md text-sm font-medium transition-colors">Tentang</a>
                        <a href="#prodi" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 px-3 py-2 rounded-md text-sm font-medium transition-colors">Program Studi</a>
                        <a href="#features" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 px-3 py-2 rounded-md text-sm font-medium transition-colors">Fitur</a>
                        <a href="#contact" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 px-3 py-2 rounded-md text-sm font-medium transition-colors">Kontak</a>
                    </div>
                </div>

            


                
                <div class="hidden md:block">
                    <div class="flex space-x-3">
                        <button onclick="toggleDarkMode()" class="w-10 h-10 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-300 flex items-center justify-center">
                            <span id="theme-icon">🌙</span>
                        </button>
                        <button onclick="redirectToLogin()" class="bg-gradient-to-r from-yellow-600 to-purple-500 text-white px-6 py-2 rounded-lg hover:from-yellow-600 hover:to-yellow-600 transition-all duration-300 transform hover:scale-105">
                            Login
                        </button>
                       <a href="#contact" class="border-2 border-purple-600 text-blue-600 dark:border-purple-400 dark:text-purple-400 px-6 py-2 rounded-lg hover:bg-blue-600 hover:text-white dark:hover:bg-yellow-600 dark:hover:text-white transition-all duration-300">Hubungi Kami</a>
                    </div>
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center space-x-2">
                    <button onclick="toggleDarkMode()" class="w-8 h-8 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-300 flex items-center justify-center">
                        <span id="theme-icon-mobile" class="text-sm">🌙</span>
                    </button>
                    <button onclick="toggleMobileMenu()" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div id="mobile-menu" class="md:hidden hidden bg-white dark:bg-gray-900 border-t dark:border-gray-700 transition-colors duration-300">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="#home" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 block px-3 py-2 rounded-md text-base font-medium">Beranda</a>
                <a href="#jadwal-hari-ini" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 block px-3 py-2 rounded-md text-base font-medium">Jadwal</a>
                <a href="#about" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 block px-3 py-2 rounded-md text-base font-medium">Tentang</a>
                <a href="#prodi" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 block px-3 py-2 rounded-md text-base font-medium">Program Studi</a>
                <a href="#features" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 block px-3 py-2 rounded-md text-base font-medium">Fitur</a>
                <a href="#contact" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 block px-3 py-2 rounded-md text-base font-medium">Kontak</a>
                <button onclick="redirectToLogin()" class="w-full text-left bg-gradient-to-r from-yellow-600 to-purple-500 text-white px-3 py-2 rounded-md text-base font-medium">
                     Login
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="gradient-bg pt-20 pb-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="text-center animate-fade-in-down">
                <div class="mb-6">
            <div class="inline-flex items-center bg-white/10 backdrop-blur-sm rounded-full px-4 py-2 mb-4">
                <img src="images/logopnl.png" alt="Politeknik Negeri Lhokseumawe" class="w-6 h-6 rounded-full mr-2">
                <span class="text-white/90 text-sm font-medium mr-4">Politeknik Negeri Lhokseumawe</span>

                <img src="images/tik.png" alt="tik" class="w-6 h-6 rounded-full mr-2">
                <span class="text-white/90 text-sm font-medium">Teknologi Informasi & Komputer</span>
            </div>
                </div>
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                    Selamat Datang di<br>
                    <span class="bg-gradient-to-r from-yellow-300 to-orange-400 bg-clip-text text-transparent">LabTIK-PNL</span>
                </h1>
                <p class="text-xl md:text-2xl text-blue-100 mb-8 max-w-3xl mx-auto">
                    Sistem Manajemen Laboratorium Digital untuk Jurusan Teknologi Informasi dan Komputer, Politeknik Negeri Lhokseumawe. Solusi modern untuk pengelolaan fasilitas akademik.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <button onclick="redirectToLogin()" class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        Login Sistem
                    </button>
                   <a href="#about"class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-all duration-300">Tentang Kami</a>
                  <a href="#features" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-all duration-300">Fitur Unggulan</a>
                </div>
            </div>
            
            <!-- Hero Image/Demo Preview -->
            <div class="mt-16 animate-fade-in-up">
                <div class="bg-white rounded-2xl shadow-2xl p-4 max-w-4xl mx-auto">
                    <div class="bg-gray-100 rounded-xl p-8 text-center">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-white p-6 rounded-lg shadow-md">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                    <span class="text-2xl">🏢</span>
                                </div>
                                <h3 class="font-semibold text-gray-800 mb-2">Manajemen Ruangan</h3>
                                <p class="text-gray-600 text-sm">Kelola ruang Laboratorium</p>
                            </div>
                            <div class="bg-white p-6 rounded-lg shadow-md">
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                    <span class="text-2xl">📅</span>
                                </div>
                                <h3 class="font-semibold text-gray-800 mb-2">Penjadwalan</h3>
                                <p class="text-gray-600 text-sm">Atur jadwal Laboratorium</p>
                            </div>
                            <div class="bg-white p-6 rounded-lg shadow-md">
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                    <span class="text-2xl">📊</span>
                                </div>
                                <h3 class="font-semibold text-gray-800 mb-2">Monitoring</h3>
                                <p class="text-gray-600 text-sm">Monitoring Jadwal Laboratorium Secara Real Time</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    <!-- jadwal  -->
    
<section id="jadwal-hari-ini" class="py-20 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 animate-fade-in-up">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-white mb-4">
                Jadwal Laboratorium Hari Ini
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-300">
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </p>
            </div>

        @if($semuaJam && !$semuaRuang->isEmpty())
            <div class="scroll-container">
                <div class="scroll-content">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 border-separate" style="border-spacing: 0;">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sticky left-0 bg-gray-50 dark:bg-gray-700 z-10">
                                    Waktu
                                </th>
                                @foreach($semuaRuang as $ruang)
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ $ruang->nama_ruang }}
                                    </th>
                                @endforeach
                                </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800">
                            @foreach($semuaJam as $index => $jam)
                                <tr class="divide-x divide-gray-200 dark:divide-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white sticky left-0 bg-white dark:bg-gray-800 z-10">
                                        {{ \Carbon\Carbon::parse($jam->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jam->jam_selesai)->format('H:i') }}
                                    </td>

                                    @foreach($semuaRuang as $ruang)
                                        @php
                                            $currentCell = $scheduleData[$index][$ruang->id] ?? null;
                                        @endphp

                                        @if ($currentCell !== 'occupied')
                                            <td class="p-1 relative"
                                                @if(is_array($currentCell) && $currentCell['span'] > 1) 
                                                    rowspan="{{ $currentCell['span'] }}"
                                                @endif
                                            >
                                                @if(is_array($currentCell))
                                                    @php 
                                                        $jadwalDitemukan = $currentCell['data']; 
                                                    @endphp
                                                    <div class="absolute inset-0 bg-green-100 dark:bg-green-900/50 p-3 rounded-lg text-center flex flex-col justify-center m-1">
                                                        <p class="font-bold text-green-800 dark:text-green-200">{{ $jadwalDitemukan->mata_kuliah }}</p>
                                                        <p class="text-xs text-green-600 dark:text-green-300">{{ $jadwalDitemukan->dosen->name ?? 'N/A' }}</p>
                                                        <p class="text-xs text-green-500 dark:text-green-400">Kelas: {{ $jadwalDitemukan->kelas->nama_kelas ?? 'N/A' }}</p>
                                                        <p class="mt-2 text-xs font-semibold text-gray-700 dark:text-gray-200">
                                                            {{ \Carbon\Carbon::parse($jadwalDitemukan->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwalDitemukan->jam_selesai)->format('H:i') }}
                                                        </p>
                                                    </div>
                                                @else
                                                    <div class="bg-red-900/80 dark:bg-red-900/30 p-3 rounded-lg text-center h-full">
                                                        <p class="font-semibold text-red-200 dark:text-red-300">-- Kosong --</p>
                                                    </div>
                                                @endif
                                            </td>
                                        @endif
                                    @endforeach
                                    </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-12 flex justify-center">
                <a href="/jadwal-laboratorium" class="back-btn">
                  Lihat Jadwal Lengkap
                </a>
            </div>
            @else
            <p class="text-center text-gray-500 dark:text-gray-400">Jadwal untuk hari ini tidak tersedia.</p>
        @endif
    </div>
</section>

    <!-- Program Studi Section -->
    <section id="prodi" class="py-20 px-4 sm:px-6 lg:px-8 bg-white dark:bg-gray-800 transition-colors duration-300">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-white mb-4">Program Studi</h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    Tiga program studi unggulan di Jurusan Teknologi Informasi dan Komputer
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="card-hover bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 p-8 rounded-xl shadow-lg border border-blue-200 dark:border-blue-700">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mb-6">
                        <span class="text-3xl">💻</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Teknik Informatika</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">Program studi yang fokus pada pengembangan software, algoritma, dan sistem informasi.</p>
                    <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                        <li>• Pemrograman & Software Engineering</li>
                        <li>• Database & Sistem Informasi</li>
                        <li>• Artificial Intelligence</li>
                        <li>• Mobile & Web Development</li>
                        <li>• Cloud Computing</li>
                    </ul>
                </div>
                
                <div class="card-hover bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 p-8 rounded-xl shadow-lg border border-green-200 dark:border-green-700">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center mb-6">
                        <span class="text-3xl">🌐</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Teknologi Rekayasa Komputer Jaringan</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">Spesialisasi dalam infrastruktur jaringan, keamanan siber, dan sistem terdistribusi.</p>
                    <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                        <li>• Network Infrastructure</li>
                        <li>• Cybersecurity</li>
                        <li>• IoT & Embedded Systems</li>
                    </ul>
                </div>
                
                <div class="card-hover bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 p-8 rounded-xl shadow-lg border border-purple-200 dark:border-purple-700">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mb-6">
                        <span class="text-3xl">🎨</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Teknologi Rekayasa Multimedia</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">Kombinasi teknologi dan seni untuk menciptakan konten multimedia interaktif.</p>
                    <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                        <li>• Digital Design & Animation</li>
                        <li>• Game Development</li>
                        <li>• Video Production</li>
                        <li>• Design UI UX </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-white mb-4">Fitur LabTIK-PNL</h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    Sistem manajemen lengkap untuk efisiensi operasional Jurusan TIK
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="card-hover bg-white dark:bg-gray-800 p-8 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center mb-6">
                        <span class="text-3xl">🏢</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Manajemen Ruangan</h3>
                    <p class="text-gray-600 dark:text-gray-300">Kelola ruang laboratorium.</p>
                </div>
                
                <div class="card-hover bg-white dark:bg-gray-800 p-8 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center mb-6">
                        <span class="text-3xl">📅</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Penjadwalan</h3>
                    <p class="text-gray-600 dark:text-gray-300">Sistem penjadwalan kuliah dengan gampang.</p>
                </div>
                
                <div class="card-hover bg-white dark:bg-gray-800 p-8 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mb-6">
                        <span class="text-3xl">👥</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Manajemen penjadwalan Dosen & Mahasiswa</h3>
                    <p class="text-gray-600 dark:text-gray-300">Database lengkap dosen dan mahasiswa dengan Roster, Ruang Dan Jam Mengajar.</p>
                </div>
                
                <div class="card-hover bg-white dark:bg-gray-800 p-8 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
                    <div class="w-16 h-16 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center mb-6">
                        <span class="text-3xl">🗓</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Monitoring Jadwal Laboratorium Secara Real Time</h3>
                    <p class="text-gray-600 dark:text-gray-300">Melihat jadwal secara real time sesuai hari </p>
                </div>
                
                <div class="card-hover bg-white dark:bg-gray-800 p-8 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
                    <div class="w-16 h-16 bg-gradient-to-r from-red-500 to-rose-500 rounded-xl flex items-center justify-center mb-6">
                        <span class="text-3xl">🗓</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Jadwal Roster Dosen & Mahasiswa</h3>
                    <p class="text-gray-600 dark:text-gray-300">Sistem Jadwal Dosen dan Mahasiswa</p>
                </div>
                
                <div class="card-hover bg-white dark:bg-gray-800 p-8 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-xl flex items-center justify-center mb-6">
                        <span class="text-3xl">📱</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Mobile Responsive</h3>
                    <p class="text-gray-600 dark:text-gray-300">Akses sistem dari mana saja dengan tampilan yang optimal di desktop, tablet, dan smartphone.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 px-4 sm:px-6 lg:px-8 bg-white dark:bg-gray-800 transition-colors duration-300">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-white mb-4">Tentang LabTIK-PNL</h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    Sistem manajemen ruangan digital yang dirancang khusus untuk kebutuhan Jurusan TIK PNL
                </p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-16">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Visi & Misi</h3>
                    <div class="space-y-6">
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-lg border dark:border-blue-800">
                            <h4 class="font-semibold text-blue-800 dark:text-blue-300 mb-3">🎯 Visi</h4>
                            <p class="text-gray-700 dark:text-gray-300">Menjadi sistem manajemen ruangan terdepan yang mendukung efisiensi dan inovasi dalam pengelolaan fasilitas akademik Jurusan TIK.</p>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900/20 p-6 rounded-lg border dark:border-green-800">
                            <h4 class="font-semibold text-green-800 dark:text-green-300 mb-3">🚀 Misi</h4>
                            <p class="text-gray-700 dark:text-gray-300">Menyediakan solusi digital yang mudah digunakan, terintegrasi, dan dapat diandalkan untuk optimalisasi penggunaan ruangan dan sumber daya akademik.</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 rounded-2xl p-8 border dark:border-gray-700">
                    <div class="text-center mb-6">
                        <div class="w-24 h-24 bg-gradient-to-r from-yellow-600 to-purple-500 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <span class="text-white text-3xl font-bold">L-PNL</span>
                        </div>
                        <h4 class="text-xl font-semibold text-gray-800 dark:text-white">Jurusan TIK</h4>
                        <p class="text-gray-600 dark:text-gray-300">Politeknik Negeri Lhokseumawe</p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">3</div>
                            <div class="text-sm text-gray-600 dark:text-gray-300">Program Studi</div>
                        </div>
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">44</div>
                            <div class="text-sm text-gray-600 dark:text-gray-300">Ruangan</div>
                        </div>
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">15+</div>
                            <div class="text-sm text-gray-600 dark:text-gray-300">Laboratorium</div>
                        </div>
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-orange-600">600+</div>
                            <div class="text-sm text-gray-600 dark:text-gray-300">Mahasiswa</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 dark:bg-gray-700 rounded-2xl p-8">
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-8 text-center">Mengapa LabTIK-PNL?</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-3xl">⚡</span>
                        </div>
                        <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Efisiensi Tinggi</h4>
                        <p class="text-gray-600 dark:text-gray-300 text-sm">Mengoptimalkan penggunaan ruangan dan mengurangi konflik jadwal</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-3xl">🎯</span>
                        </div>
                        <h4 class="font-semibold text-gray-800 dark:text-white mb-2">User-Friendly</h4>
                        <p class="text-gray-600 dark:text-gray-300 text-sm">Interface yang intuitif dan mudah digunakan oleh semua kalangan civitas akademika.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-3xl">🔒</span>
                        </div>
                        <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Aman & Terpercaya</h4>
                        <p class="text-gray-600 dark:text-gray-300 text-sm">Sistem keamanan berlapis untuk melindungi data dan privasi pengguna.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-white mb-4">Hubungi Kami</h2>
            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                Butuh bantuan atau informasi lebih lanjut tentang LabTIK-PNL?
            </p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 justify-items-center">
            <div>
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Jurusan TIK - PNL</h3>
                <div class="space-y-6">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                            <span class="text-2xl">🏛️</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800 dark:text-white">Alamat</h4>
                            <p class="text-gray-600 dark:text-gray-300">Politeknik Negeri Lhokseumawe<br>Jl. Banda Aceh - Medan Km. 280<br>Buketrata, Lhokseumawe, Aceh</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                            <span class="text-2xl">📞</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800 dark:text-white">Telepon</h4>
                            <p class="text-gray-600 dark:text-gray-300">(0645) 42785</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                            <span class="text-2xl">📧</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800 dark:text-white">Email</h4>
                            <p class="text-gray-600 dark:text-gray-300">tik@pnl.ac.id</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                            <span class="text-2xl">🕒</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800 dark:text-white">Jam Operasional</h4>
                            <p class="text-gray-600 dark:text-gray-300">Senin - Jumat: 08:00 - 16:00 WIB</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="tanya-ai" class="py-20 px-4 sm:px-6 lg:px-8 bg-white dark:bg-gray-800 transition-colors duration-300">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-white mb-4">
                Tanya Asisten AI LabTIK
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                Punya pertanyaan tentang jadwal, ruangan, atau fitur LabTIK-PNL? Tanyakan di sini!
            </p>
        </div>
        
        <div class="max-w-2xl mx-auto bg-gray-50 dark:bg-gray-900 p-8 rounded-xl shadow-lg border dark:border-gray-700">
            
            <form id="gemini-form" onsubmit="return false;"> @csrf <div class="mb-4">
                    <label for="prompt" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pertanyaan Anda:</label>
                    <input type="text" id="prompt" name="prompt"
                           placeholder="Contoh: Lab RPL hari ini dipakai jam berapa?"
                           class="w-full px-4 py-3 rounded-lg
                           bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600
                           text-gray-900 dark:text-white focus:outline-none focus:ring-2
                           focus:ring-purple-500 focus:border-transparent">
                </div>

                <div class="text-center">
                    <button type="button" id="btn-ask"
                        class="bg-gradient-to-r from-yellow-600 to-purple-500 text-white px-8 py-3
                               rounded-lg hover:scale-105 transition-all duration-300 font-semibold">
                        Tanya Sekarang
                    </button>
                </div>
            </form> 

            <div id="ai-response" class="mt-8 hidden p-6 rounded-lg bg-gray-50 dark:bg-gray-900 border dark:border-gray-700">
                <h4 class="font-semibold text-green-800 dark:text-green-300 mb-3">💡 Jawaban AI:</h4>
                <p id="response-text" class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap"></p> 
            </div>

            </div>
    </div>
</section>


<footer class="bg-gray-800 dark:bg-gray-950 text-white py-12 px-4 sm:px-6 lg:px-8 transition-colors duration-300">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <div class="col-span-1 md:col-span-2">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-yellow-600 to-purple-500 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-sm">L-PNL</span>
                </div>
                <span class="ml-2 text-xl font-bold">LabTIK-PNL</span>
            </div>
            <p class="text-gray-300 dark:text-gray-400 mb-4">
                Sistem Manajemen Jurusan Teknologi Informasi dan Komputer, Politeknik Negeri Lhokseumawe. Mengelola ruangan, jadwal, dan sumber daya akademik dengan efisien.
            </p>
            <div class="flex space-x-4">
                <button class="w-10 h-10 bg-gray-700 dark:bg-gray-800 rounded-lg flex items-center justify-center hover:bg-gray-600 dark:hover:bg-gray-700 transition-colors">
                    <span class="text-xl">🌐</span>
                </button>
                <button class="w-10 h-10 bg-gray-700 dark:bg-gray-800 rounded-lg flex items-center justify-center hover:bg-gray-600 dark:hover:bg-gray-700 transition-colors">
                    <span class="text-xl">📧</span>
                </button>
                <button class="w-10 h-10 bg-gray-700 dark:bg-gray-800 rounded-lg flex items-center justify-center hover:bg-gray-600 dark:hover:bg-gray-700 transition-colors">
                    <span class="text-xl">📱</span>
                </button>
            </div>
        </div>
        
        <div>
            <h4 class="text-lg font-semibold mb-4">Program Studi</h4>
            <ul class="space-y-2">
                <li><span class="text-gray-300 dark:text-gray-400">Teknik Informatika</span></li>
                <li><span class="text-gray-300 dark:text-gray-400">Teknologi Rekayasa Komputer Jaringan</span></li>
                <li><span class="text-gray-300 dark:text-gray-400">Teknologi Rekayasa Multimedia</span></li>
            </ul>
        </div>
        
        <div>
            <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
            <ul class="space-y-2">
                <li><a href="#home" class="text-gray-300 dark:text-gray-400 hover:text-white transition-colors">Beranda</a></li>
                <li><a href="#jadwal-hari-ini" class="text-gray-300 dark:text-gray-400 hover:text-white transition-colors">Jadwal</a></li>
                <li><a href="#features" class="text-gray-300 dark:text-gray-400 hover:text-white transition-colors">Fitur</a></li>
                <li><a href="#prodi" class="text-gray-300 dark:text-gray-400 hover:text-white transition-colors">Program Studi</a></li>
                <li><a href="#contact" class="text-gray-300 dark:text-gray-400 hover:text-white transition-colors">Kontak</a></li>
            </ul>
        </div>
    </div>
    
    <div class="border-t border-gray-700 dark:border-gray-800 mt-8 pt-8 text-center">
        <p class="text-gray-300 dark:text-gray-400">
            © 2024 LabTIK-PNL - Jurusan TIK, Politeknik Negeri Lhokseumawe. All rights reserved.
        </p>
    </div>
</footer>

   <script>
    // ▼▼▼ GANTI FUNGSI LAMA DENGAN VERSI BARU INI ▼▼▼
function setupAutoScroll() {
    const container = document.querySelector('.scroll-container');
    const scrollContent = document.querySelector('.scroll-content');

    if (!container || !scrollContent) return;

    const table = scrollContent.querySelector('table');
    if (!table || table.offsetWidth <= container.offsetWidth) return;
    
    // Duplikasi tabel untuk efek scroll tak terbatas
    const tableClone = table.cloneNode(true);
    scrollContent.appendChild(tableClone);

    const tableWidth = table.offsetWidth; // Simpan lebar tabel asli
    let currentPosition = 0;
    let isPaused = false;
    const scrollSpeed = 0.5; // Atur kecepatan otomatis di sini

    function animate() {
        if (!isPaused) {
            currentPosition -= scrollSpeed;
            // Reset posisi jika sudah melewati batas
            if (Math.abs(currentPosition) >= tableWidth) {
                currentPosition %= tableWidth;
            }
            scrollContent.style.transform = `translateX(${currentPosition}px)`;
        }
        // Terus jalankan loop animasi
        requestAnimationFrame(animate);
    }

    // Jeda auto-scroll saat mouse masuk
    container.addEventListener('mouseenter', () => { 
        isPaused = true; 
    });

    // Lanjutkan auto-scroll saat mouse keluar
    container.addEventListener('mouseleave', () => { 
        isPaused = false; 
    });

    // [BAGIAN BARU] Fungsionalitas scroll manual dengan mouse wheel
    container.addEventListener('wheel', (event) => {
        // Scroll manual hanya berfungsi saat auto-scroll dijeda (mouse di atas container)
        if (isPaused) {
            event.preventDefault(); // Mencegah halaman ikut scroll (atas/bawah)
            
            // Update posisi berdasarkan pergerakan mouse wheel/trackpad
            currentPosition -= (event.deltaX + event.deltaY);

            // Logika agar scroll tetap berputar (tidak ada ujung)
            // Jika scroll ke kiri melewati batas awal
            if (currentPosition > 0) {
                currentPosition = -tableWidth + (currentPosition % tableWidth);
            } 
            // Jika scroll ke kanan melewati batas akhir
            else if (Math.abs(currentPosition) >= tableWidth) {
                currentPosition %= tableWidth;
            }
            
            // Terapkan posisi baru secara langsung
            scrollContent.style.transform = `translateX(${currentPosition}px)`;
        }
    });

    // Mulai animasi
    animate();
}
// ▲▲▲ AKHIR DARI FUNGSI YANG DIPERBAIKI ▲▲▲

    // Dark mode functionality
    function toggleDarkMode() {
        const html = document.documentElement;
        const themeIcon = document.getElementById('theme-icon');
        const themeIconMobile = document.getElementById('theme-icon-mobile');
        
        if (html.classList.contains('dark')) {
            html.classList.remove('dark');
            themeIcon.textContent = '🌙';
            themeIconMobile.textContent = '🌙';
            localStorage.setItem('theme', 'light');
        } else {
            html.classList.add('dark');
            themeIcon.textContent = '☀️';
            themeIconMobile.textContent = '☀️';
            localStorage.setItem('theme', 'dark');
        }
    }

    // Initialize theme on page load
    function initializeTheme() {
        const savedTheme = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const themeIcon = document.getElementById('theme-icon');
        const themeIconMobile = document.getElementById('theme-icon-mobile');
        
        if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
            document.documentElement.classList.add('dark');
            themeIcon.textContent = '☀️';
            themeIconMobile.textContent = '☀️';
        } else {
            themeIcon.textContent = '🌙';
            themeIconMobile.textContent = '🌙';
        }
    }

    // Login redirect function
    function redirectToLogin() {
        window.location.href = "{{ route('filament.admin.auth.login') }}";
    }

    function handleContactForm(event) {
        event.preventDefault();
        const button = event.target.querySelector('button[type="submit"]');
        const originalText = button.innerHTML;
        
        button.innerHTML = ' Mengirim...';
        button.disabled = true;
        
        setTimeout(() => {
            alert('✅ Pesan Terkirim');
            event.target.reset();
            button.innerHTML = originalText;
            button.disabled = false;
        }, 2000);
    }

    // Smooth scroll for all navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
            
            const mobileMenu = document.getElementById('mobile-menu');
            if (!mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.add('hidden');
            }
        });
    });

    // Add scroll effect to navbar
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('nav');
        if (window.scrollY > 50) {
            navbar.classList.add('shadow-xl');
        } else {
            navbar.classList.remove('shadow-xl');
        }
    });

    // Animate elements on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in-up');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.card-hover, section > div').forEach(el => {
        observer.observe(el);
    });

 document.addEventListener('DOMContentLoaded', () => {
        // 1. Jalankan fungsi inisialisasi Anda
        initializeTheme();
        setupAutoScroll();

        // 2. Temukan elemen-elemen AI
        const aiButton = document.getElementById('btn-ask');
        const promptInput = document.getElementById('prompt');
        const responseContainer = document.getElementById('ai-response');
        const responseText = document.getElementById('response-text');
        
        // 3. Ambil CSRF Token dari <meta> tag di <head> Anda
        const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
        if (!csrfTokenElement) {
            console.error('FATAL: CSRF Token Meta Tag not found! Pastikan ada <meta name="csrf-token" content="{{ csrf_token() }}"> di <head> Anda.');
            return;
        }
        const csrfToken = csrfTokenElement.content;

        // 4. Tambahkan event listener ke tombol 'btn-ask'
        if (aiButton) {
            aiButton.addEventListener('click', async () => {
                
                const prompt = promptInput.value;
                if (!prompt) {
                    alert('Harap masukkan pertanyaan terlebih dahulu.');
                    return;
                }

                // Tampilkan pesan "Loading"
                responseContainer.classList.remove('hidden');
                responseText.innerText = "⏳ Memproses...";
                aiButton.disabled = true;
                aiButton.innerText = "Loading...";

                try {
                    const res = await fetch("{{ route('gemini.ask') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-CSRF-TOKEN": csrfToken // <-- INI YANG PALING PENTING
                        },
                        body: JSON.stringify({ prompt: prompt }),
                    });

                    const data = await res.json();

                    if (!res.ok) {
                        // Tampilkan error jika server gagal (404, 500, 422, dll)
                        responseText.innerText = "❌ Gagal: " + (data.error || 'Terjadi kesalahan server.');
                    } else {
                        // Tampilkan jawaban AI
                        // Pastikan controller Anda mengirim 'response'
                        responseText.innerText = data.response ?? "(Tidak ada jawaban teks)"; 
                    }

                } catch (err) {
                    // Tampilkan error jika fetch (jaringan) gagal
                    console.error('Fetch Error:', err);
                    responseText.innerText = "⚠️ Error: " + err.message;
                } finally {
                    // Hidupkan tombol kembali
                    aiButton.disabled = false; 
                    aiButton.innerText = "Tanya Sekarang";
                }
            });
        }
    });
</script></body>
</html>
