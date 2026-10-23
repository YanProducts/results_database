<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // スタッフへの割り当てを案件ベースで行ったものの一時保存用
    public function up(): void
    {
        Schema::create('distribution_project_assignment_imports', function (Blueprint $table) {
            $table->id();
            $table->foreignId("project_plan_id")->constrained("project_planned_counts");
            $table->foreignId("staff_id")->constrained("field_staff_lists");
            $table->date("date");
            $table->date("end_date")->nullable()->default(null);
            $table->foreignId("created_by")->constrained("user_auths"); //作った人はこのテーブルは営業所のみだが中には権限をまたぐ処理もあるのでuser_authsのidにする

            // 必ず配布前なのでstatusは不要

            $table->unique(["project_plan_id","staff_id","date"],"project_staff_date_set");
            $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribution_project_assignment_imports');
    }
};
