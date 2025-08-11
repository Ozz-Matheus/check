<?php

namespace Database\Seeders;

use App\Models\RiskControlPeriodicity;
use Illuminate\Database\Seeder;

class RiskControlPeriodicitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        RiskControlPeriodicity::factory()->create([
            'title' => 'Periodico',
        ]);
        RiskControlPeriodicity::factory()->create([
            'title' => 'Parcial',
        ]);
    }
}
