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
         // 地図のみ割り当てで分けられたものかを判定
        Schema::table('distribution_assign_imports', function (Blueprint $table) {
          $table->boolean("from_simple_flag")->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distribution_assign_imports', function (Blueprint $table) {
            $table->dropColumn("from_simple_flag");
        });
    }
};
