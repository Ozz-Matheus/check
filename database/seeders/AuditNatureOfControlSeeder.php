<?php

namespace Database\Seeders;

use App\Models\AuditNatureOfControl;
use Illuminate\Database\Seeder;

class AuditNatureOfControlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        AuditNatureOfControl::factory()->createMany([
            ['title' => 'Preventivo'],
            ['title' => 'Correctivo'],
            ['title' => 'Estructural'],
            ['title' => 'Funcional'],
            ['title' => 'Detectivos'],
        ]);
    }
}
