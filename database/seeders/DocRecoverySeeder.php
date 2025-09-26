<?php

namespace Database\Seeders;

use App\Models\DocRecovery;
use Illuminate\Database\Seeder;

class DocRecoverySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DocRecovery::factory()->createMany([
            ['storage_id' => 1, 'title' => 'Incice manual'],
            ['storage_id' => 2, 'title' => 'Búsqueda por metadatos'],
        ]);
    }
}
