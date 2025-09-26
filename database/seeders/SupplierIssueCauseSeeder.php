<?php

namespace Database\Seeders;

use App\Models\SupplierIssueCause;
use Illuminate\Database\Seeder;

class SupplierIssueCauseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        SupplierIssueCause::factory()->createMany([
            ['title' => 'Abolladura'],
            ['title' => 'Medidas'],
            ['title' => 'Ensamble'],
            ['title' => 'Deformación'],
        ]);
    }
}
