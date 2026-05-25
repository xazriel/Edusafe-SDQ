<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_kuesioner', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();
            $table->string('kelas')->nullable();
            $table->integer('q1');
            $table->integer('q2');
            $table->integer('q3');
            $table->integer('q4');
            $table->integer('q5');
            $table->integer('total_skor');
            $table->string('hasil_label');
            $table->float('prob_normal');
            $table->float('prob_perhatian');
            $table->float('prob_penanganan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_kuesioner');
    }
};