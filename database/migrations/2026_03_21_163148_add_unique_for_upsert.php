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
        Schema::table('projects', function (Blueprint $table) {
            //upsert処理のために、projectsテーブルにuniqueを追加する
            // プロジェクト名+同案件フラグナンバーは一意に決まる
            $table->unique(["project_name","another_project_flag"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            //
            $table->dropUnique(["project_name","another_project_flag"]);
        });
    }
};
