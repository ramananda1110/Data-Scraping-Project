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
        Schema::create('requisition_news', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('index_id')->nullable();
            $table->unsignedBigInteger('etin_id')->nullable();
            $table->string('name_of_institute')->nullable();
            $table->string('district')->nullable();
            $table->string('thana')->nullable();
            $table->string('post_name')->nullable();
            $table->string('subject')->nullable();
            $table->integer('vacancy')->nullable();
            $table->string('type')->nullable();
            $table->string('apply_for')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisition_news');
    }
};
