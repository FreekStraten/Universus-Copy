<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DeleteCategoryTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function testExample(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->pause(500)
                ->type('email', 'admin@admin.nl')
                ->pause(500)
                ->type('password', 'stefan123')
                ->pause(500)
                ->click('@login')
                ->pause(750)
                ->visit('/categoryList')
                ->pause(750)
                ->assertPathIs('/categoryList')
                ->pause(150)
                ->click('@delete')
                ->pause(750)
                ->visit('/categoryList');

        });
    }
}
