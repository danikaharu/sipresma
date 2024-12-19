<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('levels')->insert([
            [
                'name' => 'Internasional',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nasional',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lokal',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
