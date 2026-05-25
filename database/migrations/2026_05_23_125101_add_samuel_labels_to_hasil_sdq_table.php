<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hasil_sdq', function (Blueprint $table) {
            $table->string('samuel_depresi')->nullable()->after('hasil_label');
            $table->string('samuel_kecemasan')->nullable()->after('samuel_depresi');
            $table->string('samuel_kesejahteraan')->nullable()->after('samuel_kecemasan');
            $table->string('samuel_kelompok')->nullable()->after('samuel_kesejahteraan');
        });
    }

    public function down(): void
    {
        Schema::table('hasil_sdq', function (Blueprint $table) {
            $table->dropColumn([
                'samuel_depresi',
                'samuel_kecemasan',
                'samuel_kesejahteraan',
                'samuel_kelompok',
            ]);
        });
    }
};