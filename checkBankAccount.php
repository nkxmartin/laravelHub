<?php

namespace Tests\Browser;
namespace Facebook\WebDriver;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class checkBankAccount extends DuskTestCase
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
                    ->waitUntilMissing('input[name="login"]')
                    ->waitForTextIn('a.glyphicons.exit','Logout');
            }
        );
    }

    public function testCheckBankAccount(){
        $this->browse(function (Browser $browser){
            $browser->clickAtXPath('//a[@class="glyphicons calculator"]')
                    ->mouseover('li.dropdown.dd-1.open li.dropdown.submenu:nth-child(2)')
                    ->click('li.dropdown.dd-1.open li:nth-child(2) ul li:nth-child(3)')
                    ->waitFor('a#addButton',1)
                    ->type('div#filterBar-innerCt table:nth-of-type(3) td:nth-of-type(2) input','HSB12345')
                    ->keys('div#filterBar-innerCt table:nth-of-type(3) td:nth-of-type(2) input',['{ENTER}',''],'')
                    ->waitForTextIn('div#gridPagingToolbar-innerCt div:nth-of-type(7)','Displaying 1 - 1 of 1')
                    ->pause(2000);
        }
    );
    }
}