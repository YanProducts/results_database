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
        // スタッフの情報 //パスワードは別途定義
        Schema::create('field_staff_lists', function (Blueprint $table) {
            $table->id();
            $table->string("user_name")->unique();
            $table->string("staff_name");
            // 所属営業所
            $table->foreignId("placeId")->constrained("places");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('field_staff_lists');
    }
};
