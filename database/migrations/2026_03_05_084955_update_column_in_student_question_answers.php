<?php

use App\Traits\Migrations\HasForeign;
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
        Schema::table('student_question_answers', function (Blueprint $table) {
            if (!Schema::hasColumn('student_question_answers', 'employee_id')) {
                $table->foreignUuid('employee_id')->nullable()->after('exam_question_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_question_answers', function (Blueprint $table) {
            if (Schema::hasColumn('student_question_answers', 'employee_id')) {
                $table->dropForeign(['employee_id']);
                $table->dropColumn('employee_id');
            }
        });
    }
};
