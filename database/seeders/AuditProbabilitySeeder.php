<?php

namespace Database\Seeders;

use App\Models\AuditProbability;
use Illuminate\Database\Seeder;

class AuditProbabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        AuditProbability::factory()->create([
            'title' => 'Raro',
            'score' => 1,
        ]);
        AuditProbability::factory()->create([
            'title' => 'Improbable',
            'score' => 2,
        ]);
        AuditProbability::factory()->create([
            'title' => 'Posible',
            'score' => 3,
        ]);
        AuditProbability::factory()->create([
            'title' => 'Probable',
            'score' => 4,
        ]);
        AuditProbability::factory()->create([
            'title' => 'Casi Seguro',
            'score' => 5,
        ]);
    }
}
