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
            // staffs_idは誤植。staff_idに戻す
            $table->renameColumn("staffs_id","staff_id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distribution_records', function (Blueprint $table) {
            //
            $table->renameColumn("staff_id","staffs_id");
        });
    }
};
