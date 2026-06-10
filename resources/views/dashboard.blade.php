<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru BK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .scrollbar-thin::-webkit-scrollbar { width: 4px; height: 4px; }
        .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 99px; }
        .animate-bar { transition: width 1s cubic-bezier(.4,0,.2,1); }
        tr.row-risiko { background-color: #fff5f5; }
        tr.row-risiko:hover { background-color: #fee2e2 !important; }

        /* Sidebar overlay for mobile */
        #sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 40;
        }
        #sidebar-overlay.open { display: block; }

        /* Sidebar drawer mobile */
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

        @media (min-width: 1024px) {
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

{{-- ===================== SIDEBAR OVERLAY ===================== --}}
<div id="sidebar-overlay" onclick="closeSidebar()"></div>

{{-- ===================== SIDEBAR ===================== --}}
<aside id="sidebar" class="w-64 flex-col py-8 px-4 shadow-sm">
    <div class="px-3 mb-10">
        <div class="flex items-center gap-2 mb-1">
            <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-white" style="font-size:18px">psychology</span>
            </div>
            <h1 class="text-base font-bold text-slate-800">Counselor Portal</h1>
        </div>
        <p class="text-xs text-slate-400 ml-10">Sistem Klasifikasi Shania</p>
    </div>

    <nav class="flex-1 space-y-1">
        <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-blue-600 font-semibold bg-blue-50 border-r-4 border-blue-600 text-sm">
            <span class="material-symbols-outlined text-blue-600" style="font-size:20px">dashboard</span>
            Dashboard
        </a>
        <a href="/riwayat" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-500 hover:text-blue-600 hover:bg-slate-50 transition-colors text-sm">
            <span class="material-symbols-outlined" style="font-size:20px">clinical_notes</span>
            Riwayat Skrining
        </a>
    </nav>

    <div class="mt-6 p-3 bg-slate-50 rounded-xl flex items-center gap-3">
        <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
        </div>
        <div class="overflow-hidden">
            <p class="text-sm font-semibold truncate text-slate-800">{{ Auth::user()->name }}</p>
            <p class="text-xs text-slate-400 truncate">Guru BK</p>
        </div>
        <form method="POST" action="/logout" class="ml-auto">
            @csrf
            <button type="submit" title="Logout">
                <span class="material-symbols-outlined text-slate-400 hover:text-red-500 transition-colors" style="font-size:18px">logout</span>
            </button>
        </form>
    </div>
</aside>

{{-- ===================== MAIN ===================== --}}
<main class="flex-1 min-w-0 flex flex-col">

    {{-- Top Bar --}}
    <header class="h-16 bg-white/80 backdrop-blur-md border-b border-slate-100 flex items-center justify-between px-4 md:px-8 sticky top-0 z-30">
        <div class="flex items-center gap-3">
            {{-- Hamburger (mobile only) --}}
            <button id="hamburger-btn" onclick="openSidebar()"
                class="w-9 h-9 rounded-lg border border-slate-200 flex items-center justify-center hover:bg-slate-50 transition-colors lg:hidden">
                <span class="material-symbols-outlined text-slate-500" style="font-size:20px">menu</span>
            </button>
            <div>
                <h2 class="text-base md:text-lg font-bold text-slate-800">Dashboard Utama</h2>
                <p class="text-xs text-slate-400 hidden sm:block">Pantau kesehatan mental siswa secara real-time</p>
            </div>
        </div>
        <span class="text-xs text-slate-400 hidden sm:block">{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
    </header>

    <div class="flex-1 overflow-y-auto scrollbar-thin p-4 md:p-8 space-y-6 md:space-y-8">

        {{-- ===== STAT CARDS ===== --}}
        @php
            $totalSiswa    = $hasilSdq->count();
            $cntNormal     = $hasilSdq->where('hasil_label', 'Normal')->count();
            $cntBorderline = $hasilSdq->where('hasil_label', 'Borderline')->count();
            $cntAbnormal   = $hasilSdq->where('hasil_label', 'Abnormal')->count();
            $cntRisikoAI   = $hasilSdq->where('risiko_ai', 'YES')->count();
        @endphp

        {{-- 2 kolom di mobile, 5 kolom di desktop --}}
        <section class="grid grid-cols-2 lg:grid-cols-5 gap-3 md:gap-5">
            {{-- Total --}}
            <div class="bg-white rounded-2xl p-4 md:p-5 border-b-4 border-blue-600 shadow-sm">
                <div class="flex items-center justify-between mb-3 md:mb-4">
                    <div class="w-8 h-8 md:w-9 md:h-9 bg-blue-50 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-blue-600" style="font-size:18px">groups</span>
                    </div>
                    <span class="text-xs font-semibold text-slate-400">Total</span>
                </div>
                <p class="text-xs text-slate-400 mb-1">Total Skrining</p>
                <h3 class="text-2xl md:text-3xl font-bold text-slate-800">{{ $totalSiswa }}</h3>
            </div>

            {{-- Normal --}}
            <div class="bg-white rounded-2xl p-4 md:p-5 border-b-4 border-emerald-500 shadow-sm">
                <div class="flex items-center justify-between mb-3 md:mb-4">
                    <div class="w-8 h-8 md:w-9 md:h-9 bg-emerald-50 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-emerald-600" style="font-size:18px">check_circle</span>
                    </div>
                    <span class="text-xs font-semibold text-emerald-500">
                        {{ $totalSiswa > 0 ? round($cntNormal/$totalSiswa*100) : 0 }}%
                    </span>
                </div>
                <p class="text-xs text-slate-400 mb-1">Status Normal</p>
                <h3 class="text-2xl md:text-3xl font-bold text-emerald-600">{{ $cntNormal }}</h3>
            </div>

            {{-- Borderline --}}
            <div class="bg-white rounded-2xl p-4 md:p-5 border-b-4 border-amber-500 shadow-sm">
                <div class="flex items-center justify-between mb-3 md:mb-4">
                    <div class="w-8 h-8 md:w-9 md:h-9 bg-amber-50 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-amber-500" style="font-size:18px">warning</span>
                    </div>
                    <span class="text-xs font-semibold text-amber-500">Tinjau</span>
                </div>
                <p class="text-xs text-slate-400 mb-1">Status Borderline</p>
                <h3 class="text-2xl md:text-3xl font-bold text-amber-500">{{ $cntBorderline }}</h3>
            </div>

            {{-- Abnormal --}}
            <div class="bg-white rounded-2xl p-4 md:p-5 border-b-4 border-red-500 shadow-sm">
                <div class="flex items-center justify-between mb-3 md:mb-4">
                    <div class="w-8 h-8 md:w-9 md:h-9 bg-red-50 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-red-500" style="font-size:18px">priority_high</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                        </span>
                    </div>
                </div>
                <p class="text-xs text-slate-400 mb-1">SDQ Abnormal</p>
                <h3 class="text-2xl md:text-3xl font-bold text-red-500">{{ $cntAbnormal }}</h3>
            </div>

            {{-- Risiko AI --}}
            <div class="bg-white rounded-2xl p-4 md:p-5 border-b-4 border-purple-500 shadow-sm col-span-2 lg:col-span-1">
                <div class="flex items-center justify-between mb-3 md:mb-4">
                    <div class="w-8 h-8 md:w-9 md:h-9 bg-purple-50 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-purple-600" style="font-size:18px">psychology</span>
                    </div>
                    @if($cntRisikoAI > 0)
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-purple-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-purple-500"></span>
                    </span>
                    @endif
                </div>
                <p class="text-xs text-slate-400 mb-1">Risiko AI (YES)</p>
                <h3 class="text-2xl md:text-3xl font-bold text-purple-600">{{ $cntRisikoAI }}</h3>
                @if($totalSiswa > 0)
                <p class="text-xs text-slate-400 mt-1">{{ round($cntRisikoAI/$totalSiswa*100) }}% dari total</p>
                @endif
            </div>
        </section>

        {{-- Banner peringatan risiko AI --}}
        @if($cntRisikoAI > 0)
        <div class="bg-purple-50 border border-purple-200 rounded-xl p-4 flex items-start gap-3">
            <span class="material-symbols-outlined text-purple-600 flex-shrink-0" style="font-variation-settings:'FILL' 1; font-size:20px">psychology</span>
            <span class="text-sm font-semibold text-purple-800">
                AI mendeteksi <strong>{{ $cntRisikoAI }} siswa</strong> dengan pola risiko tersembunyi — perlu tindak lanjut segera.
            </span>
        </div>
        @endif

        {{-- ===== CHART + SKRINING TERBARU ===== --}}
        {{-- 1 kolom di mobile, 12 kolom di desktop --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

            {{-- SDQ Breakdown Chart --}}
            <section class="lg:col-span-7 bg-white rounded-2xl p-5 md:p-7 shadow-sm">
                <div class="mb-5 md:mb-6">
                    <h4 class="text-sm md:text-base font-bold text-slate-800">Breakdown SDQ Siswa</h4>
                    <p class="text-xs text-slate-400 mt-0.5">Rata-rata skor per subskala dari semua data</p>
                </div>
                @php
                    $total = $hasilSdq->count() ?: 1;
                    $avgEmotional     = round($hasilSdq->avg('skor_emotional') / 10 * 100);
                    $avgConduct       = round($hasilSdq->avg('skor_conduct') / 10 * 100);
                    $avgHyperactivity = round($hasilSdq->avg('skor_hyperactivity') / 10 * 100);
                    $avgPeer          = round($hasilSdq->avg('skor_peer') / 10 * 100);
                    $avgProsocial     = round($hasilSdq->avg('skor_prosocial') / 10 * 100);
                    $sdqBars = [
                        ['label' => 'Masalah Emosional',    'val' => $avgEmotional,     'color' => 'bg-blue-500'],
                        ['label' => 'Masalah Perilaku',     'val' => $avgConduct,        'color' => 'bg-violet-500'],
                        ['label' => 'Hiperaktivitas',       'val' => $avgHyperactivity,  'color' => 'bg-amber-500'],
                        ['label' => 'Masalah Teman Sebaya', 'val' => $avgPeer,           'color' => 'bg-orange-500'],
                        ['label' => 'Perilaku Prososial',   'val' => $avgProsocial,      'color' => 'bg-emerald-500'],
                    ];
                @endphp
                <div class="space-y-4 md:space-y-5">
                    @foreach($sdqBars as $bar)
                    <div>
                        <div class="flex justify-between text-sm mb-1.5">
                            <span class="text-slate-600 text-xs md:text-sm">{{ $bar['label'] }}</span>
                            <span class="font-bold text-slate-800 text-xs md:text-sm">{{ $bar['val'] }}%</span>
                        </div>
                        <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full {{ $bar['color'] }} rounded-full animate-bar" style="width: {{ $bar['val'] }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>

            {{-- Skrining Terbaru --}}
            <section class="lg:col-span-5 bg-white rounded-2xl p-5 md:p-7 shadow-sm flex flex-col">
                <div class="flex items-center justify-between mb-4 md:mb-5">
                    <h4 class="text-sm md:text-base font-bold text-slate-800">Skrining Terbaru</h4>
                    <div class="flex items-center gap-2 flex-wrap">
                        @if($cntRisikoAI > 0)
                            <span class="bg-purple-50 text-purple-600 px-2 py-0.5 rounded-full text-xs font-bold border border-purple-100">
                                {{ $cntRisikoAI }} AI
                            </span>
                        @endif
                        @if($cntAbnormal > 0)
                            <span class="bg-red-50 text-red-500 px-2 py-0.5 rounded-full text-xs font-bold border border-red-100">
                                {{ $cntAbnormal }} Abnormal
                            </span>
                        @endif
                    </div>
                </div>
                <div class="space-y-2.5 flex-1 overflow-y-auto scrollbar-thin max-h-64 md:max-h-72">
                    @forelse($hasilSdq->take(6) as $s)
                        @php
                            $isRisikoAI = ($s->risiko_ai ?? '') === 'YES';
                            $isAlert    = $s->hasil_label === 'Abnormal';
                            $isBorder   = $s->hasil_label === 'Borderline';
                            $initials   = strtoupper(substr($s->nama ?? 'S', 0, 2));
                            $badgeColor = $isAlert ? 'bg-red-500 text-white' : ($isBorder ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700');
                            $cardBg     = $isRisikoAI ? 'border-purple-200 bg-purple-50/40' : ($isAlert ? 'border-red-100 bg-red-50/40' : 'border-slate-100');
                        @endphp
                        <div class="p-3 rounded-xl border {{ $cardBg }} hover:border-blue-100 hover:bg-blue-50/20 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-600 text-xs flex-shrink-0">
                                        {{ $initials }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-slate-800 flex items-center gap-1 truncate">
                                            {{ $s->nama }}
                                            @if($isRisikoAI)
                                                <span class="material-symbols-outlined text-purple-500 flex-shrink-0" style="font-size:13px;font-variation-settings:'FILL' 1">psychology</span>
                                            @endif
                                        </p>
                                        <p class="text-xs text-slate-400 truncate">Kelas {{ $s->kelas }} • {{ $s->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-1 flex-shrink-0 ml-2">
                                    <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ $badgeColor }}">
                                        {{ $s->hasil_label }}
                                    </span>
                                    @if($isRisikoAI)
                                        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-purple-100 text-purple-700">⚠ AI</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-slate-400 text-sm">Belum ada data skrining</div>
                    @endforelse
                </div>
            </section>
        </div>

        {{-- ===== TABEL LENGKAP ===== --}}
        <section class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="px-4 md:px-7 py-4 md:py-5 border-b border-slate-100 flex items-center justify-between flex-wrap gap-2">
                <div>
                    <h4 class="text-sm md:text-base font-bold text-slate-800">
                        Hasil Lengkap SDQ
                        <span class="ml-2 text-xs font-semibold bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full">+ Analisis AI</span>
                    </h4>
                    <p class="text-xs text-slate-400 mt-0.5">
                        Data tersimpan dari semua skrining siswa
                        @if($cntRisikoAI > 0)
                            — <span class="text-purple-600 font-semibold">{{ $cntRisikoAI }} baris</span> terdeteksi risiko AI
                        @endif
                    </p>
                </div>
                <a href="/riwayat" class="text-xs text-blue-600 font-semibold hover:underline flex items-center gap-1">
                    Lihat semua
                    <span class="material-symbols-outlined" style="font-size:14px">chevron_right</span>
                </a>
            </div>

            {{-- Horizontal scroll untuk tabel di semua ukuran layar --}}
            <div class="overflow-x-auto scrollbar-thin">
                <table class="w-full text-sm" style="min-width: 1300px">
                    <thead>
                        <tr class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wide">
                            <th class="px-4 md:px-5 py-3 text-left font-semibold">Nama</th>
                            <th class="px-4 md:px-5 py-3 text-left font-semibold">Kelas</th>
                            <th class="px-4 md:px-5 py-3 text-left font-semibold">Total</th>
                            <th class="px-4 md:px-5 py-3 text-left font-semibold">Hasil SDQ</th>
                            <th class="px-4 md:px-5 py-3 text-left font-semibold">Emosional</th>
                            <th class="px-4 md:px-5 py-3 text-left font-semibold">Perilaku</th>
                            <th class="px-4 md:px-5 py-3 text-left font-semibold">Hiperaktivitas</th>
                            <th class="px-4 md:px-5 py-3 text-left font-semibold">Teman Sebaya</th>
                            <th class="px-4 md:px-5 py-3 text-left font-semibold">Prososial</th>
                            <th class="px-4 md:px-5 py-3 text-left font-semibold">Naive Bayes</th>
                             <th class="px-4 md:px-5 py-3 text-left font-semibold">Keputusan Akhir</th>
                             <th class="px-4 md:px-5 py-3 text-left font-semibold">Rekomendasi Tindakan</th>
                             <th class="px-4 md:px-5 py-3 text-left font-semibold">Risiko AI</th>
                             <th class="px-4 md:px-5 py-3 text-left font-semibold">Tanggal</th>
                             <th class="px-4 md:px-5 py-3 text-left font-semibold">Poin</th>
                             <th class="px-4 md:px-5 py-3 text-left font-semibold">Kesimpulan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($hasilSdq as $s)
                        @php
                            $isRisikoAI = ($s->risiko_ai ?? '') === 'YES';
                            $sdqColor = match($s->hasil_label) {
                                'Normal'     => 'bg-emerald-100 text-emerald-700',
                                'Borderline' => 'bg-amber-100 text-amber-700',
                                'Abnormal'   => 'bg-red-100 text-red-700',
                                default      => 'bg-slate-100 text-slate-600'
                            };
                            $poin = 0;
                            if ($s->keputusan_akhir) {
                                $poin += match($s->keputusan_akhir) { 'Borderline'=>2,'High Risk'=>4, default=>0 };
                                $poin += $s->hasil_naive_bayes === 'High Risk' ? 2 : ($s->hasil_naive_bayes === 'Borderline' ? 1 : 0);
                            } else {
                                $poin += match($s->hasil_label) { 'Borderline'=>1,'Abnormal'=>2,default=>0 };
                                $poin += match($s->samuel_depresi ?? '') { 'Sedang'=>1,'Tinggi'=>2,default=>0 };
                                $poin += match($s->samuel_kecemasan ?? '') { 'Sedang'=>1,'Tinggi'=>2,default=>0 };
                                $poin += match($s->samuel_kesejahteraan ?? '') { 'Cukup'=>1,'Kurang'=>2,default=>0 };
                                $poin += match($s->samuel_kelompok ?? '') { 'Sedang'=>1,'Tinggi'=>2,default=>0 };
                                $poin += $isRisikoAI ? 2 : 0;
                            }
                            $faktor = [];
                            if ($s->keputusan_akhir) {
                                if ($s->hasil_naive_bayes === 'High Risk') $faktor[] = ['t'=>'NB High Risk','c'=>'bg-red-100 text-red-700'];
                                elseif ($s->hasil_naive_bayes === 'Borderline') $faktor[] = ['t'=>'NB Borderline','c'=>'bg-amber-100 text-amber-700'];
                                if ($s->hasil_label === 'Abnormal') $faktor[] = ['t'=>'Baku Abnormal','c'=>'bg-red-100 text-red-700'];
                                elseif ($s->hasil_label === 'Borderline') $faktor[] = ['t'=>'Baku Borderline','c'=>'bg-amber-100 text-amber-700'];
                            } else {
                                if ($isRisikoAI) $faktor[] = ['t'=>'Risiko AI','c'=>'bg-purple-100 text-purple-700'];
                                if (($s->samuel_depresi??'') === 'Tinggi')          $faktor[] = ['t'=>'Depresi Tinggi','c'=>'bg-red-100 text-red-700'];
                                elseif (($s->samuel_depresi??'') === 'Sedang')      $faktor[] = ['t'=>'Depresi Sedang','c'=>'bg-amber-100 text-amber-700'];
                                if (($s->samuel_kecemasan??'') === 'Tinggi')        $faktor[] = ['t'=>'Kecemasan Tinggi','c'=>'bg-red-100 text-red-700'];
                                elseif (($s->samuel_kecemasan??'') === 'Sedang')    $faktor[] = ['t'=>'Kecemasan Sedang','c'=>'bg-amber-100 text-amber-700'];
                                if (($s->samuel_kesejahteraan??'') === 'Kurang')    $faktor[] = ['t'=>'Wellbeing Kurang','c'=>'bg-red-100 text-red-700'];
                                elseif (($s->samuel_kesejahteraan??'') === 'Cukup') $faktor[] = ['t'=>'Wellbeing Cukup','c'=>'bg-amber-100 text-amber-700'];
                            }
                            [$overall,$overallColor] = match(true) {
                                $poin <= 2 => ['Baik',      'bg-emerald-100 text-emerald-700'],
                                $poin <= 4 => ['Waspada',   'bg-amber-100 text-amber-700'],
                                $poin <= 6 => ['Perhatian', 'bg-orange-100 text-orange-700'],
                                default    => ['Prioritas', 'bg-red-100 text-red-700'],
                            };
                        @endphp
                        <tr class="transition-colors {{ $isRisikoAI ? 'row-risiko' : 'hover:bg-slate-50/60' }}">
                            <td class="px-4 md:px-5 py-3.5 font-semibold text-slate-800">
                                <div class="flex items-center gap-1.5">
                                    {{ $s->nama }}
                                    @if($isRisikoAI)
                                        <span class="material-symbols-outlined text-purple-500" style="font-size:14px;font-variation-settings:'FILL' 1" title="Terdeteksi Risiko AI">psychology</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 md:px-5 py-3.5 text-slate-500">{{ $s->kelas }}</td>
                            <td class="px-4 md:px-5 py-3.5 font-semibold">{{ $s->total_kesulitan }}<span class="text-slate-400 font-normal">/40</span></td>
                            <td class="px-4 md:px-5 py-3.5">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $sdqColor }}">{{ $s->hasil_label }}</span>
                            </td>
                            <td class="px-4 md:px-5 py-3.5 text-slate-600">{{ $s->skor_emotional }}</td>
                            <td class="px-4 md:px-5 py-3.5 text-slate-600">{{ $s->skor_conduct }}</td>
                            <td class="px-4 md:px-5 py-3.5 text-slate-600">{{ $s->skor_hyperactivity }}</td>
                            <td class="px-4 md:px-5 py-3.5 text-slate-600">{{ $s->skor_peer }}</td>
                            <td class="px-4 md:px-5 py-3.5 text-slate-600">{{ $s->skor_prosocial }}</td>
                            <td class="px-4 md:px-5 py-3.5">
                                @if($s->hasil_naive_bayes)
                                    @php
                                        $nbColor = match($s->hasil_naive_bayes) {
                                            'Normal'     => 'bg-emerald-100 text-emerald-700',
                                            'Borderline' => 'bg-amber-100 text-amber-700',
                                            'High Risk'  => 'bg-red-100 text-red-700',
                                            default      => 'bg-slate-100 text-slate-600'
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $nbColor }}">{{ $s->hasil_naive_bayes }}</span>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 md:px-5 py-3.5">
                                @if($s->keputusan_akhir)
                                    @php
                                        $kaColor = match($s->keputusan_akhir) {
                                            'Normal'     => 'bg-emerald-100 text-emerald-700',
                                            'Borderline' => 'bg-amber-100 text-amber-700',
                                            'High Risk'  => 'bg-red-100 text-red-700',
                                            default      => 'bg-slate-100 text-slate-600'
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $kaColor }}">{{ $s->keputusan_akhir }}</span>
                                @else
                                    @php
                                        $kaColor = match($s->hasil_label) {
                                            'Normal'     => 'bg-emerald-100 text-emerald-700',
                                            'Borderline' => 'bg-amber-100 text-amber-700',
                                            'Abnormal'   => 'bg-red-100 text-red-700',
                                            default      => 'bg-slate-100 text-slate-600'
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $kaColor }}">{{ $s->hasil_label }}</span>
                                @endif
                            </td>
                            <td class="px-4 md:px-5 py-3.5 text-xs text-slate-600 max-w-[200px] truncate" title="{{ $s->tindakan }}">
                                {{ $s->tindakan ?? ($s->risiko_ai === 'YES' ? 'Bimbingan Konseling' : 'Pemantauan Rutin') }}
                            </td>
                            <td class="px-4 md:px-5 py-3.5">
                                @if($isRisikoAI)
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-700">⚠ Berisiko</span>
                                    <div class="text-xs text-slate-400 mt-1">{{ $s->prob_berisiko ?? 0 }}% prob.</div>
                                @elseif(($s->risiko_ai ?? '') === 'NO')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">✓ Aman</span>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 md:px-5 py-3.5 text-slate-400 text-xs whitespace-nowrap">{{ $s->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 md:px-5 py-3.5 text-center">
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold
                                    {{ $poin <= 2 ? 'bg-emerald-100 text-emerald-700' : ($poin <= 5 ? 'bg-amber-100 text-amber-700' : ($poin <= 8 ? 'bg-orange-100 text-orange-700' : 'bg-red-100 text-red-700')) }}">
                                    {{ $poin }}
                                </span>
                            </td>
                            <td class="px-4 md:px-5 py-3.5">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $overallColor }}">{{ $overall }}</span>
                                <div class="flex flex-wrap gap-1 mt-1.5">
                                    @if(count($faktor) > 0)
                                        @foreach($faktor as $f)
                                            <span class="px-2 py-0.5 rounded text-xs font-medium {{ $f['c'] }}">{{ $f['t'] }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-xs text-slate-400">Semua normal</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="17" class="px-5 py-16 text-center text-slate-400">
                                <span class="material-symbols-outlined text-4xl block mb-2">inbox</span>
                                Belum ada data skrining
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <footer class="pt-4 border-t border-slate-100 flex justify-between items-center opacity-50 flex-wrap gap-2">
            <p class="text-xs text-slate-400">© 2025 Counselor Portal • Sistem Klasifikasi Shania</p>
            <p class="text-xs text-slate-400">Powered by SDQ + Analisis AI</p>
        </footer>

    </div>
</main>

{{-- ===================== JS SIDEBAR ===================== --}}
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
    // Tutup sidebar saat resize ke desktop
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) closeSidebar();
    });
</script>

</body>
</html>