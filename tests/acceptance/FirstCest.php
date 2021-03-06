<?php 

use \Page\Acceptance\CoffeeHouse  as CoffeeHouse;
use \Page\Acceptance\FirstPage  as FirstPage;
use \Page\Acceptance\HelpPage  as HelpPage;
use \Codeception\Scenario as Scenario;
use \Page\Acceptance\CoffeeHouse_V1  as CoffeeHouseV1;
class FirstCest
{
    // java -jar -Dwebdriver.chrome.driver=chromedriver selenium-server-standalone-3.141.59.jar
    // vendor/bin/codecept  run tests/acceptance/FirstCest --env staging
    protected $data;

    public function _before(AcceptanceTester $I,
                            Scenario $scenario,
                            CoffeeHouse $shopPage,
                            CoffeeHouseV1 $coffeeHouse_V1)
    {
        $this->data = new \stdClass();
        $env=$scenario->current('env');

        switch ($env){
            case 'staging':
                $this->data->pan='4785290000212340';
                $this->data->exp='2005';
                $this->data->phone='79169174004';
                $shopPage->sale( $this->data->pan,$this->data->exp);
                break;
            case 'test':
                $this->data->pan='4785296000031234';
                $this->data->exp='2005';
                $this->data->phone="71111111111";
               // $shopPage->sale( $data->pan,$data->exp);
               $coffeeHouse_V1->saleV1($this->data->pan,$this->data->exp);
            break;
            default:
                $this->data->pan='4785290000212340';
                $this->data->exp='2005';
                $this->data->phone='79169174004';
                $shopPage->sale( $this->data->pan,$this->data->exp);
                break;
        }

    }
    public function _after(\AcceptanceTester $I,
                           Scenario $scenario,
                           CoffeeHouse $shopPage,
                           CoffeeHouseV1 $coffeeHouse_V1)
    {
        $env=$scenario->current('env');
        switch ($env){
            case 'staging':
                if($this->data->status=='success'){

                    $shopPage->backToShop(CoffeeHouse::SUCCESS);
                }else{
                    $shopPage->backToShop(CoffeeHouse::DECLINE);
                }
                break;
            case 'test':

                if($this->data->status=='success'){

                    $coffeeHouse_V1->backToShop(CoffeeHouseV1::SUCCESS);
                }else{
                    $coffeeHouse_V1->backToShop(CoffeeHouseV1::DECLINE);
                }
                break;
            default:
                if($this->data->status=='success'){

                    $shopPage->backToShop(CoffeeHouse::SUCCESS);
                }else{
                    $shopPage->backToShop(CoffeeHouse::DECLINE);
                }
                break;

            unset($data);
    }
    }
    // tests
    public function _tryToTest(AcceptanceTester $I)
    {




    }

