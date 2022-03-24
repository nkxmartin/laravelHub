<?php

namespace Tests\Browser;
namespace Facebook\WebDriver;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTestEzp extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */

    public function testLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/operator.php')
                    ->type('input#username','operator')
                    ->type('input#clrpasswd','asdf1234')
                    ->clickAtXPath("//input[@id='captcha']")
                    ->type('input#captcha','1111')
                    ->clickAtXPath('//input[@name="login"]')
                    ->waitUntilMissing('login')
                    ->waitForTextIn('div#navbar-right-top ul:nth-child(1) li.dropdown.dd-1 a.glyphicons.exit','Logout');
            }
        );
    }

    public function checkBankAccountAdded(){
        $this->browse(function (Browser $browser){
            $browser->clickAtXPath('//a[@class="glyphicons calculator"]')
                    ->mouseover('li.dropdown.dd-1.open li.dropdown.submenu:nth-child(2)')
                    ->clickLink('Bank Accounts')
                    ->keys('input#textfield-1036-inputEl',['{enter}','HSB12345']);
        }
    );
    }
}