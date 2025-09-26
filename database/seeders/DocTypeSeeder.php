<?php

namespace Database\Seeders;

use App\Models\DocType;
use Illuminate\Database\Seeder;

class DocTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DocType::factory()->create([
            'name' => 'document',
            'label' => 'Documento',
            'acronym' => 'D',
            'expiration_years' => 1,
        ]);
        DocType::factory()->create([
            'name' => 'instructive',
            'label' => 'Instructivo',
            'acronym' => 'I',
            'expiration_years' => 2,
        ]);
        DocType::factory()->create([
            'name' => 'policy',
            'label' => 'Política',
            'acronym' => 'P',
            'expiration_years' => 1,
        ]);
        DocType::factory()->create([
            'name' => 'matrix',
            'label' => 'Matriz',
            'acronym' => 'M',
            'expiration_years' => 6,
        ]);
        DocType::factory()->create([
            'name' => 'format',
            'label' => 'Formato',
            'acronym' => 'F',
            'expiration_years' => 4,
        ]);
    }
}
