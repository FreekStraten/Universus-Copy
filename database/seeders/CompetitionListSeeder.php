<?php

namespace Database\Seeders;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CompetitionListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // creates 3 categories, and then assigns 3 competitions to each category
        $categories = array(
            'Huisdieren',
            'Fotografie',
            'Sculpturen',
            'Schilderijen',
            'Tekeningen',
            'Muziek',
            'Sport',
            'Games',
            'Overig'
        );
        $discriptions = [
            'Ontdek alles over onze harige vriendjes',
            'Verken de kunst van de fotografie',
            'Bewonder de meest indrukwekkende sculpturen',
            'Duik in de wereld van meesterlijke schilderijen',
            'Ontdek de schoonheid van het tekenen',
            'Laat je meeslepen door de kracht van muziek',
            'Ontdek de spanning van sport en beweging',
            'Ontdek de fantastische wereld van games',
            'Verken een wereld van willekeurige curiositeiten'
        ];
        foreach ($categories as $categoryName) {
            DB::table('categories')->insert([
                'name' => $categoryName,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'description' => $discriptions[array_search($categoryName, $categories)]
            ]);
        }

        // creation of competitions
        // Each competition has a name, description, start date, end date, min and max amount of competitors, userid, and category id
        // The category id is the id of the category that the competition belongs to
        // The user id is the id of the user that created the competition
        // The first competition is created by user 1, and belongs to category 1

        DB::table('competitions')->insert([
            'name' => 'Liefste Huisdieren wedstrijd',
            'description' => 'Dit is een wedstrijd voor liefste huisdieren',
            'start_date' => Carbon::now()->format('Y-m-d H:i:s'),
            'end_date' => Carbon::now()->addDays(7)->format('Y-m-d H:i:s'),
            'min_amount_competitors' => 1,
            'max_amount_competitors' => 10,
            'user_id' => 1,
            'category_id' => Category::where('name', $categories[0])->first()->id,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        // The second competition is created by user 2, and belongs to category 1
        DB::table('competitions')->insert([
            'name' => 'Snelste hond wedstrijd',
            'description' => 'Dit is een wedstrijd voor de snelste honden',
            'start_date' => Carbon::now()->format('Y-m-d H:i:s'),
            'end_date' => Carbon::now()->addDays(7)->format('Y-m-d H:i:s'),
            'min_amount_competitors' => 1,
            'max_amount_competitors' => 10,
            'user_id' => 2,
            'category_id' => Category::where('name', $categories[0])->first()->id,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        // The third competition is created by user 3, and belongs to category 2
        DB::table('competitions')->insert([
            'name' => 'Beste fotografen',
            'description' => 'Dit is een wedstrijd voor de beste fotografen. Stuur jouw mooiste foto\'s op. Kan van alles zijn, zolang het mooi is.',
            'start_date' => Carbon::now()->format('Y-m-d H:i:s'),
            'end_date' => Carbon::now()->addDays(7)->format('Y-m-d H:i:s'),
            'min_amount_competitors' => 1,
            'max_amount_competitors' => 10,
            'min_amount_pictures' => 1,
            'max_amount_pictures' => 10,
            'user_id' => 3,
            'category_id' => Category::where('name', $categories[1])->first()->id,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        // now for category 3, we create 10 competitions in a for loop
        for ($i = 0; $i < 10; $i++) {
            DB::table('competitions')->insert([
                'name' => 'Sculpturen Wedstrijd ' . $i,
                'description' => 'Sculpturen wedstrijd voor alle sculpturen gerelateerde dingen. ',
                'start_date' => Carbon::now()->format('Y-m-d H:i:s'),
                'end_date' => Carbon::now()->addDays(7)->format('Y-m-d H:i:s'),
                'min_amount_competitors' => 1,
                'max_amount_competitors' => 10,
                'user_id' => 4,
                'category_id' => Category::where('name', $categories[2])->first()->id,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        // insert a competition for category 3, but with a really long description
        DB::table('competitions')->insert([
            'name' => 'Sculpturen and co wedstrijd ',
            'description' => 'Sculpturen wedstrijd voor alle sculpturen gerelateerde dingens. Lorem ipsum dolor sit amet.',
            'start_date' => Carbon::now()->format('Y-m-d H:i:s'),
            'end_date' => Carbon::now()->addDays(7)->format('Y-m-d H:i:s'),
            'min_amount_competitors' => 1,
            'max_amount_competitors' => 10,
            'user_id' => 5,
            'category_id' => Category::where('name', $categories[2])->first()->id,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        // insert a competition that has ended already
        DB::table('competitions')->insert([
            'name' => 'Mooiste zonsondergang',
            'description' => 'Welkom bij onze fotowedstrijd! We dagen jou uit om ons te laten zien wat jij door jouw ogen ziet. Het enige wat je hoeft te doen is jouw beste foto\'s te uploaden. ',
            'start_date' => Carbon::now()->subDays(7)->format('Y-m-d H:i:s'),
            'end_date' => Carbon::now()->subDays(1)->format('Y-m-d H:i:s'),
            'min_amount_competitors' => 1,
            'max_amount_competitors' => 10,
            'min_amount_pictures' => 1,
            'max_amount_pictures' => 10,
            'user_id' => 3,
            'category_id' => Category::where('name', $categories[1])->first()->id,
            'created_at' => Carbon::now()->subDays(7)->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->subDays(7)->format('Y-m-d H:i:s')
        ]);

        // a competition that ends in 1 day
        DB::table('competitions')->insert([
            'name' => 'Beste zonsopgang',
            'description' => 'Welkom bij onze fotowedstrijd! Wat zijn de mooiste zonsondergangen? ',
            'start_date' => Carbon::now()->subDays(7)->format('Y-m-d H:i:s'),
            'end_date' => Carbon::now()->addDays(1)->format('Y-m-d H:i:s'),
            'min_amount_competitors' => 1,
            'max_amount_competitors' => 10,
            'min_amount_pictures' => 4,
            'max_amount_pictures' => 10,
            'user_id' => 3,
            'category_id' => Category::where('name', $categories[count($categories) -1])->first()->id,
            'created_at' => Carbon::now()->subDays(7)->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->subDays(7)->format('Y-m-d H:i:s')
        ]);





    }
}
