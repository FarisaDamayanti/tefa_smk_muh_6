<?php

use App\Enums\AnswerOptionEnum;
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
            if (!Schema::hasColumn('student_question_answers', 'answer_text')) {
                $table->longText('answer_text')->nullable()->after('exam_question_id');
            }

            if (!Schema::hasColumn('student_question_answers', 'score')) {
                $table->float('score', 5, 2)->nullable()->after('answer_text');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_question_answers', function (Blueprint $table) {
            if (!Schema::hasColumn('student_question_answers', 'answer')) {
                $table->enum('answer', AnswerOptionEnum::arrayCases())->after('exam_question_id');
            }

            if (Schema::hasColumn('student_question_answers', 'answer_text')) {
                $table->dropColumn('answer_text');
            }

            if (!Schema::hasColumn('student_question_answers', 'score')) {
                $table->dropColumn('score');
            }
        });
    }
};
