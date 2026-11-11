<?php

use App\Enums\SemesterEnum;
use App\Enums\SubmissionTypeEnum;
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
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('description');
            $table->dateTime('deadline');
            $table->enum('submission_type', array_column(SubmissionTypeEnum::cases(), 'value'));
            $table->boolean('allow_overdue');
            $this->addForeignUuid($table, 'topic_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
