<?php

use App\Enums\StatusSubmissionTaskEnum;
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
        Schema::create('task_submissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('answer');
            $table->integer('grade')->nullable();
            $table->text('note')->nullable();
            $this->addForeignUuid($table, 'student_id');
            $this->addForeignUuid($table, 'task_id');
            $table->dateTime('submit_date');
            $table->enum('status', array_column(StatusSubmissionTaskEnum::cases(), 'value'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_submissions');
    }
};
