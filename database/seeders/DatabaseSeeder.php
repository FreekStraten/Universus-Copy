<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserRoleTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(CompetitionListSeeder::class);
        $this->call(userPictureSeeder::class);
        $this->call(NavbarSeeder::class);
        $this->call(FeedbackSeeder::class);
        $this->call(SettingsSeeder::class);

    }
}
