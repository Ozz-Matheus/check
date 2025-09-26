<?php

namespace Database\Seeders;

use App\Models\DocStorage;
use Illuminate\Database\Seeder;

class DocStorageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DocStorage::factory()->createMany([
            ['name' => 'physical', 'label' => 'Fisico'],
            ['name' => 'digital', 'label' => 'Digital'],
        ]);
    }
}
