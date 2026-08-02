<?php

namespace Database\Seeders;

use App\Models\Deanship;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeanshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
     
        Deanship::factory()->createOne([
            'name' => [
                'en' => 'Deanship of Student Affairs',
                'ar' => 'عمادة شؤون الطلاب',
            ],
            'code' => 'DSA001',
            'email' => 'dsa@example.com',
            'extension_number' => '123456',
            'office_number' => '789012',
            'is_active' => true,
            'description' => 'Responsible for student affairs and services.',
        ]);     

        Deanship::factory()->count(3)->create();
    }
}
