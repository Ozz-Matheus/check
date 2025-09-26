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
            ['title' => 'Requiere atención', 'min' => 0, 'max' => 70],
            ['title' => 'Requiere mejora', 'min' => 71, 'max' => 80],
            ['title' => 'Adecuado', 'min' => 81, 'max' => 95],
            ['title' => 'Excelente', 'min' => 96, 'max' => 100],
        ]);
        /* $qualifications = [
            'Requires attention',
            'Requiere mejora',
            'Adecuado',
            'Excelente'
        ];
        foreach ($qualifications as $qualification) {
            \DB::table('audit_qualifications')->insert([
                'title' => $qualification,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } */
    }
}
