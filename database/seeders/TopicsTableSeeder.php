<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TopicsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('topics')->insert([
            'name' => 'Быт',
            'code' => 1,
        ]);
        DB::table('topics')->insert([
            'name' => 'Снабжение',
            'code' => 2,
        ]);
        DB::table('topics')->insert([
            'name' => 'Техподдержка',
            'code' => 3,
        ]);
    }
}
