<?php

namespace Database\Seeders;

use App\Models\DocEnding;
use Illuminate\Database\Seeder;

class DocEndingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DocEnding::factory()->create([
            'title' => 'digitize',
            'label' => 'Digitalizar',
        ]);
        DocEnding::factory()->create([
            'title' => 'conserve',
            'label' => 'Conservar',
        ]);
        DocEnding::factory()->create([
            'title' => 'eliminate',
            'label' => 'Eliminar',
        ]);
    }
}
