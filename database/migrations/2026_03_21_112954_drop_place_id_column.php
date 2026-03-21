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
        Schema::table('projects', function (Blueprint $table) {
            //project_importsからplace_idカラムを消す(案件名が同じで複数の営業所に振っている案件が存在)
            // placeはditribution_pplanや同じくrecordで確認
             $table->dropForeign(["place_id"]);
            $table->dropColumn("place_id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            //
            $table->foreignId("place_id")->constrained("places");
        });
    }
};
