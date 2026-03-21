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
        Schema::table('project_imports', function (Blueprint $table) {
            //project_importsのchange_flagをautomatic_change_flagに変更
            // 意味合いも「期限内の自動更新フラグ」に変更する(end_dateのみ変更するため)
            // とはいえ、現状使用しない可能性も多々あり
            $table->renameColumn("change_flag","automatic_change_flag");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_imports', function (Blueprint $table) {
            $table->renameColumn("automatic_change_flag",   "change_flag");
        });
    }
};
