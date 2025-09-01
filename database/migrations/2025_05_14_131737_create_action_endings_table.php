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
        Schema::create('action_endings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('action_id')->constrained();
            $table->text('real_impact');
            $table->text('result');
            $table->text('extemporaneous_reason')->nullable();
            $table->date('real_closing_date')->nullable();
            $table->date('estimated_evaluation_date')->nullable();
            $table->enum('effectiveness', ['yes', 'no', 'partial'])->nullable();
            $table->text('evaluation_comment')->nullable();
            $table->date('real_evaluation_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('action_endings');
    }
};
