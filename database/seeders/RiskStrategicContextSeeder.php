<?php

namespace Database\Seeders;

use App\Models\RiskStrategicContext;
use Illuminate\Database\Seeder;

class RiskStrategicContextSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        RiskStrategicContext::factory()->create([
            'strategic_context_type_id' => 1,
            'title' => 'Infraestructura',
        ]);
        RiskStrategicContext::factory()->create([
            'strategic_context_type_id' => 1,
            'title' => 'Personal',
        ]);
        RiskStrategicContext::factory()->create([
            'strategic_context_type_id' => 1,
            'title' => 'Procesos',
        ]);
        RiskStrategicContext::factory()->create([
            'strategic_context_type_id' => 1,
            'title' => 'Tecnología',
        ]);

        RiskStrategicContext::factory()->create([
            'strategic_context_type_id' => 2,
            'title' => 'Económicos',
        ]);
        RiskStrategicContext::factory()->create([
            'strategic_context_type_id' => 2,
            'title' => 'Medioambientales',
        ]);
        RiskStrategicContext::factory()->create([
            'strategic_context_type_id' => 2,
            'title' => 'Políticos',
        ]);
        RiskStrategicContext::factory()->create([
            'strategic_context_type_id' => 2,
            'title' => 'Sociales',
        ]);
        RiskStrategicContext::factory()->create([
            'strategic_context_type_id' => 2,
            'title' => 'Tecnológicos',
        ]);
    }
}
