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
        // その日に営業所ごとに振られた案件ごと設定部数を取得するテーブル
        Schema::create('project_planned_counts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId("project_id")->constrained("projects"); //project_nameなら同案件フラグは変わるが、project_idなら同じ。idが同じなら同じ案件で、複数回取得されるときははround_numberで取得される
            $table->foreignId("place_id")->constrained("places");
            $table->unsignedInteger("round_number")->default(0);
            $table->foreignId("main_id")->nullable()->constrained("project_planned_counts")->default(null);//自分のテーブルを参照
            $table->unsignedBigInteger("counts");
            // startとendはその案件の、それぞれ最も古い日時と新しい日時。
            $table->date("start_date");
            $table->date("end_date");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_planned_counts');
    }
};
