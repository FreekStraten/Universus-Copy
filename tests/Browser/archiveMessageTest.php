<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class archiveMessageTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function testarchiveMessage(): void
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
                ->visit('/ListUser')
                ->assertPathIs('/ListUser')
                ->pause(750)
                ->click('@archive')
                ->pause(750)
                ->type('@message', 'dit is waarom je account gearchiveerd is')
                ->pause(150)
                ->click('@submit')
                ->pause(750)
                ->visit('/ListUser');
        });
    }
}
