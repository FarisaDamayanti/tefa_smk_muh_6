<?php

use App\Enums\SemesterEnum;
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
        Schema::dropIfExists('exams');
        
        Schema::create('exams', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $this->addForeignUuid($table, 'question_bank_id');
            $this->addForeignUuid($table, 'school_year_id');
            $table->enum('semester', array_column(SemesterEnum::cases(), 'value'));
            $table->string('title');
            $table->dateTime('exam_date');
            $table->unsignedInteger('duration');
            $table->unsignedInteger('minimum_score');
            $table->longText('rules');
            $table->unsignedInteger('total_questions');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
