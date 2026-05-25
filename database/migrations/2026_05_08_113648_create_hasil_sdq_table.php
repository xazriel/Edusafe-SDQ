<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_sdq', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();
            $table->string('kelas')->nullable();

            // 25 jawaban SDQ (0 atau 1 atau 2)
            $table->integer('sdq1'); $table->integer('sdq2');
            $table->integer('sdq3'); $table->integer('sdq4');
            $table->integer('sdq5'); $table->integer('sdq6');
            $table->integer('sdq7'); $table->integer('sdq8');
            $table->integer('sdq9'); $table->integer('sdq10');
            $table->integer('sdq11'); $table->integer('sdq12');
            $table->integer('sdq13'); $table->integer('sdq14');
            $table->integer('sdq15'); $table->integer('sdq16');
            $table->integer('sdq17'); $table->integer('sdq18');
            $table->integer('sdq19'); $table->integer('sdq20');
            $table->integer('sdq21'); $table->integer('sdq22');
            $table->integer('sdq23'); $table->integer('sdq24');
            $table->integer('sdq25');

            // Skor per subskala
            $table->integer('skor_emotional');
            $table->integer('skor_conduct');
            $table->integer('skor_hyperactivity');
            $table->integer('skor_peer');
            $table->integer('skor_prosocial');
            $table->integer('total_kesulitan');

            // Hasil
            $table->string('hasil_label'); // Normal / Borderline / Abnormal
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_sdq');
    }
};