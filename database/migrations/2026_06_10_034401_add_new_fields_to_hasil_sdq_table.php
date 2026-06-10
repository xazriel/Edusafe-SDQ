<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('hasil_sdq', function (Blueprint $table) {
            $table->string('hasil_naive_bayes')->nullable()->after('prob_berisiko');
            $table->float('prob_normal')->nullable()->after('hasil_naive_bayes');
            $table->float('prob_borderline')->nullable()->after('prob_normal');
            $table->float('prob_high_risk')->nullable()->after('prob_borderline');
            $table->string('keputusan_akhir')->nullable()->after('prob_high_risk');
            $table->text('tindakan')->nullable()->after('keputusan_akhir');
            $table->float('akurasi_model')->nullable()->after('tindakan');
            $table->float('cv_score')->nullable()->after('akurasi_model');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hasil_sdq', function (Blueprint $table) {
            $table->dropColumn([
                'hasil_naive_bayes',
                'prob_normal',
                'prob_borderline',
                'prob_high_risk',
                'keputusan_akhir',
                'tindakan',
                'akurasi_model',
                'cv_score',
            ]);
        });
    }
};
