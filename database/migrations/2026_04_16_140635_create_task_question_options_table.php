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
        Schema::create('task_question_options', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $this->addForeignUuid($table, 'task_question_id');
            $table->enum('option_label', AnswerOptionEnum::arrayCases());
            $table->longText('option_text');
            $table->boolean('is_correct')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_question_options');
    }
};
