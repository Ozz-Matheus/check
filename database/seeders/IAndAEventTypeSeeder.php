<?php

namespace Database\Seeders;

use App\Models\IAndAEventType;
use Illuminate\Database\Seeder;

class IAndAEventTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        IAndAEventType::factory()->createMany([
            ['title' => 'Incidente', 'acronym' => 'INC'],
            ['title' => 'Accidente', 'acronym' => 'ACC'],
        ]);
    }
}
