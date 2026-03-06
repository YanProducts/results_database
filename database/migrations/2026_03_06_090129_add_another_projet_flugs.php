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
        // プロジェクトテーブルにanother_project_flugを追加
        Schema::table('projects', function (Blueprint $table) {
            $table->unsignedInteger("another_project_flug")->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            //
            $table->dropColumn("another_project_flug");
        });
    }
};
