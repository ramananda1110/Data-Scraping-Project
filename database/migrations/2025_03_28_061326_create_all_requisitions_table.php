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
        Schema::create('all_requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('index_id')->unique(); 
            $table->string('etin_id')->nullable();
            $table->string('name_of_institute');
            $table->string('district');
            $table->string('thana')->nullable();
            $table->string('post_name');
            $table->string('subject')->nullable();
            $table->integer('vacancy')->default(0);
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
        Schema::dropIfExists('all_requisitions');
    }
};
