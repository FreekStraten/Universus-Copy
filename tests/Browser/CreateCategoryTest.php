<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CreateCategoryTest extends DuskTestCase
{
    use withFaker;
    /**
     * A Dusk test example.
     */
    public function testCreateCategory(): void
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
                ->visit('/categoryCreate')
                ->pause(750)
                ->assertPathIs('/categoryCreate')
                ->type('@name', $this->faker->name())
                ->pause(150)
                ->type('@description', 'dit is een leuke beschrijving zeg')
                ->pause(150)
                ->click('@submit')
                ->pause(750)
                ->visit('/categoryList');

        });
    }
}
