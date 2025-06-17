<?php

namespace Database\Seeders;

use App\Models\DocExpiration;
use Illuminate\Database\Seeder;

class DocExpirationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DocExpiration::factory()->create([
            'doc_type_id' => 1,
            'management_review_years' => 1,
            'central_expiration_years' => 2,
        ]);
        DocExpiration::factory()->create([
            'doc_type_id' => 2,
            'management_review_years' => 2,
            'central_expiration_years' => 5,
        ]);
        DocExpiration::factory()->create([
            'doc_type_id' => 3,
            'management_review_years' => 1,
            'central_expiration_years' => 2,
        ]);
        DocExpiration::factory()->create([
            'doc_type_id' => 4,
            'management_review_years' => 6,
            'central_expiration_years' => 10,
        ]);
        DocExpiration::factory()->create([
            'doc_type_id' => 5,
            'management_review_years' => 4,
            'central_expiration_years' => 12,
        ]);
    }
}
