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
        Schema::create('doc_expirations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doc_type_id')->unique()->constrained(); // Cada tipo documental tiene su regla
            $table->integer('management_review_years'); // Años para revisión
            $table->integer('central_expiration_years'); // Años para vencimiento central
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doc_expirations');
    }
};
