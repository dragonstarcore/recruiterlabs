<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('job_title');
            $table->enum('job_type', ['Permanent', 'Contract']);
            $table->enum('recruitment_type', ['Contingent', 'Retained']);
            $table->string('industry')->nullable();
            $table->string('location')->nullable();
            $table->decimal('salary', 10, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->decimal('margin_agreed', 5, 2)->nullable();
            $table->decimal('fee', 10, 2)->nullable();
            $table->text('job_description')->nullable();
            $table->boolean('status')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
