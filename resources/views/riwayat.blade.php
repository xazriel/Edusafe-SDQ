<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Skrining - Counselor Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
        .scrollbar-thin::-webkit-scrollbar { width: 4px; height: 4px; }
        .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 99px; }
        tr.row-anomali { background-color: #fffbeb; }
        tr.row-anomali:hover { background-color: #fef3c7 !important; }
        tr.row-risiko-ai { background-color: #faf5ff; }
        tr.row-risiko-ai:hover { background-color: #f3e8ff !important; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 flex min-h-screen">

{{-- ===================== SIDEBAR ===================== --}}
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
        <a href="/riwayat" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-blue-600 font-semibold bg-blue-50 border-r-4 border-blue-600 text-sm">
            <span class="material-symbols-outlined text-blue-600" style="font-size:20px">clinical_notes</span>
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
    <header class="h-16 bg-white/80 backdrop-blur-md border-b border-slate-100 flex items-center justify-between px-8 sticky top-0 z-40">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Riwayat Skrining Siswa</h2>
            <p class="text-xs text-slate-400">Semua data skrining SDQ beserta analisis AI</p>
        </div>
        <span class="text-xs text-slate-400">{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
    </header>

    <div class="flex-1 overflow-y-auto scrollbar-thin p-8 space-y-6">

        {{-- ===== FILTER BAR ===== --}}
        <form method="GET" action="/riwayat">
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex flex-wrap items-center gap-3">

                {{-- Search nama --}}
                <div class="flex-1 min-w-[220px] relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" style="font-size:18px">search</span>
                    <input type="text" name="nama" value="{{ request('nama') }}"
                        placeholder="Cari nama siswa..."
                        class="w-full pl-9 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50">
                </div>

                {{-- Filter kelas --}}
                <select name="kelas" class="text-sm border border-slate-200 rounded-xl px-3 py-2.5 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                    <option value="">Semua Kelas</option>
                    @foreach($daftarKelas as $k)
                        <option value="{{ $k }}" {{ request('kelas') == $k ? 'selected' : '' }}>{{ $k }}</option>
                    @endforeach
                </select>

                {{-- Filter status SDQ --}}
                <select name="status" class="text-sm border border-slate-200 rounded-xl px-3 py-2.5 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                    <option value="">Semua Status SDQ</option>
                    <option value="Normal"     {{ request('status') == 'Normal'     ? 'selected' : '' }}>Normal</option>
                    <option value="Borderline" {{ request('status') == 'Borderline' ? 'selected' : '' }}>Borderline</option>
                    <option value="Abnormal"   {{ request('status') == 'Abnormal'   ? 'selected' : '' }}>Abnormal</option>
                </select>

                {{-- Filter risiko AI --}}
                <select name="risiko_ai" class="text-sm border border-slate-200 rounded-xl px-3 py-2.5 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                    <option value="">Semua Risiko AI</option>
                    <option value="YES" {{ request('risiko_ai') == 'YES' ? 'selected' : '' }}>Berisiko (YES)</option>
                    <option value="NO"  {{ request('risiko_ai') == 'NO'  ? 'selected' : '' }}>Aman (NO)</option>
                </select>

                {{-- Tombol --}}
                <button type="submit"
                    class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition-colors">
                    <span class="material-symbols-outlined" style="font-size:18px">filter_list</span>
                    Filter
                </button>

                @if(request()->hasAny(['nama','kelas','status','risiko_ai']))
                <a href="/riwayat"
                    class="flex items-center gap-1.5 text-sm text-slate-400 hover:text-red-500 transition-colors font-medium">
                    <span class="material-symbols-outlined" style="font-size:16px">close</span>
                    Reset
                </a>
                @endif
            </div>
        </form>

        {{-- Info hasil filter --}}
        @if(request()->hasAny(['nama','kelas','status','risiko_ai']))
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <span class="material-symbols-outlined text-blue-500" style="font-size:16px">info</span>
            Menampilkan <strong class="text-slate-700">{{ $hasilSdq->total() }}</strong> hasil
            @if(request('nama')) dengan nama "<strong class="text-slate-700">{{ request('nama') }}</strong>"@endif
            @if(request('kelas')) kelas <strong class="text-slate-700">{{ request('kelas') }}</strong>@endif
            @if(request('status')) status <strong class="text-slate-700">{{ request('status') }}</strong>@endif
            @if(request('risiko_ai')) risiko AI <strong class="text-slate-700">{{ request('risiko_ai') }}</strong>@endif
        </div>
        @endif

        {{-- ===== TABEL ===== --}}
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

            {{-- Legend --}}
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between flex-wrap gap-3">
                <div>
                    <h4 class="text-sm font-bold text-slate-800">Semua Data Skrining</h4>
                    <p class="text-xs text-slate-400 mt-0.5">Total {{ $hasilSdq->total() }} skrining tersimpan</p>
                </div>
                <div class="flex items-center gap-4 text-xs text-slate-500">
                    <span class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-sm bg-amber-100 border border-amber-300 inline-block"></span>
                        Anomali (SDQ Normal tapi AI Berisiko)
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-sm bg-purple-100 border border-purple-300 inline-block"></span>
                        Risiko AI Terdeteksi
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto scrollbar-thin">
                <table class="w-full text-sm" style="min-width:1000px">
                    <thead>
                        <tr class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wide border-b border-slate-100">
                            <th class="px-5 py-3 text-left font-semibold w-10">No</th>
                            <th class="px-5 py-3 text-left font-semibold">Tanggal</th>
                            <th class="px-5 py-3 text-left font-semibold">Nama Siswa</th>
                            <th class="px-5 py-3 text-left font-semibold">Kelas</th>
                            <th class="px-5 py-3 text-left font-semibold">Skor SDQ</th>
                            <th class="px-5 py-3 text-left font-semibold">Status SDQ</th>
                            <th class="px-5 py-3 text-left font-semibold">Depresi</th>
                            <th class="px-5 py-3 text-left font-semibold">Kecemasan</th>
                            <th class="px-5 py-3 text-left font-semibold">Kesejahteraan</th>
                            <th class="px-5 py-3 text-center font-semibold">Risiko AI</th>
                            <th class="px-5 py-3 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($hasilSdq as $i => $s)
                        @php
                            $isRisikoAI = ($s->risiko_ai ?? '') === 'YES';
                            $isNormalTapiAI = $isRisikoAI && $s->hasil_label === 'Normal'; // anomali sejati

                            // warna baris
                            if ($isNormalTapiAI)   $rowClass = 'row-anomali';
                            elseif ($isRisikoAI)   $rowClass = 'row-risiko-ai';
                            else                   $rowClass = 'hover:bg-slate-50/60 transition-colors';

                            $sdqColor = match($s->hasil_label) {
                                'Normal'     => 'bg-emerald-100 text-emerald-700',
                                'Borderline' => 'bg-amber-100 text-amber-700',
                                'Abnormal'   => 'bg-red-100 text-red-700',
                                default      => 'bg-slate-100 text-slate-500'
                            };
                            $depColor = match($s->samuel_depresi ?? '') {
                                'Rendah' => 'text-emerald-600', 'Sedang' => 'text-amber-600',
                                'Tinggi' => 'text-red-600', default => 'text-slate-400'
                            };
                            $anxColor = match($s->samuel_kecemasan ?? '') {
                                'Rendah' => 'text-emerald-600', 'Sedang' => 'text-amber-600',
                                'Tinggi' => 'text-red-600', default => 'text-slate-400'
                            };
                            $welColor = match($s->samuel_kesejahteraan ?? '') {
                                'Baik' => 'text-emerald-600', 'Cukup' => 'text-amber-600',
                                'Kurang' => 'text-red-600', default => 'text-slate-400'
                            };

                            // warna chip skor subskala
                            $chipColor = function($val) {
                                if ($val >= 7) return 'bg-red-100 text-red-700';
                                if ($val >= 4) return 'bg-amber-100 text-amber-700';
                                return 'bg-emerald-100 text-emerald-700';
                            };
                        @endphp
                        <tr class="{{ $rowClass }}">
                            {{-- No --}}
                            <td class="px-5 py-4 text-slate-400 text-xs">
                                {{ ($hasilSdq->currentPage() - 1) * $hasilSdq->perPage() + $i + 1 }}
                            </td>

                            {{-- Tanggal --}}
                            <td class="px-5 py-4 text-slate-500 text-xs whitespace-nowrap">
                                {{ $s->created_at->format('d M Y') }}<br>
                                <span class="text-slate-300">{{ $s->created_at->format('H:i') }}</span>
                            </td>

                            {{-- Nama --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs flex-shrink-0">
                                        {{ strtoupper(substr($s->nama ?? 'S', 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800 flex items-center gap-1">
                                            {{ $s->nama }}
                                            @if($isNormalTapiAI)
                                                <span class="material-symbols-outlined text-amber-500" style="font-size:14px;font-variation-settings:'FILL' 1" title="Anomali: SDQ Normal tapi AI Berisiko">warning</span>
                                            @elseif($isRisikoAI)
                                                <span class="material-symbols-outlined text-purple-500" style="font-size:14px;font-variation-settings:'FILL' 1" title="Risiko AI Terdeteksi">psychology</span>
                                            @endif
                                        </p>
                                        <p class="text-xs text-slate-400">{{ $s->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Kelas --}}
                            <td class="px-5 py-4 text-slate-500">{{ $s->kelas }}</td>

                            {{-- Skor SDQ breakdown --}}
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-1">
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold {{ $chipColor($s->skor_emotional) }}" title="Emosional">E:{{ $s->skor_emotional }}</span>
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold {{ $chipColor($s->skor_conduct) }}" title="Perilaku">C:{{ $s->skor_conduct }}</span>
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold {{ $chipColor($s->skor_hyperactivity) }}" title="Hiperaktivitas">H:{{ $s->skor_hyperactivity }}</span>
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold {{ $chipColor($s->skor_peer) }}" title="Teman Sebaya">P:{{ $s->skor_peer }}</span>
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600" title="Prososial">Pr:{{ $s->skor_prosocial }}</span>
                                </div>
                                <p class="text-xs text-slate-400 mt-1">Total: <strong class="text-slate-600">{{ $s->total_kesulitan }}/40</strong></p>
                            </td>

                            {{-- Status SDQ --}}
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $sdqColor }}">{{ $s->hasil_label }}</span>
                            </td>

                            {{-- Depresi --}}
                            <td class="px-5 py-4 text-xs font-semibold {{ $depColor }}">{{ $s->samuel_depresi ?? '-' }}</td>

                            {{-- Kecemasan --}}
                            <td class="px-5 py-4 text-xs font-semibold {{ $anxColor }}">{{ $s->samuel_kecemasan ?? '-' }}</td>

                            {{-- Kesejahteraan --}}
                            <td class="px-5 py-4 text-xs font-semibold {{ $welColor }}">{{ $s->samuel_kesejahteraan ?? '-' }}</td>

                            {{-- Risiko AI --}}
                            <td class="px-5 py-4 text-center">
                                @if($isRisikoAI)
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-700">⚠ Berisiko</span>
                                    @if($s->prob_berisiko)
                                        <div class="text-xs text-slate-400 mt-1">{{ $s->prob_berisiko }}%</div>
                                    @endif
                                @elseif(($s->risiko_ai ?? '') === 'NO')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">✓ Aman</span>
                                @else
                                    <span class="text-xs text-slate-300">-</span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-5 py-4 text-right">
                                <a href="/riwayat/{{ $s->id }}"
                                    class="inline-flex items-center gap-1 text-sm text-blue-600 hover:bg-blue-50 px-3 py-1.5 rounded-lg transition-colors font-medium">
                                    Detail
                                    <span class="material-symbols-outlined" style="font-size:14px">chevron_right</span>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="px-5 py-16 text-center text-slate-400">
                                <span class="material-symbols-outlined text-4xl block mb-2">inbox</span>
                                Tidak ada data yang cocok dengan filter
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-4 bg-white border-t border-slate-100 flex items-center justify-between flex-wrap gap-3">
                <p class="text-xs text-slate-400">
                    Menampilkan <strong>{{ $hasilSdq->firstItem() }}–{{ $hasilSdq->lastItem() }}</strong>
                    dari <strong>{{ $hasilSdq->total() }}</strong> data
                </p>
                <div class="flex items-center gap-1">
                    {{-- Prev --}}
                    @if($hasilSdq->onFirstPage())
                        <span class="w-9 h-9 rounded-lg flex items-center justify-center border border-slate-200 text-slate-300 cursor-not-allowed">
                            <span class="material-symbols-outlined" style="font-size:18px">chevron_left</span>
                        </span>
                    @else
                        <a href="{{ $hasilSdq->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}"
                            class="w-9 h-9 rounded-lg flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition-colors">
                            <span class="material-symbols-outlined" style="font-size:18px">chevron_left</span>
                        </a>
                    @endif

                    {{-- Page numbers --}}
                    @foreach($hasilSdq->getUrlRange(1, $hasilSdq->lastPage()) as $page => $url)
                        @if($page == $hasilSdq->currentPage())
                            <span class="w-9 h-9 rounded-lg flex items-center justify-center bg-blue-600 text-white text-sm font-bold">{{ $page }}</span>
                        @elseif($page == 1 || $page == $hasilSdq->lastPage() || abs($page - $hasilSdq->currentPage()) <= 1)
                            <a href="{{ $url }}&{{ http_build_query(request()->except('page')) }}"
                                class="w-9 h-9 rounded-lg flex items-center justify-center border border-slate-200 hover:bg-slate-50 text-sm transition-colors">{{ $page }}</a>
                        @elseif(abs($page - $hasilSdq->currentPage()) == 2)
                            <span class="w-9 h-9 flex items-center justify-center text-slate-400 text-sm">…</span>
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if($hasilSdq->hasMorePages())
                        <a href="{{ $hasilSdq->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}"
                            class="w-9 h-9 rounded-lg flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition-colors">
                            <span class="material-symbols-outlined" style="font-size:18px">chevron_right</span>
                        </a>
                    @else
                        <span class="w-9 h-9 rounded-lg flex items-center justify-center border border-slate-200 text-slate-300 cursor-not-allowed">
                            <span class="material-symbols-outlined" style="font-size:18px">chevron_right</span>
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Legend --}}
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
            <h3 class="text-sm font-bold text-amber-800 mb-2 flex items-center gap-2">
                <span class="material-symbols-outlined text-amber-600" style="font-size:16px">info</span>
                Keterangan Warna Baris
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs text-slate-600">
                <div class="flex items-start gap-2">
                    <span class="w-3 h-3 rounded-sm bg-amber-100 border border-amber-300 mt-0.5 flex-shrink-0"></span>
                    <span><strong class="text-amber-700">Kuning</strong> — Anomali: SDQ Normal tapi AI mendeteksi risiko tersembunyi. Siswa kemungkinan menyembunyikan kondisi sebenarnya (faking good).</span>
                </div>
                <div class="flex items-start gap-2">
                    <span class="w-3 h-3 rounded-sm bg-purple-100 border border-purple-300 mt-0.5 flex-shrink-0"></span>
                    <span><strong class="text-purple-700">Ungu</strong> — Risiko AI terdeteksi (YES) pada siswa Borderline/Abnormal. Perlu tindak lanjut segera.</span>
                </div>
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