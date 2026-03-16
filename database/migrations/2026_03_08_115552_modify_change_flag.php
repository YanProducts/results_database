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
        Schema::table('project_imports', function (Blueprint $table) {
            //project_importsにおける、独立か変更の場合、change_flagの初期を変更しないを表す「false」に変更
            $table->boolean("change_flag")->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_imports', function (Blueprint $table) {
            //
            $table->boolean("change_flag")->default(true);
        });
    }
};
