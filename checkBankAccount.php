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
                    ->click('li.dropdown.dd-1.open li.dropdown.submenu:nth-child(2) li:nth-child(3)')
                    ->waitFor('a#addButton',1);

            // preg match to get dynamic ID in digits only from column
            $patternGetDynamicID = '/[^0-9]/';
            
            // get dynamic ID from the text name of the column
            $getouterHTMLByColumnName = $browser->driver->findElement(
                WebDriverBy::xpath('//span[contains(normalize-space(text()),"Alias Name")]'))->getAttribute('ID');

            // get int from ID based on the column of the name selected
            echo "\n" . '=========================================================================' . "\n";
            echo 'HTML ID:' . $getouterHTMLByColumnName . "\n";
            echo 'Column Name ID:' . $getColumnNameDynamicID = intval(preg_replace($patternGetDynamicID,'',$getouterHTMLByColumnName)) . "\n";
            echo '=========================================================================' . "\n";


            // get dynamic ID from the search box column based on $getouterHTMLByColumnName variable
            $columnID = intval(($getColumnNameDynamicID - 1010) + 1);
            echo 'ID of the Search Box: ' . $columnID . "\n";

            // indicate that particular Search Box based on the name of the column selected
            $getouterHTMLBySearchBox = $browser->driverfindElement(
                WebDriverBy::cssSelector
                ('div#filterBar-innerCt table:nth-of-type('. $columnID .') td:nth-child(2) input'))->getAttribute('ID');

            // get int from ID based on Search Box
            echo '=========================================================================' . "\n";
            echo 'HTML ID:' . $getouterHTMLBySearchBox . "\n";
            echo 'Search Box ID:' . intval(preg_replace($patternGetDynamicID,'',$getouterHTMLBySearchBox)) . "\n";
            echo '=========================================================================' . "\n";

            // fill up the selected search box and do validation afterwards
            $browser->type('div#filterBar-innerCt table:nth-of-type('. $columnID . ') td:nth-child(2) input','HSB12345')
                    ->keys('div#filterBar-innerCt table:nth-of-type('. $columnID .') td:nth-child(2) input',['{ENTER}'])
                    ->waitForTextIn('div#gridPagingToolbar-innerCt div:nth-of-type(7)','Displaying 1 - 1 of 1')
                    ->pause(2000);
        }
    );
    }
}