<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'Kerem Mermer',
            'email' => 'keremmermer_@outlook.com',
            'password' => Hash::make('123'),
            'role' => 'admin',
            'status' => '1',
            'super_admin' => '1',
            'slug' => 'kerem-mermer',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
