<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // 同じ案件の時の確認までの間に一時保存で入れるもの
    // 前回のプロジェクトと１ヶ月間以上間があいた時に確認が出る
    public function up(): void
    {
        Schema::create('project_imports', function (Blueprint $table) {
            $table->id();
            // 開始日時
            $table->date("start_date")->nullable();
            // 終了日時
            $table->date("end_date")->nullable();
            // プロジェクト名と同案件フラグ(CSVから複数取得しており、合っているものも合っていないものも両方保存必要。同案件フラグは今回は重なっていないが２以上のものもある)
            $table->string("project_name");
            $table->foreignId("place_id")->constrained("places");
            $table->unsignedInteger("another_project_flag");
            // ①確実に変更の場合②変更可能性の場合のプロジェクトのId、変更しない場合はnull
            $table->foreignId("project_id")->nullable()->constrained("projects");
            // 独立もしくは確実に変更の場合はtrue、変更可能性の場合はfalse
            $table->boolean("change_flag")->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_imports');
    }
};
