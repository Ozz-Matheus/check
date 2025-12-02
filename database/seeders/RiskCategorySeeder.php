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
            'title' => 'Estratégicos',
        ]);
        RiskCategory::factory()->create([
            'title' => 'Operativos',
        ]);
        RiskCategory::factory()->create([
            'title' => 'Financieros',
        ]);
        RiskCategory::factory()->create([
            'title' => 'De Cumplimiento / Legales',
        ]);
        RiskCategory::factory()->create([
            'title' => 'Reputacionales',
        ]);
        RiskCategory::factory()->create([
            'title' => 'Seguridad de la Información',
        ]);
    }
}
