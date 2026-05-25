<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kuesioner Shania</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial; max-width: 620px; margin: 50px auto; padding: 20px; background: #F8FAFC; }
        h1 { color: #4F46E5; margin-bottom: 8px; }
        p.sub { color: #64748B; margin-bottom: 24px; font-size: 14px; }
        .card { background: white; border-radius: 12px; padding: 24px; margin-bottom: 16px; box-shadow: 0 1px 4px rgba(0,0,0,0.08); }
        .card h3 { margin-bottom: 16px; color: #1E293B; font-size: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 6px; color: #334155; font-size: 14px; }
        input, select { width: 100%; padding: 10px; font-size: 14px; border: 1px solid #CBD5E1; border-radius: 8px; }
        input:focus, select:focus { outline: none; border-color: #4F46E5; }
        .row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .pertanyaan { margin-bottom: 16px; }
        button { width: 100%; margin-top: 8px; padding: 14px; background: #4F46E5; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: bold; }
        button:hover { background: #4338CA; }
    </style>
</head>
<div style="text-align:right; padding: 10px 20px; background: white; border-bottom: 1px solid #E2E8F0;">
    <span style="font-size:13px; color:#64748B; margin-right:12px;">
        👤 {{ Auth::user()->name }}
    </span>
    <form method="POST" action="/logout" style="display:inline;">
        @csrf
        <button type="submit" style="padding:6px 14px; background:#EF4444; color:white; border:none; border-radius:6px; cursor:pointer; font-size:13px;">
            Logout
        </button>
    </form>
</div>
<body>
    <h1>🧠 Kuesioner Kesehatan Mental</h1>
    <p class="sub">Jawab dengan jujur sesuai kondisi yang kamu rasakan. Skala: 1 = Tidak Pernah, 4 = Selalu</p>

    <form method="POST" action="/predict">
        @csrf

        <div class="card">
            <h3>📋 Data Diri</h3>
            <div class="row">
                <div>
                    <label>Nama</label>
                    <input type="text" name="nama" placeholder="Nama lengkap" required>
                </div>
                <div>
                    <label>Kelas</label>
                    <input type="text" name="kelas" placeholder="Contoh: 8A" required>
                </div>
            </div>
        </div>

        <div class="card">
            <h3>📝 Pertanyaan</h3>

            <div class="pertanyaan">
                <label>1. Saya merasa sulit berkonsentrasi saat belajar</label>
                <select name="q1">
                    <option value="1">1 - Tidak Pernah</option>
                    <option value="2">2 - Kadang-kadang</option>
                    <option value="3">3 - Sering</option>
                    <option value="4">4 - Selalu</option>
                </select>
            </div>

            <div class="pertanyaan">
                <label>2. Saya merasa cemas atau khawatir berlebihan</label>
                <select name="q2">
                    <option value="1">1 - Tidak Pernah</option>
                    <option value="2">2 - Kadang-kadang</option>
                    <option value="3">3 - Sering</option>
                    <option value="4">4 - Selalu</option>
                </select>
            </div>

            <div class="pertanyaan">
                <label>3. Saya merasa tidak bersemangat menjalani hari</label>
                <select name="q3">
                    <option value="1">1 - Tidak Pernah</option>
                    <option value="2">2 - Kadang-kadang</option>
                    <option value="3">3 - Sering</option>
                    <option value="4">4 - Selalu</option>
                </select>
            </div>

            <div class="pertanyaan">
                <label>4. Saya sulit tidur atau tidur terlalu banyak</label>
                <select name="q4">
                    <option value="1">1 - Tidak Pernah</option>
                    <option value="2">2 - Kadang-kadang</option>
                    <option value="3">3 - Sering</option>
                    <option value="4">4 - Selalu</option>
                </select>
            </div>

            <div class="pertanyaan">
                <label>5. Saya merasa kesulitan berinteraksi dengan orang lain</label>
                <select name="q5">
                    <option value="1">1 - Tidak Pernah</option>
                    <option value="2">2 - Kadang-kadang</option>
                    <option value="3">3 - Sering</option>
                    <option value="4">4 - Selalu</option>
                </select>
            </div>
        </div>

        <button type="submit">Lihat Hasil →</button>
    </form>
</body>


</html>