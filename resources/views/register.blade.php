<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-sm p-8">
        <div class="flex items-center gap-2 mb-8">
            <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h1 class="text-base font-bold text-slate-800">Daftar Akun Siswa</h1>
        </div>

        @if($errors->any())
            <div class="bg-red-50 text-red-600 text-sm rounded-xl px-4 py-3 mb-5">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="/register" class="space-y-4">
            @csrf
            <div>
                <label class="text-sm font-medium text-slate-700 block mb-1.5">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama') }}" required
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Nama sesuai absen">
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700 block mb-1.5">Kelas</label>
                <input type="text" name="kelas" value="{{ old('kelas') }}" required
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Contoh: 8B">
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700 block mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="email@kamu.com">
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700 block mb-1.5">Password</label>
                <input type="password" name="password" required
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Minimal 6 karakter">
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700 block mb-1.5">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Ulangi password">
            </div>
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-xl text-sm transition-colors mt-2">
                Daftar Sekarang
            </button>
        </form>

        <p class="text-center text-sm text-slate-400 mt-5">
            Sudah punya akun?
            <a href="/login" class="text-blue-600 font-semibold hover:underline">Masuk</a>
        </p>
    </div>
</body>
</html>