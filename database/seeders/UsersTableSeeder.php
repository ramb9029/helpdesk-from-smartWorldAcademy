<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departments')->insert([
            'name' => 'test',
            'code' => '1',
        ]);

        DB::table('departments')->insert([
            'name' => 'test2',
            'code' => '2',
        ]);

        DB::table('departments')->insert([
            'name' => 'test3',
            'code' => '3',
        ]);

        DB::table('positions')->insert([
            'name' => 'pos_test',
            'code' => '1',
        ]);

        DB::table('positions')->insert([
            'name' => 'pos_test2',
            'code' => '2',
        ]);

        DB::table('positions')->insert([
            'name' => 'pos_test3',
            'code' => '3',
        ]);

        DB::table('rooms')->insert([
            'number' => '1',
            'description' => 'room_test',
        ]);

        DB::table('rooms')->insert([
            'number' => '2',
            'description' => 'room_test2',
        ]);

        DB::table('rooms')->insert([
            'number' => '3',
            'description' => 'room_test3',
        ]);

        DB::table('roles')->insert([
            'title' => 'deleted',
            'description' => 'Удаленный пользователь. В архиве.',
        ]);

        DB::table('roles')->insert([
            'title' => 'administrator',
            'description' => 'самый главный человек. Доступно все',
        ]);

        DB::table('roles')->insert([
            'title' => 'moderator',
            'description' => 'Модератор, может изменять заявки итд итп...',
        ]);

        DB::table('roles')->insert([
            'title' => 'user',
            'description' => 'Пользователь системы',
        ]);

        DB::table('users')->insert([
            'firstName' => 'Admin',
            'lastName' => 'aDMIN',
            'middleName' => 'aDMIN',
            'role' => 2,
            'email' => 'test@qq.ru',
            'password' => bcrypt('test'),
            'department_id' => '1',
            'position_id' => '1',
            'room_id' => '1',
        ]);

        DB::table('users')->insert([
            'firstName' => 'User',
            'lastName' => 'User',
            'middleName' => 'User',
            'role' => 4,
            'email' => 'test2@qq.ru',
            'password' => bcrypt('test'),
            'department_id' => '2',
            'position_id' => '2',
            'room_id' => '2',
        ]);

        DB::table('users')->insert([
            'firstName' => 'Moder',
            'lastName' => 'MOder',
            'middleName' => 'Moder',
            'role' => 3,
            'email' => 'test3@qq.ru',
            'password' => bcrypt('test'),
            'department_id' => '3',
            'position_id' => '3',
            'room_id' => '3',
        ]);

        DB::table('users')->insert([
            'firstName' => 'Arhive',
            'lastName' => 'Arhive',
            'middleName' => 'Arhive',
            'role' => 1,
            'email' => 'test4@qq.ru',
            'password' => bcrypt('test'),
            'department_id' => '3',
            'position_id' => '3',
            'room_id' => '3',
        ]);

    }
}
