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
        ]);
        DocType::factory()->create([
            'name' => 'instructive',
            'label' => 'Instructivo',
            'acronym' => 'I',
        ]);
        DocType::factory()->create([
            'name' => 'policy',
            'label' => 'Política',
            'acronym' => 'P',
        ]);
        DocType::factory()->create([
            'name' => 'matrix',
            'label' => 'Matriz',
            'acronym' => 'M',
        ]);
        DocType::factory()->create([
            'name' => 'format',
            'label' => 'Formato',
            'acronym' => 'F',
        ]);
    }
}
