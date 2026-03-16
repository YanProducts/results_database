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
        Schema::table('addresses', function (Blueprint $table) {
            // cityとtownのみのindexを追加する
            $table->index(["city","town"]);
            // pref.city.townはユニーク設定しているので自動的にindexがつくので削除
            $table->dropIndex('addresses_pref_city_town_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            //
            // cityとtownのみのindexを追加する
            $table->index(["pref","city","town"]);
            // pref.city.townはユニーク設定しているので自動的にindexがつくので削除
            $table->dropIndex(["city","town"]);
        });
    }
};
