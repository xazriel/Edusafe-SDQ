<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Klasifikasi</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial; max-width: 620px; margin: 50px auto; padding: 20px; background: #F8FAFC; }
        h1 { text-align: center; color: #1E293B; margin-bottom: 24px; }

        .hasil-box { padding: 30px; border-radius: 12px; margin-bottom: 24px; text-align: center; }
        .normal     { background: #D1FAE5; border: 2px solid #10B981; color: #065F46; }
        .perhatian  { background: #FEF3C7; border: 2px solid #F59E0B; color: #92400E; }
        .penanganan { background: #FEE2E2; border: 2px solid #EF4444; color: #991B1B; }
        .hasil-box h2 { font-size: 28px; margin-bottom: 8px; }
        .hasil-box p  { font-size: 15px; }

        .prob-section { background: white; border-radius: 12px; padding: 24px; margin-bottom: 24px; box-shadow: 0 1px 4px rgba(0,0,0,0.08); }
        .prob-section h3 { margin-bottom: 16px; color: #1E293B; font-size: 16px; }
        .prob-item { margin-bottom: 14px; }
        .prob-label { display: flex; justify-content: space-between; font-size: 14px; margin-bottom: 4px; color: #475569; }
        .prob-bar-bg { background: #E2E8F0; border-radius: 99px; height: 12px; }
        .prob-bar { height: 12px; border-radius: 99px; transition: width 0.6s ease; }
        .bar-normal     { background: #10B981; }
        .bar-perhatian  { background: #F59E0B; }
        .bar-penanganan { background: #EF4444; }

        .btn { display: block; text-align: center; margin-top: 10px; padding: 12px; background: #4F46E5; color: white; border-radius: 8px; text-decoration: none; font-size: 15px; }
        .btn:hover { background: #4338CA; }
    </style>
</head>
<body>
    <h1>📊 Hasil Klasifikasi</h1>

    @php
        $label = $hasil['label'];
        $prob  = $hasil['probabilitas'];

        $kelas = match($label) {
            'Normal'           => 'normal',
            'Perlu Perhatian'  => 'perhatian',
            'Perlu Penanganan' => 'penanganan',
            default            => 'normal'
        };
        $emoji = match($label) {
            'Normal'           => '✅',
            'Perlu Perhatian'  => '⚠️',
            'Perlu Penanganan' => '🔴',
            default            => '✅'
        };
        $deskripsi = match($label) {
            'Normal'           => 'Kondisimu baik. Pertahankan pola hidup sehat!',
            'Perlu Perhatian'  => 'Ada beberapa hal yang perlu diperhatikan. Pertimbangkan untuk berbicara dengan orang yang dipercaya.',
            'Perlu Penanganan' => 'Disarankan untuk segera berkonsultasi dengan tenaga profesional.',
            default            => ''
        };
    @endphp

    <div class="hasil-box {{ $kelas }}">
        <h2>{{ $emoji }} {{ $label }}</h2>
        <p>{{ $deskripsi }}</p>
    </div>

    <div class="prob-section">
        <h3>📈 Tingkat Probabilitas</h3>

        <div class="prob-item">
            <div class="prob-label">
                <span>✅ Normal</span>
                <span>{{ $prob['Normal'] }}%</span>
            </div>
            <div class="prob-bar-bg">
                <div class="prob-bar bar-normal" style="width: {{ $prob['Normal'] }}%"></div>
            </div>
        </div>

        <div class="prob-item">
            <div class="prob-label">
                <span>⚠️ Perlu Perhatian</span>
                <span>{{ $prob['Perlu Perhatian'] }}%</span>
            </div>
            <div class="prob-bar-bg">
                <div class="prob-bar bar-perhatian" style="width: {{ $prob['Perlu Perhatian'] }}%"></div>
            </div>
        </div>

        <div class="prob-item">
            <div class="prob-label">
                <span>🔴 Perlu Penanganan</span>
                <span>{{ $prob['Perlu Penanganan'] }}%</span>
            </div>
            <div class="prob-bar-bg">
                <div class="prob-bar bar-penanganan" style="width: {{ $prob['Perlu Penanganan'] }}%"></div>
            </div>
        </div>
    </div>

    <a class="btn" href="/">← Isi Ulang Kuesioner</a>
</body>
</html>