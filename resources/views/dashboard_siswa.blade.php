<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Skrining | Counselor Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }

        /* Sidebar overlay mobile */
        #sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 40;
        }
        #sidebar-overlay.open { display: block; }

        /* Sidebar drawer */
        #sidebar {
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            width: 256px;
            background: white;
            border-right: 1px solid #f1f5f9;
            display: flex;
            flex-direction: column;
            padding: 2rem 1rem;
            z-index: 50;
            transform: translateX(-100%);
            transition: transform 0.3s cubic-bezier(.4,0,.2,1);
            box-shadow: 2px 0 12px rgba(0,0,0,0.06);
        }
        #sidebar.open { transform: translateX(0); }

        @media (min-width: 768px) {
            #sidebar {
                position: sticky;
                top: 0;
                transform: translateX(0) !important;
                height: 100vh;
                box-shadow: none;
            }
            #sidebar-overlay { display: none !important; }
            #hamburger-btn { display: none !important; }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 flex min-h-screen">

{{-- OVERLAY --}}
<div id="sidebar-overlay" onclick="closeSidebar()"></div>

{{-- SIDEBAR --}}
<aside id="sidebar" class="w-64 flex-col py-8 px-4 shadow-sm">
    <div class="px-3 mb-10">
        <div class="flex items-center gap-2 mb-1">
            <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-white" style="font-size:18px">psychology</span>
            </div>
            <h1 class="text-base font-bold text-slate-800">Counselor Portal</h1>
        </div>
        <p class="text-xs text-slate-400 ml-10">SMPN 216 Jakarta</p>
    </div>

    <nav class="flex-1 space-y-1">
        <a href="/dashboard-siswa" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-blue-600 font-semibold bg-blue-50 border-r-4 border-blue-600 text-sm">
            <span class="material-symbols-outlined" style="font-size:20px">clinical_notes</span>
            Hasil Skrining Saya
        </a>
        <a href="/sdq" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-500 hover:text-blue-600 hover:bg-slate-50 transition-colors text-sm">
            <span class="material-symbols-outlined" style="font-size:20px">assignment</span>
            Isi Kuesioner SDQ
        </a>
    </nav>

    <div class="mt-6 p-3 bg-slate-50 rounded-xl flex items-center gap-3">
        <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
        </div>
        <div class="overflow-hidden">
            <p class="text-sm font-semibold truncate text-slate-800">{{ Auth::user()->name }}</p>
            <p class="text-xs text-slate-400 truncate">{{ Auth::user()->kelas }}</p>
        </div>
        <form method="POST" action="/logout" class="ml-auto">
            @csrf
            <button type="submit" title="Logout">
                <span class="material-symbols-outlined text-slate-400 hover:text-red-500 transition-colors" style="font-size:18px">logout</span>
            </button>
        </form>
    </div>
</aside>

