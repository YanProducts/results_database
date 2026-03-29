<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // そのプロジェクトの何回目か
    // 登録時に同じ案件が複数ある場合などに取得
    public function up(): void
    {
        Schema::table('distribution_plans', function (Blueprint $table) {
            //
            $table->unsignedBigInteger("round_number")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distribution_plans', function (Blueprint $table) {
            //
            $table->dropColumn("round_number");
        });
    }
};
