<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Supplier::factory()->createMany([
            ['title' => 'Proveedor 1', 'supplier_code' => 1234],
            ['title' => 'Proveedor 2', 'supplier_code' => 5678],
            ['title' => 'Proveedor 3', 'supplier_code' => 9012],
            ['title' => 'Proveedor 4', 'supplier_code' => 3456],
            ['title' => 'Proveedor 5', 'supplier_code' => 7890],
        ]);
    }
}
