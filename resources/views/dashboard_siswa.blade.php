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

        {{-- Hasil Utama --}}
        @php
            $finalLabel = $hasilTerakhir->keputusan_akhir ?? $hasilTerakhir->hasil_label;
            $displayLabel = $finalLabel === 'High Risk' ? 'Abnormal' : $finalLabel;

            $sdqColor = match($displayLabel) {
                'Normal'     => 'bg-emerald-50 text-emerald-700 border-emerald-200 border-t-emerald-500',
                'Borderline' => 'bg-amber-50 text-amber-700 border-amber-200 border-t-amber-500',
                'Abnormal', 'High Risk' => 'bg-red-50 text-red-700 border-red-200 border-t-red-500',
                default      => 'bg-slate-50 text-slate-600 border-slate-200 border-t-slate-500'
            };
            $sdqIcon = match($displayLabel) {
                'Normal'     => 'check_circle',
                'Borderline' => 'warning',
                'Abnormal', 'High Risk' => 'error',
                default      => 'info'
            };
            $deskripsiText = match($displayLabel) {
                'Normal'     => 'Hasil skrining terakhir Anda menunjukkan kondisi yang normal dan stabil. Tetap pertahankan dan selalu jaga kesehatan emosi serta hubungan sosial Anda sehari-hari.',
                'Borderline' => 'Hasil skrining terakhir Anda menunjukkan kondisi borderline. Guru BK akan memantau perkembangan Anda dan siap membantu jika Anda memerlukan dukungan.',
                'Abnormal', 'High Risk' => 'Hasil skrining terakhir Anda menunjukkan kondisi yang memerlukan perhatian lebih. Guru BK akan segera menghubungi Anda untuk mendampingi dan memberikan dukungan terbaik.',
                default      => '-'
            };
        @endphp

        <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 border-t-4 {{ $sdqColor }} flex flex-col items-center text-center">
            <span class="material-symbols-outlined text-5xl mb-3 {{ $displayLabel === 'Normal' ? 'text-emerald-500' : ($displayLabel === 'Borderline' ? 'text-amber-500' : 'text-red-500') }}">{{ $sdqIcon }}</span>
            <h3 class="text-sm font-bold text-slate-400 mb-1">Status Skrining Terakhir</h3>
            <p class="text-3xl font-extrabold text-slate-800 mb-3">{{ $displayLabel }}</p>
            <p class="text-sm text-slate-500 max-w-md leading-relaxed mb-4">{{ $deskripsiText }}</p>
            <div class="text-xs text-slate-400 p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm">info</span>
                Rincian analitik lengkap disimpan pada Counselor Portal untuk evaluasi bimbingan konseling.
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
                <table class="w-full text-sm" style="min-width:400px">
                    <thead>
                        <tr class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wide">
                            <th class="px-4 md:px-5 py-3 text-left font-semibold">Tanggal</th>
                            <th class="px-4 md:px-5 py-3 text-left font-semibold">Hasil Diagnosis</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($riwayat as $r)
                        @php
                            $histLabel = $r->keputusan_akhir ?? $r->hasil_label;
                            $dispHistLabel = $histLabel === 'High Risk' ? 'Abnormal' : $histLabel;
                            $rc = match($dispHistLabel) {
                                'Normal'     => 'bg-emerald-100 text-emerald-700',
                                'Borderline' => 'bg-amber-100 text-amber-700',
                                'Abnormal', 'High Risk' => 'bg-red-100 text-red-700',
                                default      => 'bg-slate-100 text-slate-600'
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/60 {{ $loop->first ? 'bg-blue-50/30' : '' }}">
                            <td class="px-4 md:px-5 py-3.5 text-slate-500 text-xs">
                                {{ $r->created_at->format('d M Y H:i') }}
                                @if($loop->first) <span class="ml-1.5 text-blue-600 font-bold">(Terbaru)</span> @endif
                            </td>
                            <td class="px-4 md:px-5 py-3.5">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $rc }}">{{ $dispHistLabel }}</span>
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