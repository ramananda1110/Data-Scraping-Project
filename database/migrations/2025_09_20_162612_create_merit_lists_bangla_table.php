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
        Schema::create('merit_lists_bangla', function (Blueprint $table) {
            $table->id();
            $table->string('roll')->index();
            $table->string('batch')->nullable();
            $table->string('marks')->nullable();
            $table->string('applicant_name')->nullable();
            $table->string('subject')->nullable();
            $table->string('institute_type')->nullable();
            $table->string('recommend_institute')->default('N/A');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merit_lists_bangla');
    }
};
