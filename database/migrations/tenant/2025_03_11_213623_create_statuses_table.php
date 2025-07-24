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
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string('context')->index(); // Ej: 'doc_version', 'action', 'task'
            $table->string('title')->index();   // Ej: 'approved', 'pending'
            $table->string('label');            // Ej: 'Aprobado', 'Pendiente'
            $table->string('color')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('protected')->default(false);
            $table->timestamps();

            $table->unique(['context', 'title']); // evita duplicados dentro del mismo contexto
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statuses');
    }
};
