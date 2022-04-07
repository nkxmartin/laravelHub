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
                }elseif (strlen(trim($isDisplayedArrow)) == 0){
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
            $columnNameID = $columnNameID + 1;
            $columnNameIDCounted = $columnNameID - 1;

            $getHTMLIDByColumnNameID[] = $browser->driver->findElement(
                WebDriverBy::xpath(
                    "//div[starts-with(normalize-space(@id),'headercontainer') and contains(normalize-space(@id),'innerCt')]
                    /div[starts-with(normalize-space(@id),'headercontainer') and contains(normalize-space(@id),'targetEl')]
                    /child::div[". $columnNameID ."]/child::div[contains(normalize-space(@id),'titleEl')]
                    /child::span[contains(normalize-space(@id),'textEl') and not(string-length()=1)]"))
                ->getAttribute('ID');
            $getIDByColumnName[] = $browser->driver->findElement(
                WebDriverBy::xpath(
                    "//div[starts-with(normalize-space(@id),'headercontainer') and contains(normalize-space(@id),'innerCt')]
                    /div[starts-with(normalize-space(@id),'headercontainer') and contains(normalize-space(@id),'targetEl')]
                    /child::div[". $columnNameID ."]/child::div[contains(normalize-space(@id),'titleEl')]
                    /child::span[contains(normalize-space(@id),'textEl') and not(string-length()=1)]"))
                ->getText();

            $getColumnNameDynamicID[] = intval(preg_replace($patternGetDynamicID,'',$getIDByColumnName[$x]));
            $inputStaticID[] = intval($getColumnNameDynamicID[$x]) + 24;

            // get int from ID based on the column of the name selected
            echo "\n=========================================================================\n";
            // scroll to the end of the right side
            $browser->script("
                var myDiv = document.getElementById('gridView');

                setTimeout(function() {
                    myDiv.scrollLeft = myDiv.scrollWidth;
                }, 300);
            ");
            
            echo "Column ID counted: ". $columnNameIDCounted ."\n";
            echo "Column Name: ".$getIDByColumnName[$x] ."\n";
            echo "Raw Column ID: ". $getHTMLIDByColumnNameID[$x] ."\n";

            // To detect disabled 2 input columns
            if ($getIDByColumnName[$x] === $columns[24] || $getIDByColumnName[$x] === $columns[23]){
                echo "$getIDByColumnName[$x] detected!\n";
                $inputColumnID[] = $browser->driver->findElement(WebDriverBy::xpath("
                                //div[@id='filterBar']/child::div[@id='filterBar-innerCt']/child::div[@id='filterBar-targetEl']
                                /child::div[starts-with(@id,'component')][".$disabledColumnCount."]"))
                                ->getAttribute('ID');

                $disabledColumnCount++;
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

                $inputColumnCount++;
                echo "Input ID based on each column: ". $inputColumnID[$x] ."\n";
            }

            // echo "Raw Input ID: $getColumnNameDynamicID[$x] \n";
            // echo "Raw Input ID (calculated): $inputStaticID[$x] \n";
            echo "=========================================================================\n";
            if (!empty($getIDByColumnName[$x])){
                echo "Column Name($getIDByColumnName[$x]) saved!\n";

                $arrayStoredAllColumns[] = array(
                    "name" => $getIDByColumnName[$x],
                    "number" => $columnNameIDCounted,
                    "HTMLId" => $getHTMLIDByColumnNameID[$x],
                    "InputId" => $inputColumnID[$x],
                );
            }elseif (empty($getIDByColumnName[$x])){
                echo "Column Name($getIDByColumnName[$x]) NOT FOUND!!!\n";
            }
            }

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

    public function displayedSearchBox(){
        $this->browse(function (Browser $browser){
            // all of the text name of the columns
            $columns = array("ID","Code","Alias Name","Type","Currency","Bank","Account No","Name","Usage",
                            "Address","Province","City","Phone Number","Nation","Status","Swift Code","Sort Code","Card Id",
                            "Account Type","Username","Email","Login URL","Remark","Created By","Modified By","Created","Modified");
        // 27 columns based on the array of the variable of $columns
        for($x=0;$x<count($columns);$x++){
            $displayedColumns[] = $browser->driver->findElement(
                WebDriverBy::xpath(
                    "//div[starts-with(normalize-space(@id),'headercontainer') and contains(normalize-space(@id),'innerCt')]
                    /div[starts-with(normalize-space(@id),'headercontainer') and contains(normalize-space(@id),'targetEl')]
                    /child::div/child::div[contains(normalize-space(@id),'titleEl')]
                    /child::span[contains(normalize-space(@id),'textEl') 
                    and normalize-space(.)='".$columns[$x]."']"))
                ->isDisplayed();
            $displayedNameColumns[] = $browser->driver->findElement(
                WebDriverBy::xpath(
                    "//div[starts-with(normalize-space(@id),'headercontainer') and contains(normalize-space(@id),'innerCt')]
                    /div[starts-with(normalize-space(@id),'headercontainer') and contains(normalize-space(@id),'targetEl')]
                    /child::div/child::div[contains(normalize-space(@id),'titleEl')]
                    /child::span[contains(normalize-space(@id),'textEl') 
                    and normalize-space(.)='".$columns[$x]."']"))
                ->getText();

            echo "\nNo. of count: $x\n";
            echo "Selected Column: $columns[$x]\n";
            echo "Column Name: $displayedNameColumns[$x]\n";
            echo "$displayedColumns[$x]\n";
            if (strlen(trim($displayedColumns[$x])) > 0){
                echo "Display?: Yes!\n";
            }elseif (strlen(trim($displayedColumns[$x])) == 0){
                echo "Display?: NOTHING!\n";
            }
            
        }
        });}
}