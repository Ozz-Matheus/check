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
        Schema::create('users_versions_decisions', function (Blueprint $table) {

            $table->id();

            // Sobre qué versión
            $table->foreignId('version_id')->constrained('doc_versions')->cascadeOnDelete();

            // Quién tomó la decisión
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // Cuál fue la decisión
            $table->foreignId('status_id')->constrained('statuses');

            // Cuál fue la razón de su decisión
            $table->string('comment')->nullable();

            // Asegura que un usuario solo pueda tener una decisión por versión
            $table->unique(['user_id', 'version_id']);

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_versions_decisions');
    }
};
