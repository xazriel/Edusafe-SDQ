<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\HasilKuesioner;

class KuesionerController extends Controller
{
    public function index()
    {
        return view('kuesioner');
    }

    public function predict(Request $request)
    {
        $jawaban = [
            (int) $request->q1,
            (int) $request->q2,
            (int) $request->q3,
            (int) $request->q4,
            (int) $request->q5,
        ];

        try {
            $response = Http::timeout(5)->post('http://127.0.0.1:5000/predict', [
                'jawaban' => $jawaban,
            ]);

            $hasil = $response->json();

            // Simpan ke database
            HasilKuesioner::create([
                'nama'             => $request->nama,
                'kelas'            => $request->kelas,
                'q1'               => $jawaban[0],
                'q2'               => $jawaban[1],
                'q3'               => $jawaban[2],
                'q4'               => $jawaban[3],
                'q5'               => $jawaban[4],
                'total_skor'       => array_sum($jawaban),
                'hasil_label'      => $hasil['label'],
                'prob_normal'      => $hasil['probabilitas']['Normal'],
                'prob_perhatian'   => $hasil['probabilitas']['Perlu Perhatian'],
                'prob_penanganan'  => $hasil['probabilitas']['Perlu Penanganan'],
            ]);

            return view('hasil', [
                'hasil' => $hasil,
                'nama'  => $request->nama,
                'kelas' => $request->kelas,
            ]);

        } catch (\Exception $e) {
            return response('ERROR: ' . $e->getMessage(), 500);
        }
    }
}