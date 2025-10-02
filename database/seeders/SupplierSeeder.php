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
            ['title' => 'Proveedor 1', 'email' => fake()->unique()->safeEmail()],
            ['title' => 'Proveedor 2', 'email' => fake()->unique()->safeEmail()],
            ['title' => 'Proveedor 3', 'email' => fake()->unique()->safeEmail()],
            ['title' => 'Proveedor 4', 'email' => fake()->unique()->safeEmail()],
            ['title' => 'Proveedor 5', 'email' => fake()->unique()->safeEmail()],
        ]);
    }
}
