<?php
namespace Page\Acceptance;

class CoffeeHouse
{
    // include url of current page
    //php vendor/bin/codecept generate:pageobject acceptance CoffeeHouse
    public  static $URL='';
    public  $packetofCoffee='//*[@id="div_item_list"]/div[3]/div[5]/button';
    public $processCheckout='//*[@id="a_checkout"]';
    public $basket=' //*[@id="basket_list"]';

    public $paymentDetails=' //*[@id="div_payment"]/div[1]/div';
    public $cardNumber='//*[@id="pan"]';
    public $expiryDate=' //*[@id="expiry"]';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: Page\Edit::route('/123-post');
     */
    public static function route($param)
    {
        return static::$URL.$param;
    }

    /**
     * @var \AcceptanceTester;
     */
    protected $acceptanceTester;

    public function __construct(\AcceptanceTester $I)
    {
        $this->acceptanceTester = $I;
    }


    public function pressButton(){
        $I=$this->acceptanceTester;

        $I->amOnPage('');
        $I->click($this->packetofCoffee);


    }

    public function sale($pan,$exp){
        $I=$this->acceptanceTester;

        $I->amOnPage('');
        $I->click($this->packetofCoffee);
        $I->see("Packet of Coffee",$this->basket);
        $I->click($this->processCheckout);
        //заменить на какую-нибудь хрень типа дождаться
        sleep(2);
        $I->see('Payment Details');
        $I->fillField($this->cardNumber,$pan);
        $I->fillField($this->expiryDate,$exp);
        sleep(2);


    }

}
