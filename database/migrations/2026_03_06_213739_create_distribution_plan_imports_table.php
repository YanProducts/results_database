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


            // 案件名(同じものも違うものも全て一時保存)＝後にmigrationで付け足す
            // 過去の案件と名前が重なっているものは、そのうちの最新のデータの案件id(必ずしも登録とは限らない)=nullableを後につけたし
            $table->foreignId("project_id")->constrained("projects");

            // 営業所
            $table->foreignId("place_id")->constrained("places");
            // 開始日
            $table->date("start_date");
            // 終了日
            $table->date("end_date");
            // 住所
            $table->foreignId('address_id')->constrained('addresses');
            //備考
            $table->string("remark_from_operator")->nullable();
            // 外部連動されている場合は外部キー(その町目についてOKの場合はnull、重なっていたらid記入。操作途中で親が削除されたらnullになる)
            // =後に存在確認に変更！
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
