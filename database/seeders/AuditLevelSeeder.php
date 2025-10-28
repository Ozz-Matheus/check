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
            'color' => 'gray',
        ]);
        AuditLevel::factory()->create([
            'title' => 'Bajo',
            'min' => 1,
            'max' => 5,
            'score' => 80,
            'color' => 'success',
        ]);
        AuditLevel::factory()->create([
            'title' => 'Medio',
            'min' => 6,
            'max' => 12,
            'score' => 60,
            'color' => 'yellow',
        ]);
        AuditLevel::factory()->create([
            'title' => 'Alto',
            'min' => 13,
            'max' => 19,
            'score' => 20,
            'color' => 'warning',
        ]);
        AuditLevel::factory()->create([
            'title' => 'Muy alto',
            'min' => 20,
            'max' => 25,
            'score' => 0,
            'color' => 'danger',
        ]);
    }
}
