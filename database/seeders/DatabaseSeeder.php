<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $count = DB::table('users')->count();
        if ($count == 0) {
            $this->call(UsersTableSeeder::class);
        }
        $count = DB::table('topics')->count();
        if ($count == 0) {
            $this->call(TopicsTableSeeder::class);
        }
        $count = DB::table('statuses_execution')->count();
        if ($count == 0) {
            $this->call(StatusesExecutionTableSeeder::class);
        }
        Model::reguard();
    }
}
