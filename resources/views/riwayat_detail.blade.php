<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Skrining - {{ $s->nama }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
        .scrollbar-thin::-webkit-scrollbar { width: 4px; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 99px; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 flex min-h-screen">

{{-- SIDEBAR --}}
<aside class="w-64 min-h-screen bg-white border-r border-slate-100 flex flex-col py-8 px-4 sticky top-0 shadow-sm z-50">
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
        <a href="/dashboard" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-500 hover:text-blue-600 hover:bg-slate-50 transition-colors text-sm">
            <span class="material-symbols-outlined" style="font-size:20px">dashboard</span>
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

{{-- MAIN --}}
<main class="flex-1 min-w-0 flex flex-col">

    <header class="h-16 bg-white/80 backdrop-blur-md border-b border-slate-100 flex items-center justify-between px-8 sticky top-0 z-40">
        <div class="flex items-center gap-3">
            <a href="/riwayat" class="w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center hover:bg-slate-50 transition-colors">
                <span class="material-symbols-outlined text-slate-500" style="font-size:18px">arrow_back</span>
            </a>
            <div>
                <h2 class="text-lg font-bold text-slate-800">Detail Skrining — {{ $s->nama }}</h2>
                <p class="text-xs text-slate-400">{{ $s->kelas }} • {{ $s->created_at->isoFormat('dddd, D MMMM Y • HH:mm') }}</p>
            </div>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto scrollbar-thin p-8 space-y-6">

        @php
            $isRisikoAI   = ($s->risiko_ai ?? '') === 'YES';
            $isNormalTapiAI = $isRisikoAI && $s->hasil_label === 'Normal';

            $sdqColor = match($s->hasil_label) {
                'Normal'     => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                'Borderline' => 'bg-amber-100 text-amber-700 border-amber-200',
                'Abnormal'   => 'bg-red-100 text-red-700 border-red-200',
                default      => 'bg-slate-100 text-slate-500 border-slate-200'
            };

            $subskala = [
                ['label' => 'Emosional',      'key' => 'skor_emotional',   'val' => $s->skor_emotional,   'max' => 10, 'icon' => 'mood_bad'],
                ['label' => 'Perilaku',        'key' => 'skor_conduct',     'val' => $s->skor_conduct,     'max' => 10, 'icon' => 'warning'],
                ['label' => 'Hiperaktivitas',  'key' => 'skor_hyperactivity','val'=> $s->skor_hyperactivity,'max'=> 10, 'icon' => 'bolt'],
                ['label' => 'Teman Sebaya',    'key' => 'skor_peer',        'val' => $s->skor_peer,        'max' => 10, 'icon' => 'group'],
                ['label' => 'Prososial',       'key' => 'skor_prosocial',   'val' => $s->skor_prosocial,   'max' => 10, 'icon' => 'favorite'],
            ];

            // Pertanyaan SDQ per subskala
            $pertanyaanMap = [
                'Emosional'     => [3,8,13,16,24],
                'Perilaku'      => [5,7,12,18,22],
                'Hiperaktivitas'=> [2,10,15,21,25],
                'Teman Sebaya'  => [6,11,14,19,23],
                'Prososial'     => [1,4,9,17,20],
            ];

            $labelJawaban = ['Tidak Benar', 'Agak Benar', 'Benar'];
        @endphp

        {{-- Hero card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xl">
                    {{ strtoupper(substr($s->nama, 0, 2)) }}
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800">{{ $s->nama }}</h3>
                    <p class="text-sm text-slate-400">Kelas {{ $s->kelas }} • Skrining {{ $s->created_at->diffForHumans() }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3 flex-wrap">
                <span class="px-4 py-2 rounded-xl text-sm font-bold border {{ $sdqColor }}">
                    SDQ: {{ $s->hasil_label }}
                </span>
                @if($isNormalTapiAI)
                    <span class="px-4 py-2 rounded-xl text-sm font-bold bg-amber-100 text-amber-700 border border-amber-200 flex items-center gap-1">
                        <span class="material-symbols-outlined" style="font-size:16px;font-variation-settings:'FILL' 1">warning</span>
                        Anomali Terdeteksi
                    </span>
                @elseif($isRisikoAI)
                    <span class="px-4 py-2 rounded-xl text-sm font-bold bg-purple-100 text-purple-700 border border-purple-200">
                        ⚠ Risiko AI: Berisiko
                    </span>
                @elseif(($s->risiko_ai ?? '') === 'NO')
                    <span class="px-4 py-2 rounded-xl text-sm font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                        ✓ Risiko AI: Aman
                    </span>
                @endif
            </div>
        </div>

        {{-- Anomali warning --}}
        @if($isNormalTapiAI)
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 flex gap-3">
            <span class="material-symbols-outlined text-amber-500 mt-0.5 flex-shrink-0" style="font-size:20px;font-variation-settings:'FILL' 1">warning</span>
            <div>
                <p class="text-sm font-bold text-amber-800">Perhatian: Potensi Faking Good</p>
                <p class="text-sm text-amber-700 mt-1">SDQ siswa ini menunjukkan hasil <strong>Normal</strong>, namun model AI mendeteksi adanya risiko tersembunyi. Siswa kemungkinan besar menyembunyikan kondisi sesungguhnya. Disarankan untuk melakukan wawancara lanjutan secara personal.</p>
            </div>
        </div>
        @endif

        {{-- Grid: Skor SDQ + Analisis AI --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Skor SDQ --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-bold text-slate-800">Skor SDQ Subskala</h4>
                    <span class="text-xs text-slate-400">Total Kesulitan: <strong class="text-slate-700">{{ $s->total_kesulitan }}/40</strong></span>
                </div>
                @foreach($subskala as $sk)
                @php
                    $pct = ($sk['val'] / $sk['max']) * 100;
                    if ($sk['label'] === 'Prososial') {
                        $barColor = $sk['val'] >= 7 ? 'bg-emerald-500' : ($sk['val'] >= 4 ? 'bg-amber-400' : 'bg-red-400');
                    } else {
                        $barColor = $sk['val'] >= 7 ? 'bg-red-400' : ($sk['val'] >= 4 ? 'bg-amber-400' : 'bg-emerald-500');
                    }
                    $textColor = $sk['val'] >= 7 ? 'text-red-600' : ($sk['val'] >= 4 ? 'text-amber-600' : 'text-emerald-600');
                    if ($sk['label'] === 'Prososial') $textColor = $sk['val'] >= 7 ? 'text-emerald-600' : ($sk['val'] >= 4 ? 'text-amber-600' : 'text-red-600');
                @endphp
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-1.5 text-xs text-slate-600">
                            <span class="material-symbols-outlined text-slate-400" style="font-size:15px">{{ $sk['icon'] }}</span>
                            {{ $sk['label'] }}
                        </div>
                        <span class="text-xs font-bold {{ $textColor }}">{{ $sk['val'] }}/{{ $sk['max'] }}</span>
                    </div>
                    <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full {{ $barColor }} rounded-full transition-all" style="width:{{ $pct }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Analisis AI --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-4">
                <h4 class="text-sm font-bold text-slate-800">Analisis AI (SAMUEL)</h4>
                @if(!$s->samuel_depresi && !$s->samuel_kecemasan && !$s->samuel_kesejahteraan)
                    <div class="flex flex-col items-center justify-center py-8 text-center text-slate-400">
                        <span class="material-symbols-outlined text-3xl mb-2">psychology_alt</span>
                        <p class="text-sm">Analisis AI belum tersedia untuk data ini</p>
                    </div>
                @else
                @php
                    $aiFields = [
                        ['label' => 'Depresi',        'val' => $s->samuel_depresi,        'icon' => 'sentiment_dissatisfied',
                         'color' => match($s->samuel_depresi) { 'Rendah'=>'emerald','Sedang'=>'amber','Tinggi'=>'red', default=>'slate' }],
                        ['label' => 'Kecemasan',      'val' => $s->samuel_kecemasan,      'icon' => 'anxiety',
                         'color' => match($s->samuel_kecemasan) { 'Rendah'=>'emerald','Sedang'=>'amber','Tinggi'=>'red', default=>'slate' }],
                        ['label' => 'Kesejahteraan',  'val' => $s->samuel_kesejahteraan,  'icon' => 'spa',
                         'color' => match($s->samuel_kesejahteraan) { 'Baik'=>'emerald','Cukup'=>'amber','Kurang'=>'red', default=>'slate' }],
                    ];
                @endphp
                @foreach($aiFields as $f)
                @php $c = $f['color']; @endphp
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                    <div class="flex items-center gap-2 text-sm text-slate-600">
                        <span class="material-symbols-outlined text-{{ $c }}-500" style="font-size:18px">{{ $f['icon'] }}</span>
                        {{ $f['label'] }}
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-{{ $c }}-100 text-{{ $c }}-700">
                        {{ $f['val'] ?? '-' }}
                    </span>
                </div>
                @endforeach

                @if($s->prob_berisiko)
                <div class="flex items-center justify-between p-3 bg-purple-50 rounded-xl">
                    <span class="text-sm text-slate-600 flex items-center gap-2">
                        <span class="material-symbols-outlined text-purple-400" style="font-size:18px">data_usage</span>
                        Probabilitas Berisiko
                    </span>
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-700">{{ $s->prob_berisiko }}%</span>
                </div>
                @endif
                @endif
            </div>
        </div>

        {{-- Rekomendasi --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h4 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-500" style="font-size:18px">lightbulb</span>
                Rekomendasi Tindak Lanjut
            </h4>
            <div class="space-y-2 text-sm text-slate-600">
                @if($isNormalTapiAI)
                    <p class="flex items-start gap-2"><span class="text-amber-500 font-bold mt-0.5">→</span> Lakukan wawancara individual untuk menggali kondisi sebenarnya (kemungkinan faking good).</p>
                    <p class="flex items-start gap-2"><span class="text-amber-500 font-bold mt-0.5">→</span> Libatkan orang tua / wali untuk konfirmasi perilaku siswa di rumah.</p>
                    <p class="flex items-start gap-2"><span class="text-amber-500 font-bold mt-0.5">→</span> Lakukan skrining ulang dalam 2–4 minggu ke depan.</p>
                @elseif($s->hasil_label === 'Abnormal')
                    <p class="flex items-start gap-2"><span class="text-red-500 font-bold mt-0.5">→</span> Segera lakukan konseling individual intensif.</p>
                    <p class="flex items-start gap-2"><span class="text-red-500 font-bold mt-0.5">→</span> Koordinasi dengan orang tua dan bila perlu rujuk ke psikolog/psikiater.</p>
                    <p class="flex items-start gap-2"><span class="text-red-500 font-bold mt-0.5">→</span> Pantau perkembangan siswa secara berkala setiap minggu.</p>
                @elseif($s->hasil_label === 'Borderline')
                    <p class="flex items-start gap-2"><span class="text-amber-500 font-bold mt-0.5">→</span> Jadwalkan sesi konseling preventif dalam waktu dekat.</p>
                    <p class="flex items-start gap-2"><span class="text-amber-500 font-bold mt-0.5">→</span> Monitor perkembangan siswa dan lakukan skrining ulang dalam 1 bulan.</p>
                @else
                    <p class="flex items-start gap-2"><span class="text-emerald-500 font-bold mt-0.5">→</span> Siswa dalam kondisi normal. Lanjutkan pemantauan rutin.</p>
                    <p class="flex items-start gap-2"><span class="text-emerald-500 font-bold mt-0.5">→</span> Jadwalkan skrining berikutnya sesuai jadwal reguler sekolah.</p>
                @endif
            </div>
        </div>

        {{-- Jawaban per pertanyaan --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h4 class="text-sm font-bold text-slate-800">Jawaban Per Pertanyaan SDQ</h4>
                <p class="text-xs text-slate-400 mt-0.5">0 = Tidak Benar, 1 = Agak Benar, 2 = Benar</p>
            </div>
            <div class="divide-y divide-slate-50">
                @foreach($pertanyaanMap as $grupNama => $nomorSoal)
                <div class="px-6 py-4">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-3">{{ $grupNama }}</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                        @foreach($nomorSoal as $no)
                        @php
                            $jawaban = $s->{"sdq{$no}"} ?? 0;
                            $jawColor = match((int)$jawaban) {
                                0 => 'bg-slate-50 text-slate-500 border-slate-200',
                                1 => 'bg-amber-50 text-amber-700 border-amber-200',
                                2 => 'bg-red-50 text-red-700 border-red-200',
                                default => 'bg-slate-50 text-slate-400 border-slate-100'
                            };
                        @endphp
                        <div class="flex items-center justify-between px-3 py-2 rounded-lg border {{ $jawColor }} text-xs">
                            <span class="font-semibold">SDQ {{ $no }}</span>
                            <span>{{ $labelJawaban[$jawaban] ?? $jawaban }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <footer class="pt-4 border-t border-slate-100 flex justify-between items-center opacity-50">
            <p class="text-xs text-slate-400">© 2025 Counselor Portal • Sistem Klasifikasi Shania</p>
            <p class="text-xs text-slate-400">Powered by SDQ + Analisis AI</p>
        </footer>

    </div>
</main>
</body>
</html>