<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('distribution_records', function (Blueprint $table) {
            // 入力担当による「不明」での入力が行われることを想定
            $table->boolean("is_unknown")->default(false);
            $table->integer("distribution_count")->change();
            $table->foreignId("staff_id")->nullable()->change();
            // 組み合わせのunique制約はnullが入った時はnullの重複は(単数のuniqueの時と同じく)許容されるので、直す必要はない。入力担当の訂正は何度行っても良いことになる。

        });

            // isUnknownがtrueのときのみ、配布数が負の数になることと、staff_idがnullになることが許容
            // check制約
            DB::statement("ALTER TABLE distribution_records ADD CONSTRAINT null_when_unknown CHECK(
                (is_unknown = 0 AND staff_id IS NOT NULL)
                OR
                (is_unknown = 1 AND staff_id IS NULL)
            )");
            DB::statement("ALTER TABLE distribution_records ADD CONSTRAINT under_zero_when_unknown Check(distribution_count>=0 or is_unknown=1)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE distribution_records DROP CONSTRAINT null_when_unknown");
        DB::statement("ALTER TABLE distribution_records DROP CONSTRAINT under_zero_when_unknown");
        
        Schema::table('distribution_records', function (Blueprint $table) {
            $table->dropColumn("is_unknown");
            $table->unsignedInteger("distribution_count")->change();
            $table->foreignId("staff_id")->nullable(false)->change();
        });
    }
};
