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
            // その情報が格納されているモデル名
            $table->string("authable_type");
            // 上記モデルにおけるId
            $table->integer("authable_id");
            $table->string("password");
            $table->rememberToken();
            // スルーされたがnullable()の文法ミス。後のmigrationで訂正
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
