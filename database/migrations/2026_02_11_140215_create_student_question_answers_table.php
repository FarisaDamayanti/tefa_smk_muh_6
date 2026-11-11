<?php

use App\Enums\AnswerOptionEnum;
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
        Schema::create('student_question_answers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $this->addForeignUuid($table, 'student_exam_id');
            $this->addForeignUuid($table, 'exam_question_id');
            $table->enum('answer', AnswerOptionEnum::arrayCases())->nullable();
            $table->boolean('is_correct');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_question_answers');
    }
};
