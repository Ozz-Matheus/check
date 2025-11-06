<?php

namespace Database\Seeders;

use App\Models\Headquarter;
use Illuminate\Database\Seeder;

class HeadquarterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Headquarter::firstOrCreate(['name' => 'Main', 'acronym' => 'MA']);
    }
}
