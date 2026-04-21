<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // 営業所でスタッフに分割が終えたもの
    public function up(): void
    {
        Schema::create('distribution_assignments', function (Blueprint $table) {
            $table->id();
            // 町目や案件のデータはplanに入っていて、plan_idが消されたら消去される
            $table->foreignId("plan_id")->constrained("distribution_plans");
            $table->foreignId("staff_id")->constrained("field_staff_lists");
            // 複数日で判断して配布できる場合はend_dataをつける
            $table->date("date");
            // 単日配布の場合はnullable
            $table->date("end_date")->nullable();
            // 挿入した人
            $table->foreignId("created_by")->constrained("user_auths");
            //結果データなし&日程まだ(0) & 結果データなし&
            // 今後の構造変更を考え(migrationを再設定必要になるなど)、Enumではなくstringで登録
            $table->unsignedInteger("status")->default(0);
            // plan_idとstaff_idとdateのセットは必ず1つ（1回で書けやというやつ）
            $table->unique(["plan_id","staff_id","date"],"plan_staff_date_set");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribution_assignments');
    }
};
