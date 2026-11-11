<?php

use App\Enums\TaskQuestionTypeEnum;
use App\Traits\Migrations\HasForeign;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\ErrorSolutions\SolutionProviders\Laravel\DefaultDbNameSolutionProvider;

return new class extends Migration
{
    use HasForeign;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('task_questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->longText('question');
            $table->enum('type', TaskQuestionTypeEnum::arrayCases());
            $this->addForeignUuid($table, 'task_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_questions');
    }
};
