<?php

namespace Database\Seeders;

use App\Models\Navigation\NavBarDropdownUserRole;
use App\Models\Navigation\NavBarUserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use function MongoDB\BSON\toJSON;

class NavbarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // make the nav bar items. The order is the order in which they appear on the navbar, the url is the route they link to, the name is the name that appears on the navbar and the user_role is the role that can see the item
        // array with route name and then the name of the link, containg competitions.index and then Competitions
        // roles 0 means everyone can see it even if they are not logged in, 1 is admins and 2 is users.
        $navitems = array(
            1 => array(
                'name' => "competition.Competitions",
                'route' => "competitions.index",
                'roles' => [0, 1, 2],
            ),
            2 => array(
                'name' => "competition.CompetitionCreate",
                'route' => "competitions.create",
                'roles' => [2],
            ),
            3 => array(
                'name' => "competition.ParticipatingList",
                'route' => "competitions.participatingList",
                'roles' => [2],
            ),
            4 => array(
                'name' => "messages.ListUsers",
                'route' => "list_users",
                'roles' => [1],
            ),
            5 => array(
                'name' => "Category.List",
                'route' => "listCategory",
                'roles' => [1],
            ),
            //// EXAMPLE ITEM ON HOW TO ADD A DROPDOWN | Uses negative role values to prevent this being shown in the future ////
            6 => array(
                'name' => "Dashboard", // This is the parent item
                'route' => "competitions.index",
                'roles' => [-5, -6],
                'dropdown' => array(
                    1 => array(
                        'name' => "Dashboard1", // the first dropdown
                        'route' => "competitions.index",
                        'roles' => -5, // dropdowns can only have one role defined per dropdown item atm
                    ),
                    2 => array(
                        'name' => "Dashboard2", // The second dropdown item
                        'route' => "competitions.index",
                        'roles' => -6,
                    ),
                ),
            ),
        );

        // loop through the array and make the navbar items
        foreach ($navitems as $key => $navitem) {
            DB::table('nav_bar')->insert([
                // order is the position in the array
                'order' => $key,
                'url' => $navitem['route'],
                'name' => $navitem['name'],
            ]);
            foreach ($navitem['roles'] as $role) {
                NavBarUserRole::create([
                    'nav_bar_id' => $key,
                    'user_role' => $role,
                ]);
            }

            // if the item has a dropdown, make the dropdown items
            if (isset($navitem['dropdown'])) {
                foreach ($navitem['dropdown'] as $key2 => $navitem2) {
                    DB::table('nav_bar_dropdown')->insert([
                        'order' => $key2,
                        'url' => $navitem2['route'],
                        'name' => $navitem2['name'],
                        'nav_bar_id' => $key,
                        'user_role' => $navitem2['roles'],
                    ]);
                }
            }
        }
    }
}
