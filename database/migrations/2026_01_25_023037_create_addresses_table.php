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
        // 県〜町名のリストが保存
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string("pref");
            $table->string("city");
            $table->string("town");
            $table->unique(["pref","city","town"]);
            $table->index(["pref","city","town"]);
            // 世帯数
            $table->unsignedInteger("household");
            // 集合世帯
            $table->unsignedInteger("apartment");
            // 戸建世帯
            $table->unsignedInteger("detached");
            // 事業所数
            $table->unsignedInteger("establishment");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
