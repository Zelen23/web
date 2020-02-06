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

    public $windowSize='//*[@id="purchaseForm"]/div[6]/div/select';
    public $resolutionFS='/option[5]';
    public $interPaymentDetal='//*[@id="pSubmit"]';

    public $paymentForm='//*[@id="paymentForm"]';
    public $confirmPurchase='//*[@id="paymentForm"]/div[6]/div/button';

    public $Frame='//*[@id="3dsframe"]';
    public $acsPage='//*[@id="form"]/div';

    public $ordStatus='//div[@class="col-md-4"]';

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
        /*shop*/
        $I->amOnPage('');
        $I->click($this->packetofCoffee);
        $I->see("Packet of Coffee",$this->basket);
        $I->click($this->processCheckout);
        /*ASQ*/
        $I->waitForText('Payment Details',3,$this->paymentDetails);
        $I->fillField($this->cardNumber,$pan);
        $I->fillField($this->expiryDate,$exp);
        $I->click($this->windowSize);
        $I->click($this->windowSize.'/option[1]');
        $I->click($this->interPaymentDetal);

        $I->waitForText('Use my payment address as shipping address',3,$this->paymentForm);
        $I->click($this->confirmPurchase);

        // дождаться загрузки формы в iframe
        $I->waitForElement($this->Frame,3);
        $I->switchToIFrame('3dsframe');
        $I->waitForText('Secure transaction with your personal data',10,$this->acsPage);

    }
    public function backToShop($text){
        $I=$this->acceptanceTester;
        $I->switchToIFrame();
        $I->waitForText($text,5,$this->ordStatus);
        $I->click('//*[@class="button"]');
    }

}
