<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Stefan van Dockum',
            'email' => 'admin@admin.nl',
            'password' => hash::make('stefan123'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'user_role' => 1,
        ]);
        DB::table('users')->insert([
            'name' => 'Test1',
            'email' => 'test1@test.nl',
            'password' => hash::make('test123'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'user_role' => 2,
        ]);
        DB::table('users')->insert([
            'name' => 'Test2',
            'email' => 'test2@test.nl',
            'password' => hash::make('test123'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'user_role' => 2,
        ]);
        DB::table('users')->insert([
            'name' => 'Test3',
            'email' => 'test3@test.nl',
            'password' => hash::make('test123'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'user_role' => 2,
        ]);

        // These are completely random names generated
        $names = [
            'Maximillian Whitley',
            'Alisa Mota',
            'Dangelo Dang',
            'Nya Paz',
            'Justyn Tidwell',
            'Susannah Amador',
            'Cesar Healey',
            'Jose Bullard',
            'Meg Bayer',
            'Jaden Ramos',
            'Cas',
            'Freek',
            'Noor',
            'Sophie',
        ];

        // Create a user for each of them, email is the name with @gmail appended, password is test123
        foreach ($names as $name) {
            DB::table('users')->insert([
                'name' => $name,
                'email' => strtolower(str_replace(' ', '', $name)) . '@gmail.nl',
                'password' => hash::make('test123'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'user_role' => 2,
            ]);
        }
    }

}
