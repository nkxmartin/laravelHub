<?php

namespace Tests\Browser;
namespace Facebook\WebDriver;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */

    public function testLogin()
    {
        $this->browse(function (Browser $browser) {
            //preg match function for captcha
            $patternCaptcha = "/[0-9]/";

            $browser->visit('/operator.php')
                    ->assertInputPresent('login')
                    ->type('username','operatora')
                    ->type('clrpasswd','asdf1234')
                    ->clickAtXPath("//input[@id='captcha']")
                    ->pause(3000)
                    ->press('Login')
                    ->waitUntilMissing('login',2);
            }
        );
    }
}