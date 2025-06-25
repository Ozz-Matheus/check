<?php

namespace Database\Seeders;

use App\Models\AuditCriteria;
use Illuminate\Database\Seeder;

class AuditCriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        AuditCriteria::factory()->create([
            'title' => 'ISO 9001',
        ]);
        AuditCriteria::factory()->create([
            'title' => 'Procedimientos',
        ]);
        AuditCriteria::factory()->create([
            'title' => 'Etc',
        ]);
    }
}
