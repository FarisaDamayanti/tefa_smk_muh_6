<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('modules')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->longText('content')->nullable()->change();
                $table->text('link')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('modules')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->longText('content')->nullable(false)->change();
                $table->string('link')->nullable(false)->change();
            });
        }
    }
};
