<?php

namespace Database\Seeders;

use App\Models\ActionType;
use Illuminate\Database\Seeder;

class ActionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        ActionType::factory()->create([
            'name' => 'improve',
            'label' => 'Mejora',
        ]);
        ActionType::factory()->create([
            'name' => 'corrective',
            'label' => 'Correctiva',
        ]);
        ActionType::factory()->create([
            'name' => 'preventive',
            'label' => 'Preventiva',
        ]);
    }
}
