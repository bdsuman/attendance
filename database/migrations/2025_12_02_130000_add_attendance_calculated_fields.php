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
        Schema::table('attendances', function (Blueprint $table) {
            $table->integer('worked_seconds')->default(0);
            $table->decimal('worked_hours', 8, 2)->default(0);
            $table->string('calculated_status')->nullable()->default('incomplete');
            $table->integer('late_minutes')->default(0);
            $table->integer('early_leave_minutes')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['worked_seconds','worked_hours','calculated_status','late_minutes','early_leave_minutes']);
        });
    }
};
