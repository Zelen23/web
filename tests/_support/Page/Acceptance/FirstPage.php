<?php
namespace Page\Acceptance;

use Codeception\Scenario;
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

    const INTERBIRTHDAY='Please enter your birth date below in dd/mm/yyyy format',
          WRONGBIRTHDAY='You entered the wrong birth date',
          SECURETRANSACTION='Secure transaction with your personal data',
          VERIFYBYPHONE='Verify by Phone',
          INTEROTP="Please enter the One-Time Password (OTP) below that was sent to your mobile phone * "  ;



    public $Mercant;
    public $Amount;
    public $date;
    public static function route($param)
    {
        return static::$URL.$param;
    }

    /**
     * @var \AcceptanceTester;
     */
    protected $acceptanceTester;
    public function __construct(\AcceptanceTester $I,Scenario $scenario)
    {
        $this->acceptanceTester = $I;
        $this->scenarico=$scenario;
        $this->date=gmdate('H:i d/m/Y',time());
        $env=$scenario->current('env');
        switch ($env) {
            case 'staging':
                $this->Mercant = '3DS (3DS2 ACS) STAGING';
                $this->Amount = '10,00 USD';
                break;
            case 'test':
                $this->Mercant = 'CH';
                $this->Amount = '9,00 USD';
                break;
            default:
                $this->Mercant = '3DS (3DS2 ACS) STAGING';
                $this->Amount = '10,00 USD';
                break;
        }
    }
/*Secure transaction with your personal data*/
    public function checkAllItems($pan){

        //  проверить  что в блоке есть 2е картинки
        $I=$this->acceptanceTester;
        $I->see(self::SECURETRANSACTION,$this->contentInfoArea);

        $this->checkItemInfo('Merchant',$this->Mercant);
        $this->checkItemInfo('Amount',$this->Amount);
        $this->checkItemInfo('Card number',$I->maskPan($pan));
        /*ПОДОГНАЛ ПОД КЕЙС ПОМЕНЯТЬ МЕСТАМИ  i:H*/
        $this->checkItemInfo('Date',$this->date);

        $I->see(self::INTERBIRTHDAY,$this->panelArea);
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
        sleep(2);
        $I->fillField($this->entBrthday,$bitrhDate);
        $I->seeElement($this->btnConfirm,['disabled'=>false]);
        $I->click($this->btnConfirm);
        $I->waitForElementVisible($this->entBrthday,10);

    }

/*'Verify by Phone*/
    public function checkAllItemsInVerify($data){

        //  проверить  что в блоке есть 2е картинки
        $I=$this->acceptanceTester;
        $I->waitForText(self::VERIFYBYPHONE,7,$this->contentInfoArea);

        $this->checkItemInfo('Merchant',$this->Mercant);
        $this->checkItemInfo('Amount',$this->Amount);
        $this->checkItemInfo('Card number',$I->maskPan($data->pan));
        /*ПОДОГНАЛ ПОД КЕЙС ПОМЕНЯТЬ МЕСТАМИ  i:H*/
        $this->checkItemInfo('Date',$this->date);

        $I->see(self::INTEROTP.substr($data->phone,-4)
           ,$this->panelArea);
        $I->seeElement($this->entOTP);

        $I->seeElement($this->btnConfirm,['disabled'=>true]);
        $I->dontSee('You entered an invalid password. 2 attempt(s) left.',$this->contentErr);
        $I->seeElement($this->exitLink);
        $I->seeElement($this->helpLink);
        $I->seeElement($this->timerResend);

    }
    public function inputOTP($OTPCode){
        sleep(3);
        $I=$this->acceptanceTester;
        $I->waitForElement($this->btnConfirm,10);
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
        $I->waitForElementVisible($this->btnConfirm,10);
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
