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
        // メール認証が行われるまでの間の全般統括データの一時登録
        Schema::create('onetime_whole_datas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("user_name");
            $table->string("password");
            $table->string("email");
            // ワンタイムトークン
            $table->string("onetime_token",64);
            // 期限
            $table->dateTime("expired_at");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onetime_whole_datas');
    }
};
