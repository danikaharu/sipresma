<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('activity_categoryies')->insert([
            [
                'name' => 'Prestasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Aktivitas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
