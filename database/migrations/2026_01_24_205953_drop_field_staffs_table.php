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
        // 仕様変更に伴い「field_staff」テーブルを削除(中央でデータをもつ)
            Schema::dropIfExists('field_staffs');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
     // rollback 時に元の構造で復元
        Schema::create('field_staffs', function (Blueprint $table) {
            //
            $table->id();        
            $table->string("user_name")->unique();
            $table->string("password");
            $table->string("real_name")->nullable();
            // ブラウザに保持させて自動ログインに使用するtoken
            $table->rememberToken();
            $table->foreignId("place_id")->constrained("places");
            $table->timestamps();
        });
    }
};
