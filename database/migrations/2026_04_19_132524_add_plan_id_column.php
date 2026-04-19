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
        // 結果にplan_idをつける
        // assign時に重複していてもOKかの確認を行うため
        Schema::table('distribution_records', function (Blueprint $table) {
           $table->foreignId("plan_id")->constrained("distribuiton_plans")->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distribution_records', function (Blueprint $table) {
            //カラムも一緒に消える
            $table->dropConstrainedForeignId("plan_id");
        });
    }
};
