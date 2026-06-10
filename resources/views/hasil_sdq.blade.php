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
            $finalLabel = $samuel['keputusan_akhir'] ?? $label;
            // Map "High Risk" to "Abnormal" for student friendliness
            $displayLabel = $finalLabel === 'High Risk' ? 'Abnormal' : $finalLabel;

            $kelas = match($displayLabel) {
                'Normal'     => ['bg-emerald-50 border-emerald-200 border-t-emerald-500 text-emerald-700', 'text-emerald-700', '✅'],
                'Borderline' => ['bg-amber-50 border-amber-200 border-t-amber-500 text-amber-700',   'text-amber-700',   '⚠️'],
                'Abnormal', 'High Risk' => ['bg-red-50 border-red-200 border-t-red-500 text-red-700',         'text-red-700',     '🔴'],
                default      => ['bg-slate-50 border-slate-200 border-t-slate-500 text-slate-700',   'text-slate-700',   '📋'],
            };
            $deskripsi = match($displayLabel) {
                'Normal'     => 'Hasil menunjukkan kondisi kesehatan mental Anda dalam keadaan baik. Tetap pertahankan dan selalu jaga kesehatan emosi serta hubungan sosial Anda sehari-hari.',
                'Borderline' => 'Hasil menunjukkan adanya indikasi ringan yang perlu dipantau. Jangan ragu untuk berbagi cerita atau berkonsultasi secara santai dengan Guru BK jika merasa lelah atau cemas.',
                'Abnormal', 'High Risk'   => 'Hasil menunjukkan adanya hal-hal yang perlu mendapat perhatian lebih. Guru BK akan segera menghubungi Anda untuk mendampingi dan memberikan dukungan terbaik secara personal.',
                default      => ''
            };
        @endphp

        {{-- Hasil Utama --}}
        <div class="bg-white rounded-2xl shadow-sm p-10 text-center border-t-4 {{ $kelas[0] }}">
            <div class="text-5xl mb-4">{{ $kelas[2] }}</div>
            <h2 class="text-3xl font-extrabold text-slate-800 mb-3">
                Status: <span class="{{ $kelas[1] }}">{{ $displayLabel }}</span>
            </h2>
            <div class="max-w-md mx-auto">
                <p class="text-slate-600 text-sm leading-relaxed mb-6">{{ $deskripsi }}</p>
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-center gap-2 text-slate-500 text-xs">
                    <span class="material-symbols-outlined text-base">info</span>
                    Detail analisis lengkap telah dikirimkan ke Guru BK Anda untuk evaluasi lebih lanjut.
                </div>
            </div>
        </div>

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