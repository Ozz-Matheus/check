<?php

namespace Database\Seeders;

use App\Models\RiskCategory;
use Illuminate\Database\Seeder;

class RiskCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        RiskCategory::factory()->create([
            'title' => 'Tecnología e información',
        ]);
        RiskCategory::factory()->create([
            'title' => 'Personas',
        ]);
        RiskCategory::factory()->create([
            'title' => 'Infraestructura y Maquinaria',
        ]);
        RiskCategory::factory()->create([
            'title' => 'Propios de los procesos',
        ]);
        RiskCategory::factory()->create([
            'title' => 'Legales y de Cumplimiento',
        ]);
        RiskCategory::factory()->create([
            'title' => 'Ambientales',
        ]);
        RiskCategory::factory()->create([
            'title' => 'Modelo de negocio',
        ]);
    }
}
