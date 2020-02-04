<?php 

use \Page\Acceptance\CoffeeHouse  as CoffeeHouse;
use \Page\Acceptance\FirstPage  as FirstPage;
class FirstCest
{
    // java -jar -Dwebdriver.chrome.driver=chromedriver selenium-server-standalone-3.141.59.jar
    // vendor/bin/codecept  run tests/acceptance/FirstCest --env staging
    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function _tryToTest(AcceptanceTester $I)
    {




    }
    public function test(AcceptanceTester $I, CoffeeHouse $shopPage,FirstPage $secTransac
    ){
        // карта/расширение
        $pan='4785299000235458';
        $shopPage->sale($pan,'2005');
        //позитивный сценарий
        //на первой форме проверить все текстовки, блоки, кнопки
        $secTransac->checkAllItems($pan);
        $secTransac->inputBirthDate('23/03/1990');
        // переход на след страницу
        /*наверное нужно подлить в хостовую репу
        -версия codeception
        +ssh
        +доступ к базе (иожно и  вэтой модуль подкинуть но асоциировать карты нужно)*/

    }


}
