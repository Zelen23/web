<?php 

class FirstCest
{
    // java -jar -Dwebdriver.chrome.driver=chromedriver selenium-server-standalone-3.141.59.jar
    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function _tryToTest(AcceptanceTester $I)
    {

        $I->amOnPage('');


    }

    public function test(AcceptanceTester $I, \Page\Acceptance\CoffeeHouse $shopPage){
        // карта/расширение
        $shopPage->sale('4785296000031234','2005');

        //позитивный сценарий
    }
}
