<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusesExecutionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('statuses_execution')->insert([
            'name' => 'В архиве',
            'description' => 'Заявка в архиве',
        ]);
        DB::table('statuses_execution')->insert([
            'name' => 'Новая',
            'description' => 'Заявка новая',
        ]);
        DB::table('statuses_execution')->insert([
            'name' => 'В работе',
            'description' => 'Заявка в работе',
        ]);
        DB::table('statuses_execution')->insert([
            'name' => 'Исполнена',
            'description' => 'Заявка исполнена',
        ]);
    }
}
