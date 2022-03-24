<?php

namespace Tests\Browser;
namespace Facebook\WebDriver;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class addBankAccount extends DuskTestCase
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
                    ->waitUntilMissing('input[@name="login"]')
                    ->waitForTextIn('a.glyphicons.exit','Logout');
            }
        );
    }

    public function testAddBankAccount(){
        $this->browse(function (Browser $browser){
            $browser->clickAtXPath('//a[@class="glyphicons calculator"]')
                    ->mouseover('li.dropdown.dd-1.open li.dropdown.submenu:nth-child(2)')
                    ->click('li.dropdown.dd-1.open li:nth-child(2) li:nth-child(3)')
                    ->waitFor('a#addButton',1)
                    ->clickAtXPath('//span[contains(normalize-space(text()),"Add")]')
                    ->waitForTextIn('span#mywindow_header_hd-textEl','Add New Record » Bank Account',1)
                    ->click('fieldset:first-of-type table:first-of-type td:first-of-type td:nth-of-type(2)')
                    ->clickAtXPath('//li[contains(normalize-space(text()),"Viet Nam")]')
                    ->click('table[id="formcurrency-triggerWrap"] td:nth-of-type(2)')
                    ->clickAtXPath('//li[contains(normalize-space(text()),"VND")]')
                    ->waitUntilEnabled('input#formbank-inputEl',3)
                    ->click('table[id="formbank-triggerWrap"] td:nth-of-type(2)')
                    ->clickAtXPath('//li[contains(normalize-space(text()),"Agribank")]')
                    ->type('input[name="aliasname"]','HSB12345')
                    ->type('input[name="accountno"]','12345678')
                    ->type('input[name="accountname"]','Handsome Boi')
                    ->type('textarea[name="branchaddress"]','VN')
                    ->clickAtXPath('//label[contains(normalize-space(text()),"Fund Transfer")]')
                    ->clickAtXPath('//label[contains(normalize-space(text()),"Fund Out")]')
                    ->clickAtXPath('//label[contains(normalize-space(text()),"Settlement")]')
                    ->waitFor('input[name="username"]')
                    ->click('table#formaccounttype-triggerWrap td:nth-of-type(2)')
                    ->clickAtXPath('//li[text()="Personal"]')
                    ->type('input[name="username"]','HandsomeBoiBoi')
                    ->type('input[name="password"]','asdf1234')
                    ->type('input[name="confirmpassword"]','asdf1234')
                    ->type('input[name="loginurl"]','https://handsomeboi.com')
                    ->press('div:nth-of-type(3) a:first-of-type span:nth-of-type(2)')
                    ->pause(2000);
        }
    );
    }
}