    public function С00001(AcceptanceTester $I, FirstPage $secTransac,HelpPage $helpPage
    ){
        $I->wantTo("Success");

        $data=$this->data;
        //позитивный сценарий
        //на первой форме проверить все текстовки, блоки, кнопки
        $secTransac->checkAllItems($data->pan);
        //helpPage
        $helpPage->checkHelpContentAndBack();
        $I->canSee(FirstPage::INTERBIRTHDAY);
        $secTransac->inputBirthDate('23/03/1990');

        // переход на след страницу
        $secTransac->checkAllItemsInVerify($data);
        //helpPage
        $helpPage->checkHelpContentAndBack();
        $secTransac->inputOTP($I->getOTPByNumber($data->phone));
        $data->status="success";

    }
    public function С00002(AcceptanceTester $I, FirstPage $secTransac,HelpPage $helpPage
    ){
        $I->wantTo("2 attempts OTP  wrong 1success ");
        // карта/расширение
        $data=$this->data;
        //4785290000212340  2005
       // $shopPage->sale($pan,'2005');
        //позитивный сценарий
        //на первой форме проверить все текстовки, блоки, кнопки
        $secTransac->checkAllItems($data->pan);
        $I->canSee(FirstPage::INTERBIRTHDAY);
        $secTransac->inputBirthDate('23/03/1990');
        // переход на след страницу
        $secTransac->checkAllItemsInVerify($data);
        // wrong OTP
        $secTransac->inputOTP("111123");
        //You entered an invalid password. 2 attempt(s) left.
        $secTransac->inputWrongOTP(2);
        //helpPage
        $helpPage->checkHelpContentAndBack();
        //The next attempt will be possible in 39 sec
        $secTransac->inputOTP("111122");
        $secTransac->inputWrongOTP(1);
        //redirect
        $secTransac->inputOTP($I->getOTPByNumber($data->phone));
        //redirect from shop Order denied
        //dontSee Frame
        $data->status="success";

    }
    /*--*/
    public function С00003(AcceptanceTester $I, FirstPage $secTransac,HelpPage $helpPage
    ){
        $I->wantTo("3 attempts OTP Decline ");
        // карта/расширение
        $data=$this->data;
        //4785290000212340  2005
        // $shopPage->sale($pan,'2005');
        //позитивный сценарий
        //на первой форме проверить все текстовки, блоки, кнопки
        $secTransac->checkAllItems($data->pan);
        $I->canSee(FirstPage::INTERBIRTHDAY);
        $secTransac->inputBirthDate('23/03/1990');
        // переход на след страницу
        $secTransac->checkAllItemsInVerify($data);
        // wrong OTP
        $secTransac->inputOTP("111123");
        //You entered an invalid password. 2 attempt(s) left.
        $secTransac->inputWrongOTP(2);
        //The next attempt will be possible in 39 sec
        $secTransac->inputOTP("111122");
        $secTransac->inputWrongOTP(1);
        //redirect
        $secTransac->inputOTP("111121");
        //redirect from shop Order denied
        //dontSee Frame
        $data->status="decline";

    }
    /* если пользователь сделал запрос и ему смс не пришла (по тех. причинам),
     потом заказал еще одну смс, а далее ему приходят они одновременно, то
     клиент может воспользоваться любым кодом из смс.
    */
    public function С00004(AcceptanceTester $I, FirstPage $secTransac,HelpPage $helpPage
    ){
        $I->wantTo("resend 2 and input 1st OTP pwd success ");
        $data=$this->data;
        //позитивный сценарий
        //на первой форме проверить все текстовки, блоки, кнопки
        $secTransac->checkAllItems($data->pan);
        $I->canSee(FirstPage::INTERBIRTHDAY);
        $secTransac->inputBirthDate('23/03/1990');
        // переход на след страницу
        $secTransac->checkAllItemsInVerify($data);
        $firstOTP=$I->getOTPByNumber($data->phone);
        // проверка кнопки resend(2а раза)
        $I->waitForText("Resend code in 58 sec.", 10, $secTransac->timerResend);
        print_r("1st");
        $secTransac->checkResend();
        $I->waitForText("The next attempt will be possible in 59 sec.",10, $secTransac->timerResend);
        print_r("2nd");
        $secTransac->checkResend();
        $I->waitForText("The next attempt will be possible in 59 sec.",10, $secTransac->timerResend);
        $secTransac->inputOTP($firstOTP);
        $data->status="success";

    }
    public function С00005(AcceptanceTester $I, FirstPage $secTransac,HelpPage $helpPage
    ){
        $I->wantTo("resend 1, wrong 1 and input 1st OTP pwd  success");
        // карта/расширение
        $data=$this->data;
        //позитивный сценарий
        //на первой форме проверить все текстовки, блоки, кнопки
        $secTransac->checkAllItems($data->pan);
        $I->canSee(FirstPage::INTERBIRTHDAY);
        $secTransac->inputBirthDate('23/03/1990');
        // переход на след страницу
        $secTransac->checkAllItemsInVerify($data);
        $firstOTP=$I->getOTPByNumber($data->phone);
        // проверка кнопки resend(2а раза)
        $I->waitForText("Resend code in 58 sec.", 10, $secTransac->timerResend);
        print_r("1st");
        $secTransac->checkResend();
        $I->waitForText("The next attempt will be possible in 59 sec.",10, $secTransac->timerResend);
        $secTransac->inputOTP("111122");
        $I->waitForText("The next attempt will be possible in 59 sec.",10, $secTransac->timerResend);
        $secTransac->inputOTP($firstOTP);
        $data->status="success";
    }
    public function С00006(AcceptanceTester $I, FirstPage $secTransac,HelpPage $helpPage
    ){
        $I->wantTo("resend 2 and input last OTP attempt pwd success ");
        $data=$this->data;
        //позитивный сценарий
        //на первой форме проверить все текстовки, блоки, кнопки
        $secTransac->checkAllItems($data->pan);
        $I->canSee(FirstPage::INTERBIRTHDAY);
        $secTransac->inputBirthDate('23/03/1990');
        // переход на след страницу
        $secTransac->checkAllItemsInVerify($data);
        // проверка кнопки resend(2а раза)
        $I->waitForText("Resend code in 58 sec.", 10, $secTransac->timerResend);
        $secTransac->checkResend();

        $I->waitForText("The next attempt will be possible in 59 sec.",10, $secTransac->timerResend);
        $secTransac->checkResend();

        $I->waitForText("The next attempt will be possible in 59 sec.",10, $secTransac->timerResend);
        $firstOTP=$I->getOTPByNumber($data->phone);

        $secTransac->inputOTP($firstOTP);
        $data->status="success";

    }
    public function С00007(AcceptanceTester $I, FirstPage $secTransac,HelpPage $helpPage
    ){
        $I->wantTo("Decline/timeout session");

        $data=$this->data;
        //позитивный сценарий
        //на первой форме проверить все текстовки, блоки, кнопки
        $secTransac->checkAllItems($data->pan);
        $I->canSee(FirstPage::INTERBIRTHDAY);
        $secTransac->inputBirthDate('23/03/1990');
        // переход на след страницу
        $secTransac->checkAllItemsInVerify($data);
        sleep(250);
        $secTransac->inputOTP($I->getOTPByNumber($data->phone));
        $data->status="decline";
    }
    public function С00008(AcceptanceTester $I, FirstPage $secTransac,HelpPage $helpPage
    ){
        $I->wantTo("Exit first Factor");
        $data=$this->data;
        //позитивный сценарий
        //на первой форме проверить все текстовки, блоки, кнопки
        $secTransac->checkAllItems($data->pan);
        $I->click($secTransac->exitLink);
        $data->status="decline";
    }
    public function С00009(AcceptanceTester $I, FirstPage $secTransac,HelpPage $helpPage
    ){
        $I->wantTo("Exit OTPpage Factor");

        $data=$this->data;
        //позитивный сценарий
        //на первой форме проверить все текстовки, блоки, кнопки
        $secTransac->checkAllItems($data->pan);
        $I->canSee(FirstPage::INTERBIRTHDAY);
        $secTransac->inputBirthDate('23/03/1990');
        // переход на след страницу
        $secTransac->checkAllItemsInVerify($data);
        $I->click($secTransac->exitLink);
        $data->status="decline";

    }
    public function С00010(AcceptanceTester $I, FirstPage $secTransac,HelpPage $helpPage
    ){
        $I->wantTo("3 attempts wrong bd  ");
        // карта/расширение
        $data=$this->data;
        //4785290000212340  2005
        // $shopPage->sale($pan,'2005');
        //позитивный сценарий
        //на первой форме проверить все текстовки, блоки, кнопки
        $secTransac->checkAllItems($data->pan);
        $secTransac->inputBirthDate('25/03/1990');
        $I->waitForText(FirstPage::WRONGBIRTHDAY,5,$secTransac->contentErr);
        // переход на след страницу
        $secTransac->inputBirthDate('25/04/1990');
        $I->waitForText(FirstPage::WRONGBIRTHDAY,5,$secTransac->contentErr);
        $secTransac->inputBirthDate('25/04/1991');
        $data->status="denied";

    }
    public function С00011(AcceptanceTester $I, FirstPage $secTransac,HelpPage $helpPage
    ){
        $I->wantTo("2 attempts wrong bd 1 sucess ");
        // карта/расширение
        $data=$this->data;
        //4785290000212340  2005
        // $shopPage->sale($pan,'2005');
        //позитивный сценарий
        //на первой форме проверить все текстовки, блоки, кнопки
        $secTransac->checkAllItems($data->pan);
        $secTransac->inputBirthDate('25/03/1990');
        $I->waitForText(FirstPage::WRONGBIRTHDAY,5,$secTransac->contentErr);
        // переход на след страницу
        $secTransac->inputBirthDate('25/04/1990');
        $I->waitForText(FirstPage::WRONGBIRTHDAY,5,$secTransac->contentErr);
        $secTransac->inputBirthDate('23/03/1990');
        // переход на след страницу
        $secTransac->checkAllItemsInVerify($data);
        // wrong OTP
        $secTransac->inputOTP("111123");
        //You entered an invalid password. 2 attempt(s) left.
        $secTransac->inputWrongOTP(2);
        //helpPage
        $helpPage->checkHelpContentAndBack();
        //The next attempt will be possible in 39 sec
        $secTransac->inputOTP("111122");
        $secTransac->inputWrongOTP(1);
        //redirect
        $secTransac->inputOTP($I->getOTPByNumber($data->phone));
        //redirect from shop Order denied
        //dontSee Frame
        $data->status="success";

    }
}
