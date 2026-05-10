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
        // projectsに入力完成フラグを追加
        Schema::table('projects', function (Blueprint $table) {
            //
            $table->boolean("is_complete")->default(false);
            });
            }

            /**
             * Reverse the migrations.
            */
     public function down(): void
     {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn("is_complete");
        });
    }
};
