<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Counselor Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-3xl bg-white rounded-3xl shadow-sm overflow-hidden flex min-h-[480px]">

        {{-- Panel Kiri --}}
        <div class="hidden md:flex w-72 flex-shrink-0 bg-blue-50 flex-col justify-between p-8">
            <div>
                <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center mb-6">
                    <span class="material-symbols-outlined text-white" style="font-size:20px">psychology</span>
                </div>
                <h1 class="text-lg font-bold text-blue-900 mb-1">Counselor Portal</h1>
                <p class="text-sm text-blue-500">Sistem Klasifikasi Shania</p>
            </div>

            <div class="space-y-5 border-t border-blue-200 pt-6">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-blue-500 mt-0.5" style="font-size:18px">bar_chart</span>
                    <div>
                        <p class="text-sm font-semibold text-blue-900">Analisis SDQ</p>
                        <p class="text-xs text-blue-500">Skrining kesehatan mental siswa</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-blue-500 mt-0.5" style="font-size:18px">smart_toy</span>
                    <div>
                        <p class="text-sm font-semibold text-blue-900">Deteksi AI</p>
                        <p class="text-xs text-blue-500">Identifikasi risiko tersembunyi</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-blue-500 mt-0.5" style="font-size:18px">clinical_notes</span>
                    <div>
                        <p class="text-sm font-semibold text-blue-900">Riwayat Skrining</p>
                        <p class="text-xs text-blue-500">Pantau perkembangan siswa</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Panel Kanan --}}
        <div class="flex-1 flex flex-col justify-center px-8 py-10">

            {{-- Mobile logo --}}
            <div class="flex items-center gap-2 mb-8 md:hidden">
                <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-white" style="font-size:18px">psychology</span>
                </div>
                <h1 class="text-base font-bold text-slate-800">Counselor Portal</h1>
            </div>

            <h2 class="text-2xl font-bold text-slate-800 mb-1">Selamat datang</h2>
            <p class="text-sm text-slate-400 mb-8">Masuk untuk melanjutkan</p>

            @if($errors->any())
                <div class="bg-red-50 text-red-600 text-sm rounded-xl px-4 py-3 mb-5 flex items-center gap-2">
                    <span class="material-symbols-outlined" style="font-size:16px">error</span>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="/login" class="space-y-4">
                @csrf
                <div>
                    <label class="text-sm font-medium text-slate-600 block mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        placeholder="email@kamu.com"
                        class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-colors">
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-600 block mb-1.5">Password</label>
                    <input type="password" name="password" required
                        placeholder="Password kamu"
                        class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-colors">
                </div>
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white font-semibold py-2.5 rounded-xl text-sm transition-all mt-2">
                    Masuk
                </button>
            </form>

            <p class="text-center text-sm text-slate-400 mt-6">
                Belum punya akun?
                <a href="/register" class="text-blue-600 font-semibold hover:underline">Daftar di sini</a>
            </p>
        </div>
    </div>

</body>
</html>