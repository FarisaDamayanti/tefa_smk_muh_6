<?php

use App\Enums\GroupExamQuestionMatchingEnum;
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
        Schema::create('exam_question_matchings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $this->addForeignUuid($table, 'exam_question_id');
            $table->longText('item_text');
            $table->enum('group', GroupExamQuestionMatchingEnum::arrayCases());
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_question_matchings');
    }
};
