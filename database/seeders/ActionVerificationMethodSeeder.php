<?php

namespace Database\Seeders;

use App\Models\ActionVerificationMethod;
use Illuminate\Database\Seeder;

class ActionVerificationMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        ActionVerificationMethod::factory()->create([
            'title' => 'Revisión documental',
        ]);
        ActionVerificationMethod::factory()->create([
            'title' => 'Entrevista',
        ]);
        ActionVerificationMethod::factory()->create([
            'title' => 'Inspección en sitio',
        ]);
        ActionVerificationMethod::factory()->create([
            'title' => 'Medición de indicador',
        ]);
    }
}
