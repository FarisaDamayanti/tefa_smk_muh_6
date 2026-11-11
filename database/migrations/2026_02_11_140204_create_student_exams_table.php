<?php

use App\Enums\UserExamStatus;
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
        Schema::create('student_exams', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $this->addForeignUuid($table, 'student_id');
            $this->addForeignUuid($table, 'exam_id');
            $table->float('score', 5, 2)->nullable();
            $table->enum('status', UserExamStatus::arrayCases());
            $table->datetime('started_at');
            $table->datetime('finished_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_exams');
    }
};
