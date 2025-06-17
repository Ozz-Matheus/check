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
            'title' => 'Documento',
            'acronym' => 'D',
        ]);
        DocType::factory()->create([
            'title' => 'Instructivo',
            'acronym' => 'I',
        ]);
        DocType::factory()->create([
            'title' => 'Politica',
            'acronym' => 'P',
        ]);
        DocType::factory()->create([
            'title' => 'Matriz',
            'acronym' => 'M',
        ]);
        DocType::factory()->create([
            'title' => 'Formato',
            'acronym' => 'F',
        ]);
    }
}
