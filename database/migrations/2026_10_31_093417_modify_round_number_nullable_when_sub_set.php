<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('project_planned_counts', function (Blueprint $table) {
            //併配案件はround_numberをnullにする仕様に変更
            $table->integer("round_number")->nullable()->change();
        });

                // 既存の併配案件はround_numberが記入されているので、nullに直す（そうしないと、新たに作成したnullの制約に引っかかる）
        DB::table('project_planned_counts')
            ->whereNotNull('main_id')
            ->update(['round_number' => null]);

        // check制約
        DB::statement("ALTER TABLE project_planned_counts ADD CONSTRAINT round_number_is_null_when_sub_in_ppc  CHECK(
            (main_id is NULL AND round_number is not NULL)
            or
            (main_id is NOT NULL AND round_number IS NULL)
        )");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE project_planned_counts DROP CONSTRAINT round_number_is_null_when_sub_in_ppc");

        DB::table('project_planned_counts')
            ->whereNotNull('main_id')
            ->whereNull("round_number")
            ->update(['round_number' => 0]);

        Schema::table('project_planned_counts', function (Blueprint $table) {
            //nullableではない通常の形に戻す？既に存在？
             $table->integer("round_number")->nullable(false)->change();
        });

    }
};
