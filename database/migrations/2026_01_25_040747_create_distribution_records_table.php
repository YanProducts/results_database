<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 町丁目ごとの配布数
        Schema::create('distribution_records', function (Blueprint $table) {
            $table->id();

            // 配布日(デフォルトはlaravel変数側で取得)
            $table->date("distribution_date");
            // 配布数            
            $table->unsignedInteger("distribution_count");

            // 町丁目名(外部キー)
            $table->foreignId('addressesId')->constrained('addresses');
            // 案件名(外部キー：事務所側からの備考はこの内部にあり)
            $table->foreignId("projectsId")->constrained("projects");
            // スタッフ名(外部キー)
            $table->foreignId("staffsId")->constrained("field_staff_lists");
            // 備考(スタッフ側から)
            $table->string("remarks")->nullable();

            // 配布日&配布スタッフ&案件名&町丁目セットは1つのみ(カラム名が長いために名前もつける)
            $table->unique(["distribution_date","addressesId","projectsId","staffsId"],"unique_distribution_sets");

            // 検索用
            $table->index(["addressesId"]);
            $table->index(["projectsId"]);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribution_records');
    }
};
