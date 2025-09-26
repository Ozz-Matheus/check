<?php

namespace Database\Seeders;

use App\Models\AuditEffectType;
use Illuminate\Database\Seeder;

class AuditEffectTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        AuditEffectType::factory()->createMany([
            ['title' => 'Economico'],
            ['title' => 'Personas'],
            ['title' => 'Estructural'],
            ['title' => 'Funcional'],
            ['title' => 'Reputacional'],
            ['title' => 'Ambiental'],
        ]);
    }
}
