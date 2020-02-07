<?php 

use \Page\Acceptance\CoffeeHouse  as CoffeeHouse;
use \Page\Acceptance\FirstPage  as FirstPage;
class FirstCest
{
    // java -jar -Dwebdriver.chrome.driver=chromedriver selenium-server-standalone-3.141.59.jar
    // vendor/bin/codecept  run tests/acceptance/FirstCest --env staging
    protected $data;

    public function _before(AcceptanceTester $I,\Codeception\Scenario $scenario, CoffeeHouse $shopPage)
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
                $this->data->phone="79169174004";
               // $shopPage->sale( $data->pan,$data->exp);
            break;
            default:
                $this->data->pan='4785290000212340';
                $this->data->exp='2005';
                $this->data->phone='79169174004';
                $shopPage->sale( $this->data->pan,$this->data->exp);
                break;

        }

    }
    public function _after(\AcceptanceTester $I,\Codeception\Scenario $scenario, CoffeeHouse $shopPage)
    {
        $env=$scenario->current('env');
        switch ($env){
            case 'staging':
                $shopPage->backToShop($this->data->text);
                break;
            case 'test':

                // $shopPage->sale( $data->pan,$data->exp);
                break;
            default:
                $shopPage->backToShop($this->data->text);
                break;

    }
    }

    // tests
    public function _tryToTest(AcceptanceTester $I)
    {




    }

    public function С00001(AcceptanceTester $I, CoffeeHouse $shopPage,FirstPage $secTransac
    ){
        $I->wantTo("Success");

        $data=$this->data;
        //позитивный сценарий
        //на первой форме проверить все текстовки, блоки, кнопки
        $secTransac->checkAllItems($data->pan);
        $secTransac->inputBirthDate('23/03/1990');
        $I->canSee("Please enter your birth date below in dd/mm/yyyy format");
        // переход на след страницу
        $secTransac->checkAllItemsInVerify($data);
        $secTransac->inputOTP($I->getOTPByNumber($data->phone));

        $data->text ="Order completed successfully";

    }
    public function С00002(AcceptanceTester $I, CoffeeHouse $shopPage,FirstPage $secTransac
    ){
        $I->wantTo("2 attempts OTP  wrong 1success ");
        // карта/расширение
        $data=$this->data;
        //4785290000212340  2005
       // $shopPage->sale($pan,'2005');
        //позитивный сценарий
        //на первой форме проверить все текстовки, блоки, кнопки
        $secTransac->checkAllItems($data->pan);
        $secTransac->inputBirthDate('23/03/1990');
        $I->canSee("Please enter your birth date below in dd/mm/yyyy format");
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
        $secTransac->inputOTP($I->getOTPByNumber($data->phone));
        //redirect from shop Order denied
        //dontSee Frame
        $data->text ="Order completed successfully";
    }
    /*--*/
    public function С00003(AcceptanceTester $I, CoffeeHouse $shopPage,FirstPage $secTransac
    ){
        $I->wantTo("3 attempts OTP Decline ");
        // карта/расширение
        $data=$this->data;
        //4785290000212340  2005
        // $shopPage->sale($pan,'2005');
        //позитивный сценарий
        //на первой форме проверить все текстовки, блоки, кнопки
        $secTransac->checkAllItems($data->pan);
        $secTransac->inputBirthDate('23/03/1990');
        $I->canSee("Please enter your birth date below in dd/mm/yyyy format");
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
        $data->text ="Order denied";



    }
    /* если пользователь сделал запрос и ему смс не пришла (по тех. причинам),
     потом заказал еще одну смс, а далее ему приходят они одновременно, то
     клиент может воспользоваться любым кодом из смс.
    */
    public function С00004(AcceptanceTester $I, CoffeeHouse $shopPage,FirstPage $secTransac
    ){
        $I->wantTo("resend 2 and input 1st OTP pwd ");
        $data=$this->data;
        //позитивный сценарий
        //на первой форме проверить все текстовки, блоки, кнопки
        $secTransac->checkAllItems($data->pan);
        $secTransac->inputBirthDate('23/03/1990');
        $I->canSee("Please enter your birth date below in dd/mm/yyyy format");
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
        $data->text="Order completed successfully";




    }
    public function С00005(AcceptanceTester $I, CoffeeHouse $shopPage,FirstPage $secTransac
    ){
        $I->wantTo("resend 1, wrong 1 and input 1st OTP pwd ");
        // карта/расширение
        $data=$this->data;
        //позитивный сценарий
        //на первой форме проверить все текстовки, блоки, кнопки
        $secTransac->checkAllItems($data->pan);
        $secTransac->inputBirthDate('23/03/1990');
        $I->canSee("Please enter your birth date below in dd/mm/yyyy format");
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
        $data->text="Order completed successfully";
    }
    public function С00006(AcceptanceTester $I, CoffeeHouse $shopPage,FirstPage $secTransac
    ){
        $I->wantTo("resend 2 and input last OTP attempt pwd ");
        $data=$this->data;
        //позитивный сценарий
        //на первой форме проверить все текстовки, блоки, кнопки
        $secTransac->checkAllItems($data->pan);
        $secTransac->inputBirthDate('23/03/1990');
        $I->canSee("Please enter your birth date below in dd/mm/yyyy format");
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
        $data->text="Order completed successfully";

    }




}
