<?php

namespace Database\Seeders;

use App\Models\InternalAuditQualification;
use Illuminate\Database\Seeder;

class InternalAuditQualificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        InternalAuditQualification::factory()->createMany([
            ['title' => 'Requiere atención', 'min' => 0, 'max' => 70, 'color' => 'danger'],
            ['title' => 'Requiere mejora', 'min' => 71, 'max' => 80, 'color' => 'warning'],
            ['title' => 'Adecuado', 'min' => 81, 'max' => 95, 'color' => 'warning'],
            ['title' => 'Excelente', 'min' => 96, 'max' => 100, 'color' => 'success'],
        ]);
    }
}
