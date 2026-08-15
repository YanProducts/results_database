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
        Schema::table('project_planned_counts', function (Blueprint $table) {
            //main_idを親が消えたらそのデータ自体を消すことができるようにする＝こうしないと消せない
            // foreignkeyのみ消して付け加える
            $table->dropForeign(["main_id"]);
            $table->foreign("main_id")->references('id')->on('project_planned_counts')->cascadeOnDelete();
        });
        Schema::table('project_planned_count_imports', function (Blueprint $table) {
            //main_idを親が消えたらそのデータ自体を消すことができるようにする＝こうしないと消せない
            // foreignkeyのみ消して付け加える
            $table->dropForeign(["main_id"]);
            $table->foreign("main_id")->references('id')->on('project_planned_count_imports')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_planned_counts', function (Blueprint $table) {
            $table->dropForeign("main_id");
            $table->foreign("main_id")->references('id')->on('project_planned_counts');
        });
        Schema::table('project_planned_count_imports', function (Blueprint $table) {
            $table->dropForeign("main_id");
            $table->foreign("main_id")->references('id')->on('project_planned_count_imports');
        });
    }
};
