<?php

use App\Traits\Migrations\HasForeign;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use HasForeign;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('teacher_journals', function (Blueprint $table) {
            if (!Schema::hasColumn('teacher_journals', 'topic_id')) {
                $this->addForeignUuid($table, 'topic_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_journals', function (Blueprint $table) {
            if (Schema::hasColumn('teacher_journals', 'topic_id')) {
                $table->dropForeign(['topic_id']);
                $table->dropColumn('topic_id');
            }
        });
    }
};
