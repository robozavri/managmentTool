<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
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
        DB::table('users')->insert([
            'name' => Str::random(10),
            'email' => Str::random(10).'@gmail.com',
            'password' => bcrypt('password'),
            'api_token' => '1eb95a9661b82061f548b7544dd7ec50b83b2f724221af4d6c6f5a5c101636bb',
//            'api_token' => Str::random(60),
        ]);
    }
}
