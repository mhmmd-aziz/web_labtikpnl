<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Laboratorium TIK PNL</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* Auto-scroll Logic CSS */
        .scroll-container {
            width: 100%;
            overflow: hidden;
        }
        .scroll-content {
            display: flex;
            width: fit-content;
        }
        .scroll-content table {
            flex-shrink: 0;
        }

        /*  Back Button CSS */
        .back-btn {
            display: inline-flex; align-items: center; padding: 15px 25px; border: none; border-radius: 15px; color: #ffffffff; z-index: 1; background: #ff0000ff; position: relative; font-weight: 1000; font-size: 17px; text-decoration: none; -webkit-box-shadow: 4px 8px 19px -3px rgba(0, 0, 0, 0.27); box-shadow: 4px 8px 19px -3px rgba(0, 0, 0, 0.27); transition: all 250ms; overflow: hidden;
        }
        .back-btn::before {
            content: ""; position: absolute; top: 0; left: 0; height: 100%; width: 0; border-radius: 15px; background-color: #9d0707ff; z-index: -1; -webkit-box-shadow: 4px 8px 19px -3px rgba(0, 0, 0, 0.27); box-shadow: 4px 8px 19px -3px rgba(0, 0, 0, 0.27); transition: all 250ms;
        }
        .back-btn:hover { color: #ffffffff; }
        .back-btn:hover::before { width: 100%; }

        /* Styles & Animasi */
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            min-height: 100vh;
        }
        .glass-effect {
            background: rgba(30, 41, 59, 0.3);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(148, 163, 184, 0.1);
        }
        .neon-glow {
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.3), 0 0 40px rgba(59, 130, 246, 0.1);
        }
        .floating-card {
            transform: translateY(0);
            transition: all 0.3s ease;
        }
        .floating-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
        }
        .gradient-text {
            background: linear-gradient(135deg, #60a5fa 0%, #a78bfa 50%, #f472b6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .pulse-animation { animation: pulse 2s infinite; }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        /* Table Styles */
        .modern-table {
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .table-header {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            position: relative;
        }
        .table-header::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899);
        }
        .schedule-card {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(147, 51, 234, 0.1) 100%);
            border: 1px solid rgba(59, 130, 246, 0.2);
            backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        .schedule-card:hover {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.2) 0%, rgba(147, 51, 234, 0.2) 100%);
            border-color: rgba(59, 130, 246, 0.4);
            transform: scale(1.02);
        }
        .status-indicator {
            width: 8px; height: 8px; border-radius: 50%; display: inline-block;
            background: #10b981;
            box-shadow: 0 0 10px rgba(16, 185, 129, 0.5);
        }

        /* button full layar */
        .fullscreen-btn {
            position: absolute; top: 1rem; right: 1rem;
            background: #374151; color: white; border: none; border-radius: 0.5rem;
            padding: 0.75rem; cursor: pointer; transition: all 0.3s ease; z-index: 20;
        }
        .fullscreen-btn:hover {
            background: #4B5563;
            transform: scale(1.05);
        }
        body.fullscreen-active #page-header,
        body.fullscreen-active #page-footer {
            display: none;
        }
        body.fullscreen-active #schedule-table-container {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 999;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            overflow: auto; padding: 1rem;
        }
    </style>
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
</head>
<body class="p-4 sm:p-8">

    <section id="jadwal-hari-ini" class="w-full relative z-10">
        <div class="container mx-auto">

            <div id="page-header" class="text-center mb-12 px-4">
                <div class="glass-effect rounded-3xl p-8 floating-card neon-glow">
                    <div class="flex items-center justify-center mb-6">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center mr-4 shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-4xl md:text-5xl font-bold gradient-text mb-2">
                                Jadwal Laboratorium Hari Ini
                            </h2>
                            <div class="text-xl text-blue-300 font-medium">TIK Politeknik Negeri Lhokseumawe</div>
                        </div>
                    </div>
                    <div class="inline-flex items-center px-6 py-3 rounded-full bg-gradient-to-r from-blue-600/20 to-purple-600/20 border border-blue-400/30">
                        <div class="status-indicator pulse-animation mr-3"></div>
                        <span class="text-xl text-white font-semibold">
                            {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                        </span>
                    </div>
                </div>
            </div>

            @if($semuaJam && !$semuaRuang->isEmpty())
                <div id="schedule-table-container" class="relative">
                    <button id="fullscreen-btn" class="fullscreen-btn" title="Toggle Fullscreen">
                        <svg id="expand-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 3H5C3.89543 3 3 3.89543 3 5V8M21 8V5C21 3.89543 20.1046 3 19 3H16M16 21H19C20.1046 21 21 20.1046 21 19V16M3 16V19C3 20.1046 3.89543 21 5 21H8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <svg id="compress-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: none;">
                            <path d="M8 3V6C8 7.10457 7.10457 8 6 8H3M21 3V6C21 7.10457 20.1046 8 19 8H16M16 21V18C16 16.8954 16.8954 16 18 16H21M3 21V18C3 16.8954 3.89543 16 5 16H8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>

                    <div class="scroll-container modern-table">
                        <div class="scroll-content">
                            <table class="min-w-full divide-y divide-gray-700 border-separate" style="border-spacing: 0;">
                                <thead class="table-header">
                                    <tr>
                                        <th scope="col" class="px-8 py-6 text-left text-sm font-bold text-blue-200 uppercase tracking-wider sticky left-0 bg-gradient-to-r from-slate-800 to-slate-700 z-10">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Waktu
                                            </div>
                                        </th>
                                        @foreach($semuaRuang as $ruang)
                                            <th scope="col" class="px-8 py-6 text-left text-sm font-bold text-blue-200 uppercase tracking-wider">
                                                <div class="flex items-center">
                                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                    {{ $ruang->nama_ruang }}
                                                </div>
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="bg-gradient-to-b from-slate-800/50 to-slate-900/50">
                                    @foreach($semuaJam as $index => $jam)
                                        <tr class="divide-x divide-gray-700 hover:bg-slate-700/30 transition-all duration-300">
                                            <td class="px-8 py-4 whitespace-nowrap text-sm font-medium text-white sticky left-0 bg-gradient-to-r from-slate-800/95 to-slate-700/90 z-10">
                                                <div class="bg-gradient-to-r from-slate-700 to-slate-600 border-2 border-blue-400/30 rounded-xl px-4 py-3 text-center">
                                                    <div class="text-lg font-bold text-white">{{ \Carbon\Carbon::parse($jam->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jam->jam_selesai)->format('H:i') }}</div>
                                                </div>
                                            </td>
                                            @foreach($semuaRuang as $ruang)
                                                @php
                                                    $currentCell = $scheduleData[$index][$ruang->id] ?? null;
                                                @endphp
                                                @if ($currentCell !== 'occupied')
                                                    <td class="p-2 relative" @if(is_array($currentCell) && $currentCell['span'] > 1) rowspan="{{ $currentCell['span'] }}" @endif >
                                                        @if(is_array($currentCell))
                                                            @php $jadwalDitemukan = $currentCell['data']; @endphp
                                                            <div class="schedule-card absolute inset-0 m-2 rounded-xl p-4 flex flex-col justify-center">
                                                                <div class="flex items-start justify-between mb-2">
                                                                    <div class="status-indicator"></div>
                                                                    <div class="text-xs px-2 py-1 rounded-full bg-blue-500/20 text-blue-300 border border-blue-400/30 font-semibold">
                                                                        {{ $jadwalDitemukan->kelas->nama_kelas ?? 'N/A' }}
                                                                    </div>
                                                                </div>
                                                                <div class="font-bold text-white text-base mb-2">{{ $jadwalDitemukan->mata_kuliah }}</div>
                                                                <div class="text-sm text-slate-300 mb-2">
                                                                    <svg class="w-4 h-4 inline mr-1.5 align-middle" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                                    {{ $jadwalDitemukan->dosen->name ?? 'N/A' }}
                                                                </div>
                                                                
                                                                <div class="text-sm text-slate-300 font-medium">
                                                                    <svg class="w-4 h-4 inline mr-1.5 align-middle" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                                    {{ \Carbon\Carbon::parse($jadwalDitemukan->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwalDitemukan->jam_selesai)->format('H:i') }}
                                                                </div>
                                                                </div>
                                                        @else
                                                            <div class="absolute inset-0 m-2 flex items-center justify-center">
                                                                <span class="text-slate-600 text-sm font-medium">--</span>
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
                </div>
            @else
                <p class="text-center text-gray-400 text-xl glass-effect p-8 rounded-2xl">Jadwal untuk hari ini tidak tersedia.</p>
            @endif

            <div id="page-footer" class="text-center mt-12">
                <div class="glass-effect rounded-2xl p-4 floating-card inline-block">
                    <a href="/" class="back-btn">
                         <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali
                    </a>
                </div>
            </div>

        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            setupAutoScroll();
            setupInteractivity();
        });

        // auto scroll
        function setupAutoScroll() {
            const container = document.querySelector('.scroll-container');
            const scrollContent = document.querySelector('.scroll-content');
            if (!container || !scrollContent) return;
            const table = scrollContent.querySelector('table');
            if (!table || table.offsetWidth <= container.offsetWidth) return;
            const tableClone = table.cloneNode(true);
            scrollContent.appendChild(tableClone);
            const tableWidth = table.offsetWidth;
            let currentPosition = 0;
            let isPaused = false;
            const scrollSpeed = 0.5;
            function animate() {
                if (!isPaused) {
                    currentPosition -= scrollSpeed;
                    if (Math.abs(currentPosition) >= tableWidth) {
                        currentPosition %= tableWidth;
                    }
                    scrollContent.style.transform = `translateX(${currentPosition}px)`;
                }
                requestAnimationFrame(animate);
            }
            container.addEventListener('mouseenter', () => { isPaused = true; });
            container.addEventListener('mouseleave', () => { isPaused = false; });
            container.addEventListener('wheel', (event) => {
                if (isPaused) {
                    event.preventDefault();
                    currentPosition -= (event.deltaX + event.deltaY);
                    if (currentPosition > 0) {
                        currentPosition = -tableWidth + (currentPosition % tableWidth);
                    } else if (Math.abs(currentPosition) >= tableWidth) {
                        currentPosition %= tableWidth;
                    }
                    scrollContent.style.transform = `translateX(${currentPosition}px)`;
                }
            });
            animate();
        }

        // full layar
        function setupInteractivity() {
            const fullscreenBtn = document.getElementById('fullscreen-btn');
            const body = document.body;
            const expandIcon = document.getElementById('expand-icon');
            const compressIcon = document.getElementById('compress-icon');

            const toggleState = () => {
                body.classList.toggle('fullscreen-active');
                const isActive = body.classList.contains('fullscreen-active');
                expandIcon.style.display = isActive ? 'none' : 'block';
                compressIcon.style.display = isActive ? 'block' : 'none';
            };

            if (fullscreenBtn) {
                fullscreenBtn.addEventListener('click', toggleState);
            }

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && body.classList.contains('fullscreen-active')) {
                    toggleState();
                }
            });
        }
    </script>
</body>
</html>