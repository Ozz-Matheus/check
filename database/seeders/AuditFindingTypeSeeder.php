<?php

namespace Database\Seeders;

use App\Models\AuditFindingType;
use Illuminate\Database\Seeder;

class AuditFindingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        AuditFindingType::factory()->createMany([
            ['title' => 'NC Mayor'],
            ['title' => 'NC Menor'],
            ['title' => 'Observación'],
            ['title' => 'Oportunidad de Mejora'],
        ]);
    }
}
