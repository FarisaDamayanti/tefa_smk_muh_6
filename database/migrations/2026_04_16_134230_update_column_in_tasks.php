<?php

use App\Enums\SubmissionTypeEnum;
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
        Schema::table('tasks', function (Blueprint $table) {
            if (Schema::hasColumn('tasks', 'submission_type')) {
                $table->enum('submission_type', array_column(SubmissionTypeEnum::cases(), 'value'))->nullable()->change();
            }
            if (!Schema::hasColumn('tasks', 'is_multiple_choice')) {
                $table->boolean('is_multiple_choice')->nullable()->after('allow_overdue');
            }
            if (!Schema::hasColumn('tasks', 'can_view_answer_result')) {
                $table->boolean('can_view_answer_result')->nullable()->after('is_multiple_choice');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            if (Schema::hasColumn('tasks', 'submission_type')) {
                $table->enum('submission_type', array_column(SubmissionTypeEnum::cases(), 'value'))->change();
            }
            if (Schema::hasColumn('tasks', 'is_multiple_choice')) {
                $table->dropColumn('is_multiple_choice');
            }
            if (Schema::hasColumn('tasks', 'can_view_answer_result')) {
                $table->dropColumn('can_view_answer_result');
            }
        });
    }
};
