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

        // Hitung subskala baru sesuai pemetaan
        $emotional     = $jawaban[1]+$jawaban[3]+$jawaban[13]+$jawaban[16]+$jawaban[24];
        $conduct       = $jawaban[5]+$jawaban[7]+$jawaban[12]+$jawaban[18]+$jawaban[22];
        $hyperactivity = $jawaban[2]+$jawaban[10]+$jawaban[15]+$jawaban[21]+$jawaban[25];
        $peer          = $jawaban[6]+$jawaban[8]+$jawaban[11]+$jawaban[14]+$jawaban[19];
        $prosocial     = $jawaban[4]+$jawaban[9]+$jawaban[17]+$jawaban[20]+$jawaban[23];

        $total = $emotional + $conduct + $hyperactivity + $peer;

        // Klasifikasi SDQ (Jalur 1 — tetap di Laravel, tidak perlu Flask)
        if ($total <= 15) {
            $label = 'Normal';
        } elseif ($total <= 19) {
            $label = 'Borderline';
        } else {
            $label = 'Abnormal'; // Di UI/Laravel tetap sebut Abnormal demi kompatibilitas
        }

        // Panggil Flask /sdq (Jalur 2 — Naive Bayes Vietnam SDQ)
        try {
            $response = Http::timeout(5)->post('http://127.0.0.1:5000/sdq', [
                'emotional'     => $emotional,
                'hyperactivity' => $hyperactivity,
                'conduct'       => $conduct,
                'peer'          => $peer,
                'prosocial'     => $prosocial,
            ]);
            $samuel = $response->json();
        } catch (\Exception $e) {
            $samuel = [
                'total_skor'        => $total,
                'hasil_sdq_baku'    => $label === 'Abnormal' ? 'High Risk' : $label,
                'hasil_naive_bayes' => 'Tidak tersedia',
                'probabilitas'      => [
                    'Normal'     => 0.0,
                    'Borderline' => 0.0,
                    'High Risk'  => 0.0,
                ],
                'keputusan_akhir'   => $label === 'Abnormal' ? 'High Risk' : $label,
                'tindakan'          => $label === 'Normal' ? 'Tidak perlu tindakan khusus' : ($label === 'Borderline' ? 'Guru BK perlu memonitor siswa ini' : 'Tindakan segera diperlukan'),
                'akurasi_model'     => 0.0,
                'cv_score'          => 0.0,
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

        // Mapping model lama (samuel) - diisi null/default untuk record baru
        $data['samuel_depresi']       = null;
        $data['samuel_kecemasan']     = null;
        $data['samuel_kesejahteraan'] = null;
        $data['samuel_kelompok']      = null;

        // Kolom model baru (Vietnam SDQ)
        $data['hasil_naive_bayes']    = $samuel['hasil_naive_bayes'] ?? null;
        $data['prob_normal']          = $samuel['probabilitas']['Normal'] ?? null;
        $data['prob_borderline']      = $samuel['probabilitas']['Borderline'] ?? null;
        $data['prob_high_risk']       = $samuel['probabilitas']['High Risk'] ?? null;
        $data['keputusan_akhir']      = $samuel['keputusan_akhir'] ?? null;
        $data['tindakan']             = $samuel['tindakan'] ?? null;
        $data['akurasi_model']        = $samuel['akurasi_model'] ?? null;
        $data['cv_score']             = $samuel['cv_score'] ?? null;

        // Kompatibilitas filter Guru BK lama
        $data['risiko_ai']            = ($data['hasil_naive_bayes'] === 'Borderline' || $data['hasil_naive_bayes'] === 'High Risk') ? 'YES' : 'NO';
        $data['prob_berisiko']        = (float) (($data['prob_borderline'] ?? 0) + ($data['prob_high_risk'] ?? 0));

        HasilSdq::create($data);

        return view('hasil_sdq', compact(
            'label', 'total',
            'emotional', 'conduct', 'hyperactivity', 'peer', 'prosocial',
            'samuel'
        ));
    }
}