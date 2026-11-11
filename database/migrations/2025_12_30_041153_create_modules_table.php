<?php

use App\Enums\ModuleTypeEnum;
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
        if(! Schema::hasTable('modules')){
            Schema::create('modules', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('title');
                $table->longText('content');
                $table->text('link');
                $this->addForeignUuid($table, 'topic_id');
                $table->enum('link_type', array_column(ModuleTypeEnum::cases(), 'value'));
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
        Schema::dropIfExists('modules');
    }
};
