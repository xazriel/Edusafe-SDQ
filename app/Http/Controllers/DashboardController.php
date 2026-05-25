<?php

namespace App\Http\Controllers;

use App\Models\HasilKuesioner;
use App\Models\HasilSdq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $hasilSdq       = HasilSdq::latest()->get();
        $hasilKuesioner = HasilKuesioner::latest()->get();

        return view('dashboard', compact('hasilSdq', 'hasilKuesioner'));
    }

    public function siswa()
    {
        $user    = Auth::user();
        $riwayat = HasilSdq::where('nama', $user->name)
                           ->where('kelas', $user->kelas)
                           ->orderBy('created_at', 'desc')
                           ->get();

        $hasilTerakhir = $riwayat->first();

        return view('dashboard_siswa', compact('hasilTerakhir', 'riwayat'));
    }

    public function riwayat(Request $request)
    {
        $query = HasilSdq::latest();

        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        if ($request->filled('status')) {
            $query->where('hasil_label', $request->status);
        }

        if ($request->filled('risiko_ai')) {
            $query->where('risiko_ai', $request->risiko_ai);
        }

        $hasilSdq    = $query->paginate(15)->withQueryString();
        $daftarKelas = HasilSdq::select('kelas')->distinct()->orderBy('kelas')->pluck('kelas');

        return view('riwayat', compact('hasilSdq', 'daftarKelas'));
    }

    public function detail($id)
{
    $s = HasilSdq::findOrFail($id);
    return view('riwayat_detail', compact('s'));
}
}