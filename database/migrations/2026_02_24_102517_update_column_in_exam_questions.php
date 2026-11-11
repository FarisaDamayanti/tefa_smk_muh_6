<?php

use App\Enums\AnswerOptionEnum;
use App\Enums\ExamQuestionTypeEnum;
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
        Schema::table('exam_questions', function (Blueprint $table) {
            $table->enum('type', ExamQuestionTypeEnum::arrayCases())->default(ExamQuestionTypeEnum::SINGLE_CHOICE->value)->after('employee_id');
            $table->longText('option_a')->nullable()->change();
            $table->longText('option_b')->nullable()->change();
            $table->longText('option_c')->nullable()->change();
            $table->longText('option_d')->nullable()->change();
            $table->longText('option_e')->nullable()->change();
            $table->enum('answer', AnswerOptionEnum::arrayCases())->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_questions', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->longText('option_a')->change();
            $table->longText('option_b')->change();
            $table->longText('option_c')->change();
            $table->longText('option_d')->change();
            $table->longText('option_e')->change();
            $table->enum('answer', AnswerOptionEnum::arrayCases())->change();
        });
    }
};
