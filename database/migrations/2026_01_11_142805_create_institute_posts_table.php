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
        Schema::create('institute_posts', function (Blueprint $table) {
            $table->id(); // auto increment auth id
            $table->bigInteger('eiin')->index();
            $table->string('institute_name', 255);
            $table->string('authority', 50);
            $table->string('subject', 100);
            $table->string('post_name', 100);
            $table->string('thana', 100);
            $table->string('district', 100);
            $table->string('level', 150);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institute_posts');
    }
};
