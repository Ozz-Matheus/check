<?php

namespace Database\Seeders;

use App\Models\ActionSource;
use Illuminate\Database\Seeder;

class ActionSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        ActionSource::factory()->createMany([
            ['title' => 'Inspección'],
            ['title' => 'Sugerencia'],
            ['title' => 'Auditoría Externa'],
            ['title' => 'Revisión por la Dirección'],
            ['title' => 'Indicadores'],
        ]);
    }
}
