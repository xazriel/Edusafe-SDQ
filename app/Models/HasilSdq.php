<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilSdq extends Model
{
    protected $table = 'hasil_sdq';

    protected $fillable = [
        'nama', 'kelas',
        'sdq1','sdq2','sdq3','sdq4','sdq5',
        'sdq6','sdq7','sdq8','sdq9','sdq10',
        'sdq11','sdq12','sdq13','sdq14','sdq15',
        'sdq16','sdq17','sdq18','sdq19','sdq20',
        'sdq21','sdq22','sdq23','sdq24','sdq25',
        'skor_emotional','skor_conduct',
        'skor_hyperactivity','skor_peer','skor_prosocial',
        'total_kesulitan','hasil_label',
        'samuel_depresi','samuel_kecemasan',
        'samuel_kesejahteraan','samuel_kelompok',
        'risiko_ai','prob_berisiko',
    ];
}