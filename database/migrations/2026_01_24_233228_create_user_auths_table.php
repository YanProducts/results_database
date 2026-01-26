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
        // ログイン用の共通テーブル
        Schema::create('user_auths', function (Blueprint $table) {
            $table->id();
            $table->string("authable_type");
            $table->integer("authable_id");
            $table->string("password");
            $table->rememberToken();
            $table->string("email")->nullable;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_auths');
    }
};
