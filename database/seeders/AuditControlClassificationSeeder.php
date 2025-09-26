<?php

namespace Database\Seeders;

use App\Models\AuditControlClassification;
use Illuminate\Database\Seeder;

class AuditControlClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        AuditControlClassification::factory()->createMany([
            ['title' => 'No conocen el control'],
            ['title' => 'Conocen el control y no lo aplican'],
            ['title' => 'Aplican el control y no es efectivo'],
            ['title' => 'No existe el control'],
        ]);
    }
}
