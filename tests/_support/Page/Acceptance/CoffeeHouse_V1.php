<?php
namespace Page\Acceptance;

class CoffeeHouse_V1
{
    // include url of current page
    public static $URL = '';

    /**
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: Page\Edit::route('/123-post');
     */


    public $bExpressCheckout='//*[@class="checkout"]';
    public $inpCardNumber='//*[@id="card_number"]';
    public $lCardNumber='//*[text()="Card number:"]';

    public $iYear='//*[@id="exp_year"]';
    public $iMonth='//*[@id="exp_month"]';

    public $bPlaceOrder='//*[@class="submit"]';

    public $cbinIframe='//*[@id="inIframe"]';
    public $Frame='//*[@id="iframe"]';
    public $acsPage='//*[@id="form"]';

    public $ordStatus='//*[@id="content"]';

    //Thank you for your order, enjoy your coffee
    //Something went wrong...
    const SUCCESS="hank you for your order, enjoy your coffee",
          DECLINE="Something went wrong...";

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

    public function saleV1($pan,$exp){
        $I=$this->acceptanceTester;
        $I->amOnPage('');
        $I->click($this->bExpressCheckout);
        $I->waitForText("Card number:",5,$this->lCardNumber);

        $I->fillField($this->inpCardNumber,$pan);
        $I->fillField($this->iYear,substr($exp,0,2));
        $I->fillField($this->iMonth,substr($exp,-2));
        $I->click($this->cbinIframe);
        $I->click($this->bPlaceOrder);

        $I->waitForElement($this->Frame,10);
        $I->switchToIFrame('iframe');
        $I->waitForText(FirstPage::SECURETRANSACTION,10,$this->acsPage);

    }

    public function backToShop($text){
        $I=$this->acceptanceTester;
        $I->switchToIFrame();
        $I->waitForText($text,5,$this->ordStatus);
        $I->click('//*[@class="goback"]');

    }
}
