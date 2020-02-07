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
    public $entOTP='//*[@id="password"]';
    public $btnConfirm='//*[@id="submit_button"]';
    public $exitLink='//*[@id="form"]/div/div[5]/div/div/div[1]/a';
    public $helpLink='//*[@id="help_button"]';

    public $resendCode='//*[@id="get_password"]';
    public $counterAttempt='//*[@id="form"]/div/div[4]/div';
    public $timerResend='//*[@id="timer_text"]';

    public $contentErr='//div[@class= "content control error"]';
    public $content='//div[@class= "content control"]';



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
/*Secure transaction with your personal data*/
    public function checkAllItems($pan){

        //  проверить  что в блоке есть 2е картинки
        $I=$this->acceptanceTester;
        $I->see('Secure transaction with your personal data',$this->contentInfoArea);

        $this->checkItemInfo('Merchant','3DS (3DS2 ACS) STAGING');
        $this->checkItemInfo('Amount','10,00 USD');
        $this->checkItemInfo('Card number',$I->maskPan($pan));
        /*ПОДОГНАЛ ПОД КЕЙС ПОМЕНЯТЬ МЕСТАМИ  i:H*/
        $this->checkItemInfo('Date',gmdate('H:i d/m/Y',time()));

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
    public function inputBirthDate($bitrhDate){
        $I=$this->acceptanceTester;
        $I->fillField($this->entBrthday,$bitrhDate);
        $I->seeElement($this->btnConfirm,['disabled'=>false]);
        $I->click($this->btnConfirm);
    }

/*'Verify by Phone*/
    public function checkAllItemsInVerify($data){

        //  проверить  что в блоке есть 2е картинки
        $I=$this->acceptanceTester;
        $I->waitForText('Verify by Phone',7,$this->contentInfoArea);

        $this->checkItemInfo('Merchant','3DS (3DS2 ACS) STAGING');
        $this->checkItemInfo('Amount','10,00 USD');
        $this->checkItemInfo('Card number',$I->maskPan($data->pan));
        /*ПОДОГНАЛ ПОД КЕЙС ПОМЕНЯТЬ МЕСТАМИ  i:H*/
        $this->checkItemInfo('Date',gmdate('H:i d/m/Y',time()));

        $I->see('Please enter the One-Time Password (OTP) below that was sent to your mobile phone (***) *** '
            .substr($data->phone,-4)
           ,$this->panelArea);
        $I->seeElement($this->entOTP);

        $I->seeElement($this->btnConfirm,['disabled'=>true]);
        $I->dontSee('You entered an invalid password. 2 attempt(s) left.',$this->contentErr);
       // print_r($I->grabAttributeFrom($this->resendCode,'style'));
        $I->seeElement($this->exitLink);
        $I->seeElement($this->helpLink);
        $I->seeElement($this->timerResend);

    }
    public function inputOTP($OTPCode){
        sleep(3);
        $I=$this->acceptanceTester;
        $I->fillField($this->entOTP,$OTPCode);
        $I->seeElement($this->btnConfirm,['disabled'=>false]);
        $I->click($this->btnConfirm);

    }
    public function inputWrongOTP($count){
        /*введл не верный otp
        нажал кнопку
        появился
       class content control error
        */
        $I=$this->acceptanceTester;
        $I->waitForText('You entered an invalid password. '.$count.' attempt(s) left.',10,$this->contentErr);
        $I->seeElement($this->btnConfirm,['disabled'=>true]);


    }
    public function checkResend()
    {
        $I = $this->acceptanceTester;

        $I->waitForElementVisible($this->resendCode,60);
        $I->click($this->resendCode);

    }


}
