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
    public function testLoginCaptcha(){
        $this->browse(function (Browser $browser)
        {
            $browser->visit('/operator.php')
                    ->assertInputPresent('login')
                    ->type('username','operatora')
                    ->type('clrpasswd','asdf1234')
                    ->clickAtXPath("//input[@id='captcha']")
                    ->type('captcha','1111')
                    ->press('Login')
                    ->waitFor('#error',1)
                    ->assertVisible('#error');
        }
    );
    }

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
                    ->pause(3000);
            
            $getCaptcha = $browser->driver->findElement(WebDriverBy::id('captcha'))->getAttribute('value');

            if (preg_match($patternCaptcha,$getCaptcha) == 1){
                $browser->press('Login')
                        ->waitUntilMissing('login',2);

                // find and switch the frame due to homepage having frame wrapping
                $my_frame = $browser->driver->findElement(WebDriverBy::xpath("//frame[@id='contentframe']"));
                $browser->driver->switchTo()->frame($my_frame);

                $browser->assertSee('Logout')
                        ->clickLink('Logout')
                        ->waitUntilMissing('Logout',0.5)
                        ->assertInputPresent('login');
            }else {
                $browser->quit();
            }
            }
        );
    }
}