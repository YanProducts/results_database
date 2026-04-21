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
        Schema::create('distribution_assign_imports', function (Blueprint $table) {
            $table->id();

            // 町目や案件のデータはplanに入っていて、plan_idが消されたら消去される
            // このplan_idが過去のものと重なっているので、確認しにきている
            $table->foreignId("plan_id")->constrained("distribution_plans");
            $table->foreignId("staff_id")->constrained("field_staff_lists");
            // 複数日で判断して配布できる場合はend_dataをつける
            $table->date("date");
            // 単日配布の場合はnullable
            $table->date("end_date")->nullable();
            // 挿入した人
            $table->foreignId("created_by")->constrained("user_auths");

            // plan_idとstaff_idとdateのセットは必ず1つ（1回で書けやというやつ）
            // このセットはImportでもunique
            $table->unique(["plan_id","staff_id","date"],"plan_staff_date_set");

            // 配布前確認なので状態は変わらず0

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribution_assign_imports');
    }
};
