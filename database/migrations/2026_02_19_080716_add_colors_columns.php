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
        Schema::table('places', function (Blueprint $table) {
            //営業所のテーブルに色の追加
            $table->double("red");
            $table->double("green");
            $table->double("blue");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('places', function (Blueprint $table) {
            //
            $table->dropColumn("red");
            $table->dropColumn("green");
            $table->dropColumn("blue");
        });
    }
};
