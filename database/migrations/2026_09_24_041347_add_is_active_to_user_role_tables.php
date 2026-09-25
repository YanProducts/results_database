<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // それぞれのlistsに「稼働中かどうか(辞めたり休んだりしていないか)」というカラムを追加。初期値は稼働。
    // Scheme::tableを別々にすることで、4つのテーブル同時に更新
    public function up(): void
    {
            Schema::table('field_staff_lists', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
              });

            Schema::table('clerical_lists', function (Blueprint $table) {
                $table->boolean('is_active')->default(true);
            });

            Schema::table('project_operator_lists', function (Blueprint $table) {
                $table->boolean('is_active')->default(true);
            });

            Schema::table('branch_manager_lists', function (Blueprint $table) {
                $table->boolean('is_active')->default(true);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('field_staff_lists', function (Blueprint $table) {
        $table->dropColumn('is_active');
        });

        Schema::table('clerical_lists', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
        Schema::table('branch_manager_lists', function (Blueprint $table) {
        $table->dropColumn('is_active');
        });

        Schema::table('project_operator_lists', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
