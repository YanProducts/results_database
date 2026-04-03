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
        Schema::table('distribution_plans', function (Blueprint $table) {
            //distribution_plansのuniqueのセットにmain_idもたす
            $table->dropUnique("unique_distribution_plan_sets");
            $table->unique(["project_id","same_project_flag","address_id","place_id","main_id"],"modified_unique_distribution_plan_sets");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distribution_plans', function (Blueprint $table) {
            //
            $table->dropUnique("modified_unique_distribution_plan_sets");
            $table->unique(["project_id","same_project_flag","address_id","place_id"],"unique_distribution_plan_sets");
        });
    }
};
