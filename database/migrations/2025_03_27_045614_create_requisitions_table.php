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
        Schema::create('requisitions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('index_id')->unique(); // Added index_id (first value from the dataset)
            $table->bigInteger('etin_id'); // Institute ID
            $table->string('name_of_institute');
            $table->string('district');
            $table->string('thana');
            $table->string('post_name');
            $table->string('subject');
            $table->integer('vacancy');
            $table->string('type');
            $table->string('apply_for');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisitions');
    }
};
