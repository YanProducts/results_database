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
        // 配布計画を作成
        Schema::create('distribution_plans', function (Blueprint $table) {
            $table->id();

            // 案件
            $table->foreignId("projectId")->constrained("projects");
            // 同案件フラグナンバー（同じ案件が複数回続けば増えていく）
            $table->unsignedInteger("same_project_flug")->default(0);
            // 営業所
            $table->foreignId("placeId")->constrained("places");
            // 開始日
            $table->date("start_date");
            // 終了日
            $table->date("end_date");
            // 町目
            $table->foreignId('addressesId')->constrained('addresses');
            //備考
            $table->string("remark_from_operator")->nullable();
            // 案件、同案件フラグ、町目、営業所がセットになっているのは1つのみ（1つの町目を営業所で仕分けている場合も考え、営業所もユニークにセットする）
            $table->unique(["projectId","same_project_flug","addressesId","placeId"],"unique_distribution_plan_sets");
            // 検索用
            $table->index(["addressesId"]);
            $table->index(["projectId"]);
            $table->index(["placeId"]);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribution_plans');
    }
};
