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
        Schema::create('student_answer_matchings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $this->addForeignUuid($table, 'student_question_answer_id');
            $this->addForeignUuidTo($table, 'right_item_id', 'exam_question_matchings');
            $this->addForeignUuidTo($table, 'left_item_id', 'exam_question_matchings');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_answer_matchings');
    }
};
