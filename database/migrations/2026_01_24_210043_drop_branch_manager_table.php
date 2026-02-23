<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        /**
     * Run the migrations.
     */
    // 仕様変更に伴い営業所担当ログインテーブルを削除
    // 中央で一括データ管理
    public function up(): void
    {
        Schema::dropIfExists('branch_managers');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('branch_managers', function (Blueprint $table) {
            $table->id();
            $table->string("user_name")->unique();
            $table->string("password");
            // ブラウザに保持させて自動ログインに使用するtoken
            $table->rememberToken();
            $table->foreignId("place_id")->constrained("places");
            $table->timestamps();
        });
    }
};
