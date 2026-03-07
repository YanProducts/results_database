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
        // 同じ案件、同じ町目が既に存在する時(町目を営業所間で分けた時)
        Schema::create('distribution_plan_imports', function (Blueprint $table) {
            $table->id();

           // 案件(外部キーはdistribution_plansと)
            $table->foreignId("project_id")->constrained("projects");
            // 同町目フラグナンバー（「同じ案件＆町目で異なる営業所」が複数回続けば増えていく）＝ここが複数なら必ずアラートが出るようにする
            // $table->unsignedInteger("same_project_flag")->default(0);
            // 営業所
            $table->foreignId("place_id")->constrained("places");
            // 開始日
            $table->date("start_date");
            // 終了日
            $table->date("end_date");
            // 町目
            $table->foreignId('address_id')->constrained('addresses');
            //備考
            $table->string("remark_from_operator")->nullable();
            // 外部連動されている場合は外部キー(その町目についてOKの場合はnull、重なっていたらid記入。操作途中で親が削除されたらnullになる)
            $table->foreignId("distribution_plan_id")->nullable()->constrained("distribution_plans")->nullOnDelete();
            $table->foreignId("distribution_record_id")->nullable()->constrained("distribution_records")->nullOnDelete();

            // 検索用
            $table->index(["address_id"]);
            $table->index(["project_id"]);
            $table->index(["place_id"]);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribution_plan_imports');
    }
};
