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
        Schema::table('distribution_records', function (Blueprint $table) {
            // その日、そのスタッフ、その予定(町目と案件も一意に決まる)に対応する結果は必ず1つ（これまでのplan_idの中身の2つに合わせたものより正確。コード上取得しやすい新しいユニークキー。使っている箇所がある可能性があるため、古いものは消さない、2つ目の引数はインデックスの名前)
            $table->unique(["distribution_date","staff_id","plan_id"],  "unique_distribution_date_staff_plan");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distribution_records', function (Blueprint $table) {
            //
          $table->dropUnique("unique_distribution_date_staff_plan");
        });
    }
};
