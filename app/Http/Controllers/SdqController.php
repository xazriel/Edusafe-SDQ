<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\HasilSdq;
use Illuminate\Support\Facades\Auth;

class SdqController extends Controller
{
    public function index()
    {
    $user = Auth::user();

    $sudahIsi = HasilSdq::where('nama', $user->name)
                        ->where('kelas', $user->kelas)
                        ->exists();

    if ($sudahIsi) {
        return redirect('/dashboard-siswa')->with('info', 'Kamu sudah pernah mengisi kuesioner SDQ.');
    }

    return view('sdq');
    }

    public function submit(Request $request)
    {
        $jawaban = [];
        for ($i = 1; $i <= 25; $i++) {
            $jawaban[$i] = (int) $request->input("sdq$i", 0);
        }

        // Reverse scoring
        $reverse = [7, 11, 14, 21, 25];
        foreach ($reverse as $r) {
            $jawaban[$r] = 2 - $jawaban[$r];
        }

        // Hitung subskala
        $emotional     = $jawaban[3]+$jawaban[8]+$jawaban[13]+$jawaban[16]+$jawaban[24];
        $conduct       = $jawaban[5]+$jawaban[7]+$jawaban[12]+$jawaban[18]+$jawaban[22];
        $hyperactivity = $jawaban[2]+$jawaban[10]+$jawaban[15]+$jawaban[21]+$jawaban[25];
        $peer          = $jawaban[6]+$jawaban[11]+$jawaban[14]+$jawaban[19]+$jawaban[23];
        $prosocial     = $jawaban[1]+$jawaban[4]+$jawaban[9]+$jawaban[17]+$jawaban[20];

        $total = $emotional + $conduct + $hyperactivity + $peer;

        // Klasifikasi SDQ (Jalur 1 — tetap di Laravel, tidak perlu Flask)
        if ($total <= 15) {
            $label = 'Normal';
        } elseif ($total <= 19) {
            $label = 'Borderline';
        } else {
            $label = 'Abnormal';
        }

        // Panggil Flask /sdq (Jalur 2 — Naive Bayes Samuel)
        try {
            $response = Http::timeout(5)->post('http://127.0.0.1:5000/sdq', [
                'emotional'     => $emotional,
                'hyperactivity' => $hyperactivity,
                'conduct'       => $conduct,
                'peer'          => $peer,
                'prosocial'     => $prosocial,
                'total'         => $total,
            ]);
            $samuel = $response->json();
        } catch (\Exception $e) {
            $samuel = [
                'depresi'         => 'Tidak tersedia',
                'kecemasan'       => 'Tidak tersedia',
                'kesejahteraan'   => 'Tidak tersedia',
                'gejala_negatif'  => 'Tidak tersedia',
                'risiko_ai'       => 'Tidak tersedia',
                'prob_berisiko'   => null,
            ];
        }

        // Simpan ke database
        $data = [
            'nama'  => Auth::user()->name,
            'kelas' => Auth::user()->kelas,
        ];
        for ($i = 1; $i <= 25; $i++) {
            $data["sdq$i"] = $jawaban[$i];
        }
        $data['skor_emotional']       = $emotional;
        $data['skor_conduct']         = $conduct;
        $data['skor_hyperactivity']   = $hyperactivity;
        $data['skor_peer']            = $peer;
        $data['skor_prosocial']       = $prosocial;
        $data['total_kesulitan']      = $total;
        $data['hasil_label']          = $label;

        // Label detail (informatif)
        $data['samuel_depresi']       = $samuel['depresi'] ?? null;
        $data['samuel_kecemasan']     = $samuel['kecemasan'] ?? null;
        $data['samuel_kesejahteraan'] = $samuel['kesejahteraan'] ?? null;
        $data['samuel_kelompok']      = $samuel['gejala_negatif'] ?? null;

        // Hasil Naive Bayes (baru)
        $data['risiko_ai']            = $samuel['risiko_ai'] ?? null;
        $data['prob_berisiko']        = $samuel['prob_berisiko'] ?? null;

        HasilSdq::create($data);

        return view('hasil_sdq', compact(
            'label', 'total',
            'emotional', 'conduct', 'hyperactivity', 'peer', 'prosocial',
            'samuel'
        ));
    }
}