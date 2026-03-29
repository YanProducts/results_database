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
        // 配布予定に地図の番号のカラム追加
        Schema::table('distribution_plans', function (Blueprint $table) {
            //
            $table->unsignedInteger("map_number")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distribution_plans', function (Blueprint $table) {
            //
            $table->dropColumn("map_number");
        });
    }
};
