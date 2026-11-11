<?php

use App\Enums\GraduationStatusEnum;
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
        Schema::create('graduation_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $this->addForeignUuid($table, 'student_id');
            $this->addForeignUuid($table, 'graduation_id');
            $table->enum('status', GraduationStatusEnum::arrayCases());
            $table->boolean('is_open')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('graduation_details');
    }
};
