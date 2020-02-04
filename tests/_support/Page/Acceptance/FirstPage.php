<?php
namespace Page\Acceptance;

use Codeception\Util\Locator;

class FirstPage
{
    // include url of current page
    public static $URL = '';


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

    public $logoAerea='//*[@id="form"]/div/div[1]/div/div';
    public $contentInfoArea='//*[@id="form"]/div/div[2]';

    public $panelArea='//*[@id="form"]/div/div[3]';
    public $entBrthday='//*[@id="birthday"]';
    public $btnConfirm='//*[@id="submit_button"]';
    public $exitLink='//*[@id="form"]/div/div[5]/div/div/div[1]/a';
    public $helpLink='//*[@id="help_button"]';



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

    public function checkAllItems($pan){

        //  проверить  что в блоке есть 2е картинки
        $I=$this->acceptanceTester;
        $I->see('Secure transaction with your personal data',$this->contentInfoArea);

        $this->checkItemInfo('Merchant','3DS (3DS2 ACS) STAGING');
        $this->checkItemInfo('Amount','10,00 USD');
        $this->checkItemInfo('Card number',$I->maskPan($pan));
        /*ПОДОГНАЛ ПОД КЕЙС ПОМЕНЯТЬ МЕСТАМИ  i:H*/
        $this->checkItemInfo('Date',gmdate('i:H d/m/Y',time()));

        $I->see('Please enter your birth date below in dd/mm/yyyy format',$this->panelArea);
        $I->seeElement($this->entBrthday);

        $I->seeElement($this->btnConfirm,['disabled'=>true]);
        $I->seeElement($this->exitLink);
        $I->seeElement($this->helpLink);
    }

    public function checkItemInfo($key,$value){
        $I=$this->acceptanceTester;
        $ContentItem="//*[text()='$key']";
        $I->see( $value,"$ContentItem/following-sibling::span");
    }
}
