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
        Schema::create('task_submission_options', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $this->addForeignUuid($table, 'task_submission_question_id');
            $this->addForeignUuid($table, 'task_question_option_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_submission_options');
    }
};
