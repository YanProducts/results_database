<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // スタッフへの割り当てを案件ベースで行ったもの
    public function up(): void
    {
        Schema::create('distribution_project_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId("project_plan_id")->constrained("project_planned_counts");
            $table->foreignId("staff_id")->constrained("field_staff_lists");
            $table->date("date");
            $table->date("end_date")->nullable()->default(null);
            $table->foreignId("created_by")->constrained("user_auths"); //作った人はこのテーブルは営業所のみだが中には権限をまたぐ処理もあるのでuser_authsのidにする
            //結果データなし&日程まだ(0) & 結果データなし&
            // 今後の構造変更を考え(migrationを再設定必要になるなど)、Enumではなくstringで登録
            $table->unsignedInteger("status")->default(0);
            // project_plan_idとstaff_idとdateのセットは必ず1つ
            $table->unique(["project_plan_id","staff_id","date"],"project_staff_date_set");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribution_project_assignments');
    }
};