{{-- MAIN --}}
<main class="flex-1 overflow-y-auto scrollbar-hide bg-slate-50 min-w-0">

    {{-- Header --}}
    <div class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-slate-100 px-4 md:px-8 py-4 flex justify-between items-center">
        <div class="flex items-center gap-3">
            {{-- Hamburger (mobile only) --}}
            <button id="hamburger-btn" onclick="openSidebar()"
                class="w-9 h-9 rounded-lg border border-slate-200 flex items-center justify-center hover:bg-slate-50 transition-colors md:hidden">
                <span class="material-symbols-outlined text-slate-500" style="font-size:20px">menu</span>
            </button>
            <div>
                <h2 class="text-base md:text-lg font-bold text-slate-800">Hasil Skrining Saya</h2>
                <p class="text-xs text-slate-400 hidden sm:block">Halo, {{ Auth::user()->name }} — Kelas {{ Auth::user()->kelas }}</p>
            </div>
        </div>
        <span class="text-xs text-slate-400 hidden sm:block">{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
    </div>

    <div class="px-4 md:px-8 py-6 max-w-5xl mx-auto space-y-5 md:space-y-6">

        @if(session('info'))
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-center gap-3">
            <span class="material-symbols-outlined text-blue-600" style="font-size:18px">info</span>
            <span class="text-sm font-medium text-blue-700">{{ session('info') }}</span>
        </div>
        @endif

        @if($hasilTerakhir)

        {{-- Banner Risiko AI --}}
        @if(($hasilTerakhir->risiko_ai ?? '') === 'YES')
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start gap-3 animate-pulse">
            <span class="material-symbols-outlined text-amber-600 flex-shrink-0" style="font-variation-settings:'FILL' 1">warning</span>
            <span class="text-sm font-bold text-amber-800">Terdeteksi pola risiko tersembunyi — Guru BK akan menghubungi kamu</span>
        </div>
        @endif

        {{-- Profile Card --}}
        <div class="bg-white rounded-2xl p-5 md:p-8 shadow-sm flex flex-col sm:flex-row gap-4 md:gap-6 items-start sm:items-center">
            <div class="w-14 h-14 md:w-16 md:h-16 rounded-2xl bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xl md:text-2xl flex-shrink-0">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-x-8 md:gap-x-12 gap-y-3 md:gap-y-4 flex-1 w-full">
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Nama Lengkap</p>
                    <p class="text-base md:text-lg font-bold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Kelas</p>
                    <p class="text-base md:text-lg font-bold text-slate-800">{{ Auth::user()->kelas }}</p>
                </div>
                <div class="col-span-2 md:col-span-1">
                    <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Tanggal Skrining</p>
                    <p class="text-base md:text-lg font-bold text-slate-800">{{ $hasilTerakhir->created_at->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        {{-- 2 Kolom: SDQ + AI — stack di mobile --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 md:gap-6">

            {{-- Hasil SDQ --}}
            <div class="bg-white rounded-2xl p-5 md:p-8 shadow-sm space-y-5 md:space-y-6">
                <div class="flex justify-between items-start">
                    <h3 class="text-sm md:text-base font-bold text-slate-800">Hasil Skoring SDQ</h3>
                    @php
                        $sdqColor = match($hasilTerakhir->hasil_label) {
                            'Normal'     => 'bg-emerald-100 text-emerald-700',
                            'Borderline' => 'bg-amber-100 text-amber-700',
                            'Abnormal'   => 'bg-red-100 text-red-700',
                            default      => 'bg-slate-100 text-slate-600'
                        };
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $sdqColor }}">
                        {{ $hasilTerakhir->hasil_label }}
                    </span>
                </div>

                <div class="flex items-baseline gap-2">
                    <span class="text-3xl md:text-4xl font-bold text-blue-600">{{ $hasilTerakhir->total_kesulitan }}</span>
                    <span class="text-slate-400 text-sm">Skor Total SDQ</span>
                </div>

                @php
                    $bars = [
                        ['label' => 'Gejala Emosional',    'val' => $hasilTerakhir->skor_emotional,     'max' => 10, 'color' => 'bg-blue-500'],
                        ['label' => 'Masalah Perilaku',    'val' => $hasilTerakhir->skor_conduct,       'max' => 10, 'color' => 'bg-violet-500'],
                        ['label' => 'Hiperaktivitas',      'val' => $hasilTerakhir->skor_hyperactivity, 'max' => 10, 'color' => 'bg-amber-500'],
                        ['label' => 'Masalah Teman Sebaya','val' => $hasilTerakhir->skor_peer,          'max' => 10, 'color' => 'bg-orange-500'],
                        ['label' => 'Perilaku Prososial',  'val' => $hasilTerakhir->skor_prosocial,     'max' => 10, 'color' => 'bg-emerald-500'],
                    ];
                @endphp

                <div class="space-y-3 md:space-y-4 pt-1">
                    @foreach($bars as $bar)
                    <div class="space-y-1.5">
                        <div class="flex justify-between text-xs md:text-sm">
                            <span class="text-slate-500">{{ $bar['label'] }}</span>
                            <span class="font-bold text-slate-800">{{ $bar['val'] }}/{{ $bar['max'] }}</span>
                        </div>
                        <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full {{ $bar['color'] }} rounded-full" style="width: {{ ($bar['val']/$bar['max'])*100 }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Analisis AI --}}
            <div class="bg-blue-50 rounded-2xl p-5 md:p-8 shadow-sm border border-blue-100 flex flex-col">
                <div class="flex justify-between items-center mb-3 md:mb-4 text-blue-600">
                    <div class="flex items-center gap-2 md:gap-3">
                        <span class="material-symbols-outlined text-xl md:text-2xl">psychology</span>
                        <h3 class="text-sm md:text-base font-bold">Analisis AI — Pola Tersembunyi</h3>
                    </div>
                    <span class="material-symbols-outlined opacity-60">auto_awesome</span>
                </div>
                <p class="text-xs text-slate-500 mb-4 md:mb-6">Analisis mendalam berbasis pola data 553 responden</p>

                <div class="space-y-3 md:space-y-4 mb-5 md:mb-6">
                    {{-- Risiko AI --}}
                    <div class="flex items-start gap-3 md:gap-4">
                        @if(($hasilTerakhir->risiko_ai ?? '') === 'YES')
                            <div class="mt-1.5 h-3 w-3 rounded-full bg-red-400 shadow-[0_0_8px_rgba(248,113,113,0.6)] flex-shrink-0"></div>
                            <div>
                                <p class="font-bold text-slate-800 text-sm md:text-base">Terdeteksi Risiko Tersembunyi</p>
                                <p class="text-xs text-slate-500">Probabilitas risiko: <span class="font-bold text-red-600">{{ $hasilTerakhir->prob_berisiko ?? 0 }}%</span></p>
                            </div>
                        @else
                            <div class="mt-1.5 h-3 w-3 rounded-full bg-emerald-400 flex-shrink-0"></div>
                            <div>
                                <p class="font-bold text-slate-800 text-sm md:text-base">Tidak Ada Risiko Tersembunyi</p>
                                <p class="text-xs text-slate-500">Probabilitas aman: <span class="font-bold text-emerald-600">{{ 100 - ($hasilTerakhir->prob_berisiko ?? 0) }}%</span></p>
                            </div>
                        @endif
                    </div>

                    {{-- Label Detail --}}
                    @php
                        $details = [
                            ['label' => 'Depresi',        'val' => $hasilTerakhir->samuel_depresi ?? '-'],
                            ['label' => 'Kecemasan',      'val' => $hasilTerakhir->samuel_kecemasan ?? '-'],
                            ['label' => 'Kesejahteraan',  'val' => $hasilTerakhir->samuel_kesejahteraan ?? '-'],
                            ['label' => 'Gejala Negatif', 'val' => $hasilTerakhir->samuel_kelompok ?? '-'],
                        ];
                    @endphp
                    @foreach($details as $d)
                    @php
                        $dc = match($d['val']) {
                            'Tinggi','Kurang','Berisiko Tinggi' => 'text-red-600 font-bold',
                            'Sedang','Cukup','Perlu Perhatian'  => 'text-amber-600 font-bold',
                            default => 'text-emerald-600 font-bold'
                        };
                    @endphp
                    <div class="flex justify-between text-xs md:text-sm">
                        <span class="text-slate-500">{{ $d['label'] }}</span>
                        <span class="{{ $dc }}">{{ $d['val'] }}</span>
                    </div>
                    @endforeach
                </div>

                <div class="mt-auto p-3 md:p-4 rounded-xl border border-blue-200 bg-white">
                    <p class="text-xs italic text-slate-500">
                        @if(($hasilTerakhir->risiko_ai ?? '') === 'YES')
                            Hasil AI mendeteksi pola yang perlu perhatian. Guru BK akan menindaklanjuti hasil ini.
                        @else
                            Hasil analisis AI menunjukkan tidak ada pola risiko yang signifikan. Tetap jaga kesehatan mentalmu!
                        @endif
                    </p>
                </div>
            </div>
        </div>

        {{-- Riwayat Skrining --}}
        @if($riwayat->count() > 1)
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="px-4 md:px-7 py-4 md:py-5 border-b border-slate-100">
                <h4 class="text-sm md:text-base font-bold text-slate-800">Riwayat Skrining</h4>
                <p class="text-xs text-slate-400 mt-0.5">Semua hasil skrining kamu sebelumnya</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm" style="min-width:500px">
                    <thead>
                        <tr class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wide">
                            <th class="px-4 md:px-5 py-3 text-left font-semibold">Tanggal</th>
                            <th class="px-4 md:px-5 py-3 text-left font-semibold">Total Skor</th>
                            <th class="px-4 md:px-5 py-3 text-left font-semibold">Hasil SDQ</th>
                            <th class="px-4 md:px-5 py-3 text-left font-semibold">Risiko AI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($riwayat as $r)
                        @php
                            $rc = match($r->hasil_label) {
                                'Normal'     => 'bg-emerald-100 text-emerald-700',
                                'Borderline' => 'bg-amber-100 text-amber-700',
                                'Abnormal'   => 'bg-red-100 text-red-700',
                                default      => 'bg-slate-100 text-slate-600'
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/60 {{ $loop->first ? 'bg-blue-50/30' : '' }}">
                            <td class="px-4 md:px-5 py-3.5 text-slate-500 text-xs">
                                {{ $r->created_at->format('d M Y H:i') }}
                                @if($loop->first) <span class="ml-1 text-blue-600 font-bold">(Terbaru)</span> @endif
                            </td>
                            <td class="px-4 md:px-5 py-3.5 font-bold text-sm">{{ $r->total_kesulitan }}/40</td>
                            <td class="px-4 md:px-5 py-3.5">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $rc }}">{{ $r->hasil_label }}</span>
                            </td>
                            <td class="px-4 md:px-5 py-3.5">
                                @if(($r->risiko_ai ?? '') === 'YES')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">⚠ Berisiko</span>
                                @elseif(($r->risiko_ai ?? '') === 'NO')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">✓ Aman</span>
                                @else
                                    <span class="text-slate-400 text-xs">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        @else
        {{-- Belum ada data --}}
        <div class="bg-white rounded-2xl shadow-sm p-10 md:p-16 text-center">
            <span class="material-symbols-outlined text-5xl text-slate-300 block mb-4">assignment</span>
            <h3 class="text-base md:text-lg font-bold text-slate-600 mb-2">Belum Ada Hasil Skrining</h3>
            <p class="text-sm text-slate-400 mb-6">Kamu belum pernah mengisi kuesioner SDQ.</p>
            <a href="/sdq" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 md:px-6 py-3 rounded-xl text-sm transition-colors">
                <span class="material-symbols-outlined" style="font-size:18px">assignment</span>
                Isi Kuesioner Sekarang
            </a>
        </div>
        @endif

        <footer class="pt-4 border-t border-slate-100 flex justify-between items-center opacity-50 flex-wrap gap-2">
            <p class="text-xs text-slate-400">© 2025 Counselor Portal • Sistem Klasifikasi Shania</p>
            <p class="text-xs text-slate-400">Powered by SDQ + Analisis AI</p>
        </footer>

    </div>
</main>

{{-- JS SIDEBAR --}}
<script>
    function openSidebar() {
        document.getElementById('sidebar').classList.add('open');
        document.getElementById('sidebar-overlay').classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebar-overlay').classList.remove('open');
        document.body.style.overflow = '';
    }
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768) closeSidebar();
    });
</script>

</body>
</html>