<?php

namespace Database\Seeders;

use App\Models\AuditLevel;
use Illuminate\Database\Seeder;

class AuditLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        AuditLevel::factory()->create([
            'title' => 'Sin observación',
            'min' => 0,
            'max' => 0,
            'score' => 100,
        ]);
        AuditLevel::factory()->create([
            'title' => 'Bajo',
            'min' => 1,
            'max' => 4,
            'score' => 80,
        ]);
        AuditLevel::factory()->create([
            'title' => 'Medio',
            'min' => 5,
            'max' => 12,
            'score' => 60,
        ]);
        AuditLevel::factory()->create([
            'title' => 'Alto',
            'min' => 13,
            'max' => 24,
            'score' => 20,
        ]);
        AuditLevel::factory()->create([
            'title' => 'Muy alto',
            'min' => 25,
            'max' => 40,
            'score' => 0,
        ]);
    }
}
