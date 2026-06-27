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
        Schema::table('field_staff_lists', function (Blueprint $table) {
            //staff_nameとplace_idのセットをuniqueにする
            $table->unique(["staff_name","place_id"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('field_staff_lists', function (Blueprint $table) {
            //
        $table->dropUnique(["staff_name","place_id"]);
        });
    }
};
