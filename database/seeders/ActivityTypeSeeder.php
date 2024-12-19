<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('activity_types')->insert([
            [
                'activity_category_id' => 1,
                'name' => 'PKM',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'activity_category_id' => 1,
                'name' => 'Lomba',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'activity_category_id' => 2,
                'name' => 'Seminar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'activity_category_id' => 2,
                'name' => 'Konferensi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'activity_category_id' => 2,
                'name' => 'Workshop',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'activity_category_id' => 2,
                'name' => 'Pertukaran Pelajar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'activity_category_id' => 2,
                'name' => 'Sertifikasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'activity_category_id' => 2,
                'name' => 'Dan Lain-Lain',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
