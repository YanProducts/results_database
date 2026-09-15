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
        //日付を編集時にnullにする
        Schema::table('distribution_records', function (Blueprint $table) {
            $table->date("distribution_date")->nullable()->change();
        });

        // isUnknownがtrueのときのみ、dateがnullになることが許容
        // check制約
        DB::statement("ALTER TABLE distribution_records ADD CONSTRAINT date_null_when_unknown CHECK(
            (is_unknown = 0 AND distribution_date IS NOT NULL)
            OR
            (is_unknown = 1 AND distribution_date IS NULL)
        )");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP CONSTRAINT date_null_when_unknown");
        Schema::table('distribution_records', function (Blueprint $table) {
            $table->date("distribution_date")->nullable(false)->change();
        });
    }
};
