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
        // 配布予定の重複確認テーブルにメイン案件のidを追加(併配用)、自分自身から取得
        // 親案件はnull
        Schema::table('distribution_plan_imports', function (Blueprint $table) {
            $table->foreignId("main_id")->nullable()->constrained("distribution_plan_imports")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distribution_plan_imports', function (Blueprint $table) {
            $table->dropConstrainedForeignId('main_id');
        });
    }
};
