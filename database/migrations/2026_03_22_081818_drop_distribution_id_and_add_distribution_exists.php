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
        // distribution_plan_importsの過去履歴のidの取得の部分を存在確認へ変更
        Schema::table('distribution_plan_imports', function (Blueprint $table) {
            //
            // 外部キー削除＋カラム削除
            $table->dropConstrainedForeignId('distribution_plan_id');
            $table->dropConstrainedForeignId('distribution_record_id');
            $table->boolean("distribution_plan_exists")->default(false);
            $table->boolean("distribution_record_exists")->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distribution_plan_imports', function (Blueprint $table) {
            $table->dropColumn("distribution_plan_exists");
            $table->dropColumn("distribution_record_exists");
            $table->foreignId("distribution_plan_id")->nullable()->constrained("distribution_plans")->nullOnDelete();
            $table->foreignId("distribution_record_id")->nullable()->constrained("distribution_records")->nullOnDelete();
        });
    }
};
