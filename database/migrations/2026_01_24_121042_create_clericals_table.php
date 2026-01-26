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
        Schema::create('clericals', function (Blueprint $table) {
            $table->id();        
            $table->string("user_name")->unique();
            $table->string("password");
            // ブラウザに保持させて自動ログインに使用するtoken
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clericals');
    }
};
