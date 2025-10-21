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
        Schema::create('audit_sub_process_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('process_id')->constrained('processes');
            $table->foreignId('sub_process_id')->constrained('sub_processes');
            $table->string('title')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_sub_process_activities');
    }
};
