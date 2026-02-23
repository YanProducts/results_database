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
        // 営業所担当の情報
        Schema::create('branch_manager_lists', function (Blueprint $table) {
            $table->id();
            $table->string("user_name")->unique();
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
        Schema::dropIfExists('branch_manager_lists');
    }
};
