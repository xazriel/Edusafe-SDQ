<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil SDQ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">

    <div class="max-w-2xl mx-auto px-4 py-10 space-y-5">

        {{-- Header --}}
        <div class="flex items-center gap-2 mb-2">
            <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-white" style="font-size:18px">psychology</span>
            </div>
            <h1 class="text-base font-bold text-slate-800">Hasil Skrining SDQ</h1>
        </div>

        @php
            $kelas = match($label) {
                'Normal'     => ['bg-emerald-50 border-emerald-200', 'text-emerald-700', '✅'],
                'Borderline' => ['bg-amber-50 border-amber-200',     'text-amber-700',   '⚠️'],
                'Abnormal'   => ['bg-red-50 border-red-200',         'text-red-700',     '🔴'],
                default      => ['bg-slate-50 border-slate-200',     'text-slate-700',   '📋'],
            };
            $deskripsi = match($label) {
                'Normal'     => 'Tidak ditemukan indikasi masalah yang signifikan.',
                'Borderline' => 'Terdapat beberapa indikasi yang perlu dipantau lebih lanjut.',
                'Abnormal'   => 'Terdapat indikasi masalah yang perlu penanganan profesional.',
                default      => ''
            };
        @endphp

        {{-- Hasil Utama --}}
        <div class="bg-white rounded-2xl shadow-sm p-8 text-center border-t-4
            {{ $label === 'Normal' ? 'border-emerald-500' : ($label === 'Borderline' ? 'border-amber-500' : 'border-red-500') }}">
            <div class="text-4xl mb-3">{{ $kelas[2] }}</div>
            <h2 class="text-2xl font-bold {{ $kelas[1] }} mb-2">{{ $label }}</h2>
            <p class="text-slate-500 text-sm">{{ $deskripsi }}</p>
            <div class="mt-4 inline-block bg-slate-100 rounded-xl px-5 py-2">
                <span class="text-slate-500 text-sm">Total Skor: </span>
                <span class="font-bold text-slate-800 text-lg">{{ $total }}/40</span>
            </div>
        </div>

        {{-- Rincian Subskala --}}
        <div class="bg-white rounded-2xl shadow-sm p-7">
            <h3 class="text-base font-bold text-slate-800 mb-5">Rincian Skor per Subskala</h3>
            @php
                $subskalas = [
                    ['nama' => 'Gejala Emosional',     'skor' => $emotional,     'color' => 'bg-blue-500'],
                    ['nama' => 'Masalah Perilaku',      'skor' => $conduct,       'color' => 'bg-violet-500'],
                    ['nama' => 'Hiperaktivitas',        'skor' => $hyperactivity, 'color' => 'bg-amber-500'],
                    ['nama' => 'Masalah Teman Sebaya',  'skor' => $peer,          'color' => 'bg-orange-500'],
                    ['nama' => 'Perilaku Prososial',    'skor' => $prosocial,     'color' => 'bg-emerald-500'],
                ];
            @endphp
            <div class="space-y-4">
                @foreach($subskalas as $s)
                <div>
                    <div class="flex justify-between text-sm mb-1.5">
                        <span class="text-slate-500">{{ $s['nama'] }}</span>
                        <span class="font-bold text-slate-800">{{ $s['skor'] }}/10</span>
                    </div>
                    <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full {{ $s['color'] }} rounded-full" style="width: {{ ($s['skor']/10)*100 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Analisis Samuel (jika ada) --}}
        @if(isset($samuel) && ($samuel['depresi'] ?? '') !== 'Tidak tersedia')
        <div class="bg-blue-50 rounded-2xl shadow-sm p-7 border border-blue-100">
            <div class="flex items-center gap-2 mb-5">
                <span class="material-symbols-outlined text-blue-600">psychology</span>
                <h3 class="text-base font-bold text-slate-800">Analisis AI</h3>
            </div>
            <div class="grid grid-cols-2 gap-3">
                @php
                    $items = [
                        ['label' => 'Depresi',        'val' => $samuel['depresi'] ?? '-'],
                        ['label' => 'Kecemasan',      'val' => $samuel['kecemasan'] ?? '-'],
                        ['label' => 'Kesejahteraan',  'val' => $samuel['kesejahteraan'] ?? '-'],
                        ['label' => 'Risiko AI',      'val' => $samuel['risiko_ai'] ?? '-'],
                    ];
                @endphp
                @foreach($items as $item)
                @php
                    $vc = match($item['val']) {
                        'Tinggi','Kurang','YES' => 'text-red-600 font-bold',
                        'Sedang','Cukup'        => 'text-amber-600 font-bold',
                        default                 => 'text-emerald-600 font-bold'
                    };
                @endphp
                <div class="bg-white rounded-xl p-4 border border-blue-100">
                    <p class="text-xs text-slate-400 mb-1">{{ $item['label'] }}</p>
                    <p class="text-sm {{ $vc }}">{{ $item['val'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Tombol --}}
        <div class="flex gap-3">
            <a href="/sdq"
                class="flex-1 text-center py-3 rounded-xl border-2 border-blue-600 text-blue-600 font-semibold text-sm hover:bg-blue-50 transition-colors">
                ← Isi Ulang SDQ
            </a>
            <a href="/dashboard-siswa"
                class="flex-1 text-center py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm transition-colors">
                Lihat Dashboard →
            </a>
        </div>

    </div>
</body>
</html>