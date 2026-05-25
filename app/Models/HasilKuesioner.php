<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilKuesioner extends Model
{
    protected $table = 'hasil_kuesioner';

    protected $fillable = [
        'nama', 'kelas',
        'q1', 'q2', 'q3', 'q4', 'q5',
        'total_skor', 'hasil_label',
        'prob_normal', 'prob_perhatian', 'prob_penanganan',
    ];
}