<?php

namespace Tests\Browser;
namespace Facebook\WebDriver;

// use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class checkBankAccount extends DuskTestCase
{
    //use DatabaseMigrations;
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
                    ->waitForTextIn('a.glyphicons.exit','Logout');
            }
        );
    }

    public function testCheckBankAccount(){
        $this->browse(function (Browser $browser){
            $isDisplayedArrow = $browser->driver->findElement(
                WebDriverBy::xpath("//a[contains(normalize-space(@class),'arrow')]/parent::div[@id='navbar-dropdown-showmore']"))
                ->isDisplayed();

                if (strlen(trim($isDisplayedArrow)) > 0){
                    echo "\nArrow display?: Yes!\n";
                    $browser->clickAtXPath("//a[contains(normalize-space(@class),'arrow')]/parent::div[@id='navbar-dropdown-showmore']");
                }elseif (strlen(trim($isDisplayedArrow)) === 0){
                    echo "\nArrow display?: NOTHING!\n";
                }

            $browser->clickAtXPath('//a[@class="glyphicons calculator"]')
                    ->mouseover('li.dropdown.dd-1.open li.dropdown.submenu:nth-child(2)')
                    ->clickLink('Bank Accounts')
                    ->waitFor('a#addButton',2)
                    ->assertSee('Add');
            $this->findSearchBox();
        });}

    public function findSearchBox(){
        $this->browse(function (Browser $browser){
            // all of the text name of the columns
            $columns = array("ID","Code","Alias Name","Type","Currency","Bank","Account No","Name","Usage","Address","Province",
                            "City","Phone Number","Nation","Status","Swift Code","Sort Code","Card Id","Account Type",
                            "Username","Email","Login URL","Remark","Created By","Modified By","Created","Modified");

            $columnNameID = 1;
            $arrayStoredAllColumns = array();
            $disabledColumnCount = 2;
            $inputColumnCount = 1;

        // 27 columns based on the array of the variable of $columns
        for($x=0;$x<count($columns);$x++){
            // preg match to get dynamic ID in digits only from column
            $patternGetDynamicID = '/[^0-9]/';
            // number to find each ID 1 by 1
            $columnNameID = $columnNameID + 1;
            // number to count each column search
            $columnNameIDCounted = $columnNameID - 1;

            // to search and get ID from that element
            $getHTMLIDByColumnNameID[] = $browser->driver->findElement(
                WebDriverBy::xpath(
                    "//div[starts-with(normalize-space(@id),'headercontainer') and contains(normalize-space(@id),'innerCt')]
                    /div[starts-with(normalize-space(@id),'headercontainer') and contains(normalize-space(@id),'targetEl')]
                    /child::div[". $columnNameID ."]/child::div[contains(normalize-space(@id),'titleEl')]
                    /child::span[contains(normalize-space(@id),'textEl') and not(string-length()=1)]"))
                ->getAttribute('ID');
            // to search and get ID from that/those valid visible element
            $getIDByColumnName[] = $browser->driver->findElement(
                WebDriverBy::xpath(
                    "//div[starts-with(normalize-space(@id),'headercontainer') and contains(normalize-space(@id),'innerCt')]
                    /div[starts-with(normalize-space(@id),'headercontainer') and contains(normalize-space(@id),'targetEl')]
                    /child::div[". $columnNameID ."]/child::div[contains(normalize-space(@id),'titleEl')]
                    /child::span[contains(normalize-space(@id),'textEl') and not(string-length()=1)]"))
                ->getText();

            echo "\n=========================================================================\n"; 
            echo "Column ID counted: ". $columnNameIDCounted ."\n";
            echo "Column Name: ".$getIDByColumnName[$x] ."\n";
            echo "Raw Column ID: ". $getHTMLIDByColumnNameID[$x] ."\n";

            // To detect disabled 2 input columns and other input columns too
            if ($getIDByColumnName[$x] === $columns[24] || $getIDByColumnName[$x] === $columns[23]){
                echo "$getIDByColumnName[$x] detected!\n";
                $inputColumnID[] = $browser->driver->findElement(WebDriverBy::xpath("
                                //div[@id='filterBar']/child::div[@id='filterBar-innerCt']/child::div[@id='filterBar-targetEl']
                                /child::div[starts-with(@id,'component')][".$disabledColumnCount."]"))
                                ->getAttribute('ID');

                echo "Input ID based on each column: ". $inputColumnID[$x] ."\n";
            }else{
                $inputColumnID[] = $browser->driver->findElement(WebDriverBy::xpath(
                    "//div[@id='filterBar']/child::div[@id='filterBar-innerCt']/child::div[@id='filterBar-targetEl']
                    /child::table[contains(normalize-space(@id),'triggerWrap') or starts-with(@id,'textfield') or 
                    starts-with(@id,'combobox') or starts-with(@id,'datetimerangefield')][".$inputColumnCount."]
                    //td[contains(normalize-space(@id),'inputCell') or contains(normalize-space(@id),'bodyEl')]
                    /input[contains(normalize-space(@id),'inputEl') and starts-with(@id,'textfield') or 
                    starts-with(@id,'combobox') or starts-with(@id,'datetimerangefield')]"))
                    ->getAttribute('ID');

                echo "Input ID based on each column: ". $inputColumnID[$x] ."\n";
            }

            echo "=========================================================================\n";

            // To insert those valid visible columns into array
            if (!empty($getIDByColumnName[$x]) && $getIDByColumnName[$x] !== $columns[24] && $getIDByColumnName[$x] !== $columns[23]){
                echo "Column Name($getIDByColumnName[$x]) saved!\n";

                $arrayStoredAllColumns[] = array(
                    "name" => $getIDByColumnName[$x],
                    "indexColumnID" => $columnNameID,
                    "HTMLId" => $getHTMLIDByColumnNameID[$x],
                    "indexInputID" => $inputColumnCount,
                    "InputBoxId" => $inputColumnID[$x],
                );

                $inputColumnCount++;
            }elseif (!empty($getIDByColumnName[$x]) && $getIDByColumnName[$x] === $columns[24] || $getIDByColumnName[$x] === $columns[23]){
                echo "Column Name($getIDByColumnName[$x]) saved!\n";

                $arrayStoredAllColumns[] = array(
                    "name" => $getIDByColumnName[$x],
                    "indexColumnID" => $columnNameID,
                    "HTMLId" => $getHTMLIDByColumnNameID[$x],
                    "indexInputID" => $disabledColumnCount,
                    "InputBoxId" => $inputColumnID[$x],
                );

                $disabledColumnCount++;
            }elseif (empty($getIDByColumnName[$x])){
                echo "Column Name($getIDByColumnName[$x]) NOT FOUND!!!\n";

                // scroll to the right side by pixel size
                $browser->script("
                    var myDiv = document.getElementById('gridView');

                    setTimeout(function() {
                        myDiv.scrollLeft += 100;
                    }, 250);
                ");
            }
            }

        // to print out each array saved and how many were inside
        $keys = array_keys($arrayStoredAllColumns);
        for($i = 0; $i < count($arrayStoredAllColumns); $i++) {
            echo $keys[$i] . "\n";
            foreach($arrayStoredAllColumns[$keys[$i]] as $key => $value) {
                echo $key . " : " . $value . "\n";
            }
            echo "\n";
        }
        echo "\n*********************************\n";
        echo "Total columns found from array: ".count($arrayStoredAllColumns)."\n";
        echo "*********************************\n";
    });
    }
}