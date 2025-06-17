<?php

namespace Database\Seeders;

use App\Models\ActionOrigin;
use Illuminate\Database\Seeder;

class ActionOriginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        ActionOrigin::factory()->create([
            'title' => 'Sugerencia',
        ]);
        ActionOrigin::factory()->create([
            'title' => 'Auditoría',
        ]);
        ActionOrigin::factory()->create([
            'title' => 'Indicadores',
        ]);
    }
}
