<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    //案件ごとの設定部数の投稿時の重複時の一時保存
    public function up(): void
    {
        Schema::create('project_planned_count_imports', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId("created_by")->constrained("user_auths");
            $table->string("project_name"); //新しくなる可能性を考え、ひとまず名前で登録
            $table->foreignId("place_id")->constrained("places");
            $table->foreignId("main_id")->nullable()->constrained("project_planned_count_imports")->default(null);//自分のテーブルを参照
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
        Schema::dropIfExists('project_planned_count_imports');
    }
};
