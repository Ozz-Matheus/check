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
        Schema::create('incident_and_accidents', function (Blueprint $table) {
            $table->id();
            $table->string('classification_code')->unique();
            $table->string('title');
            $table->text('description');
            $table->string('name_affected_person');
            $table->foreignId('event_type_id')->constrained('i_and_a_event_types');
            $table->foreignId('process_id')->constrained('processes');
            $table->foreignId('sub_process_id')->constrained('sub_processes');
            $table->dateTime('event_date');
            $table->string('event_site');
            $table->foreignId('priority_id')->constrained('priorities');
            $table->foreignId('status_id')->constrained('statuses');
            $table->foreignId('created_by_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_and_accidents');
    }
};
