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
        Schema::table('distribution_plans', function (Blueprint $table) {
            //どのUserが登録したか
            $table->foreignId("created_by")->constrained("user_auths");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distribution_plans', function (Blueprint $table) {
            // 先に外部依存を解いてから消去
            $table->dropForeign(['created_by']);
            $table->dropColumn("created_by");
        });
    }
};
