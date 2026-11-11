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
        if (! Schema::hasTable('topics')) {
            Schema::create('topics', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('title');
                $table->text('description');
                $this->addForeignUuid($table, 'subject_id');
                $this->addForeignUuid($table, 'employee_id');
                $this->addForeignUuid($table, 'classroom_id');
                $table->enum('semester', array_column(SemesterEnum::cases(), 'value'));
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topics');
    }
};
