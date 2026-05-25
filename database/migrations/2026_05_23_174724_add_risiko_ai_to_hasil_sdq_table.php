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
        $table->string('risiko_ai')->nullable()->after('samuel_kelompok');
        $table->float('prob_berisiko')->nullable()->after('risiko_ai');
    });
}

public function down(): void
{
    Schema::table('hasil_sdq', function (Blueprint $table) {
        $table->dropColumn(['risiko_ai', 'prob_berisiko']);
    });
}
};
