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
        Schema::table('graduations', function (Blueprint $table) {
            if (!Schema::hasColumn('graduations', 'additional_file')) {
                $table->string('additional_file')->nullable()->after('school_year_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('graduations', function (Blueprint $table) {
            if (Schema::hasColumn('graduations', 'additional_file')) {
                $table->dropColumn('additional_file');
            }
        });
    }
};
