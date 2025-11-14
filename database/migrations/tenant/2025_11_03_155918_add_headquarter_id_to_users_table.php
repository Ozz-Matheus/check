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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('headquarter_id')->after('id')->default(1)->constrained()->restrictOnDelete();
            $table->boolean('view_all_headquarters')->default(false);
            $table->boolean('interact_with_all_headquarters')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['headquarter_id']);
            $table->dropColumn('headquarter_id');
            $table->dropColumn('view_all_headquarters');
            $table->dropColumn('interact_with_all_headquarters');
        });
    }
};
