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
        Schema::create('users_lead_subprocesses', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('sub_process_id')->constrained('sub_processes');

            $table->primary(['user_id', 'sub_process_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_lead_subprocesses');
    }
};
