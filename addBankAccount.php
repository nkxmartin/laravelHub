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

    public function testAddBankAccount(){
        $this->browse(function (Browser $browser){
            $browser->clickAtXPath('//a[@class="glyphicons calculator"]')
                    ->mouseover('li.dropdown.dd-1.open li.dropdown.submenu:nth-child(2)')
                    ->clickLink('Bank Accounts')
                    ->waitFor('span#addButton-btnInnerEl',1)
                    ->press('span#addButton-btnInnerEl')
                    ->waitForTextIn('span#mywindow_header_hd-textEl','Add New Record » Bank Account',1)
                    ->click('tr td#combobox-1145-inputCell + td div')
                    ->clickAtXPath('//li[text()="Viet Nam"]')
                    ->click('tr td#formcurrency-inputCell + td div')
                    ->clickAtXPath('//li[text()="VND"]')
                    ->waitUntilEnabled('#formbank-inputEl',3)
                    ->click('tr td#formbank-inputCell + td div')
                    ->clickAtXPath('//li[text()="Agribank  | Agribank "]')
                    ->type('input#textfield-1147-inputEl','HSB12345')
                    ->type('input#textfield-1148-inputEl','12345678')
                    ->type('input#textfield-1149-inputEl','Handsome Boi')
                    ->type('textarea#textareafield-1150-inputEl','VN')
                    ->check('input#checkboxfield-1156-inputEl')
                    ->check('input#checkboxfield-1157-inputEl')
                    ->check('input#checkboxfield-1158-inputEl')
                    ->waitFor('input#formusername-inputEl')
                    ->click('tr td#formaccounttype-inputCell + td div')
                    ->clickAtXPath('//li[text()="Personal"]')
                    ->type('input#formusername-inputEl','Handsome Boi')
                    ->type('input#formpassword-inputEl','asdf1234')
                    ->type('input#formconfirmpassword-inputEl','asdf1234')
                    ->type('input#formloginurl-inputEl','https://handsomeboi.com')
                    ->press('a#button-1175')
                    ->pause(2000);
        }
    );
    }
}