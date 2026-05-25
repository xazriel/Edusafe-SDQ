<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuesioner SDQ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        input[type=radio] { accent-color: #2563eb; width: 15px; height: 15px; cursor: pointer; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">

    <div class="max-w-2xl mx-auto px-4 py-10 space-y-5">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-2">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-white" style="font-size:18px">psychology</span>
                </div>
                <h1 class="text-base font-bold text-slate-800">Counselor Portal</h1>
            </div>
            <a href="/dashboard-siswa" class="flex items-center gap-1 text-sm text-blue-600 hover:underline font-medium">
                <span class="material-symbols-outlined" style="font-size:16px">arrow_back</span>
                Dashboard
            </a>
        </div>

        {{-- Title --}}
        <div class="bg-white rounded-2xl shadow-sm p-7">
            <h2 class="text-xl font-bold text-slate-800 mb-1">📋 Kuesioner SDQ</h2>
            <p class="text-sm text-slate-400">Strengths and Difficulties Questionnaire — Standar Internasional untuk usia 11–17 tahun</p>
            <div class="mt-4 p-4 bg-blue-50 rounded-xl border border-blue-100">
                <p class="text-sm text-blue-700 font-medium">Petunjuk: Pilih jawaban yang paling sesuai dengan kondisimu selama 6 bulan terakhir.</p>
            </div>
        </div>

        {{-- Form --}}
        <form method="POST" action="/sdq/submit" class="space-y-5">
            @csrf

            @php
            $soal = [
                1  => "Saya berusaha bersikap baik kepada orang lain",
                2  => "Saya tidak bisa diam, saya terlalu aktif",
                3  => "Saya sering sakit kepala, sakit perut, atau sakit lainnya",
                4  => "Saya berbagi dengan orang lain",
                5  => "Saya sering marah dan tidak bisa mengendalikan emosi",
                6  => "Saya lebih suka menyendiri daripada bersama orang lain",
                7  => "Saya biasanya melakukan apa yang diperintahkan",
                8  => "Saya banyak khawatir",
                9  => "Saya membantu jika seseorang terluka, sedih, atau sakit",
                10 => "Saya tidak bisa duduk diam dengan tenang",
                11 => "Saya punya setidaknya satu teman baik",
                12 => "Saya sering berkelahi",
                13 => "Saya sering merasa tidak bahagia, sedih, atau menangis",
                14 => "Orang lain pada umumnya menyukai saya",
                15 => "Saya mudah teralihkan perhatiannya",
                16 => "Saya merasa gugup dalam situasi baru",
                17 => "Saya bersikap baik kepada anak yang lebih muda",
                18 => "Saya sering dituduh berbohong",
                19 => "Saya diganggu atau diintimidasi oleh orang lain",
                20 => "Saya sering menawarkan diri untuk membantu orang lain",
                21 => "Saya berpikir sebelum melakukan sesuatu",
                22 => "Saya mengambil barang yang bukan milik saya",
                23 => "Saya lebih cocok bergaul dengan orang dewasa daripada anak seusia saya",
                24 => "Saya memiliki banyak ketakutan",
                25 => "Saya menyelesaikan pekerjaan sampai selesai",
            ];
            $subskala = [
                'Perilaku Prososial'    => ['nomor' => [1,4,9,17,20],  'icon' => 'favorite',      'color' => 'text-emerald-600', 'bg' => 'bg-emerald-50',  'border' => 'border-emerald-200'],
                'Hiperaktivitas'        => ['nomor' => [2,10,15,21,25], 'icon' => 'bolt',          'color' => 'text-amber-600',   'bg' => 'bg-amber-50',    'border' => 'border-amber-200'],
                'Gejala Emosional'      => ['nomor' => [3,8,13,16,24],  'icon' => 'sentiment_sad', 'color' => 'text-blue-600',    'bg' => 'bg-blue-50',     'border' => 'border-blue-200'],
                'Masalah Teman Sebaya'  => ['nomor' => [6,11,14,19,23], 'icon' => 'group',         'color' => 'text-orange-600',  'bg' => 'bg-orange-50',   'border' => 'border-orange-200'],
                'Masalah Perilaku'      => ['nomor' => [5,7,12,18,22],  'icon' => 'warning',       'color' => 'text-violet-600',  'bg' => 'bg-violet-50',   'border' => 'border-violet-200'],
            ];
            @endphp

            @foreach($subskala as $nama => $info)
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                {{-- Subskala Header --}}
                <div class="flex items-center gap-2 px-6 py-4 {{ $info['bg'] }} border-b {{ $info['border'] }}">
                    <span class="material-symbols-outlined {{ $info['color'] }}" style="font-size:18px">{{ $info['icon'] }}</span>
                    <span class="text-sm font-bold {{ $info['color'] }}">{{ $nama }}</span>
                </div>

                {{-- Soal --}}
                <div class="divide-y divide-slate-50">
                    @foreach($info['nomor'] as $n)
                    <div class="px-6 py-4">
                        <p class="text-sm text-slate-700 mb-3 font-medium">
                            <span class="text-slate-400 mr-1">{{ $n }}.</span>
                            {{ $soal[$n] }}
                        </p>
                        <div class="flex gap-4">
                            @foreach(['0' => 'Tidak Benar', '1' => 'Agak Benar', '2' => 'Benar'] as $val => $teks)
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="sdq{{ $n }}" value="{{ $val }}" required
                                    class="mt-0.5">
                                <span class="text-xs text-slate-500 group-hover:text-slate-800 transition-colors">{{ $teks }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach

            {{-- Submit --}}
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl text-sm transition-colors flex items-center justify-center gap-2">
                <span class="material-symbols-outlined" style="font-size:18px">send</span>
                Lihat Hasil SDQ
            </button>

        </form>

        <footer class="pt-4 border-t border-slate-100 flex justify-between items-center opacity-50">
            <p class="text-xs text-slate-400">© 2025 Counselor Portal • Sistem Klasifikasi Shania</p>
            <p class="text-xs text-slate-400">SDQ © Robert Goodman</p>
        </footer>

    </div>
</body>
</html>