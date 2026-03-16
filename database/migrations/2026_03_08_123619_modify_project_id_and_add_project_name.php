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
        Schema::table('distribution_plan_imports', function (Blueprint $table) {
            //配布プランの一時保存のproject_idをnullableにして、project_nameを付け足す
            // 「foreindIdはcreateしてから外部と接続する処理をする」という構造になるので、changeしたい場合は別途定義を行う必要がある
            $table->unsignedBigInteger("project_id")->nullable()->change();

            // foreignを削除して、foreinIdではなくforeignでIdと接続
            $table->dropForeign(['project_id']);
            $table->foreign('project_id')
                    ->references('id')
                    ->on('projects')
                    // 削除されたら同時に削除だが、そもそもnull(プロジェクト自体も準備段階)の場合はnullのまま
                    ->cascadeOnDelete()
                    ->cascadeOnUpdate();

            $table->string("project_name");

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distribution_plan_imports', function (Blueprint $table) {
            //
            $table->foreignId("project_id")->constrained("projects");
            $table->dropColumn("project_name");
        });
    }
};
