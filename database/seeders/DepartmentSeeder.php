<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Department::firstOrCreate([
            'code' => '1948',
        ], [
            'name' => [
                'en' => 'Department of Student Affairs',
                'ar' => 'قسم شؤون الطلاب',
            ],
            'email' => 'departmentsa@example.com',
            'extension_number' => '123456',
            'office_number' => '789012',
            'is_active' => true,
            'description' => 'Responsible for student affairs and services.',
            'deanship_id' => \App\Models\Deanship::inRandomOrder()->first()?->id ?? \App\Models\Deanship::factory(),
        ]);     

        Department::factory()->count(3)->create();
    }
}
