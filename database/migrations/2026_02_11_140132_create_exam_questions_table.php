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
        Schema::dropIfExists('exam_questions');
        
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $this->addForeignUuid($table, 'question_bank_id');
            $this->addForeignUuid($table, 'employee_id');
            $table->longText('question');
            $table->longText('option_a');
            $table->longText('option_b');
            $table->longText('option_c');
            $table->longText('option_d');
            $table->longText('option_e');
            $table->enum('answer', AnswerOptionEnum::arrayCases());
            $table->boolean('is_active')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_questions');
    }
};
