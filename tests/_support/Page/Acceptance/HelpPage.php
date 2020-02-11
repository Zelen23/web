<?php
namespace Page\Acceptance;

class HelpPage
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

    public $bBack='//*[@id="back_help"]';
    public $content='//*[@id="help"]/div';
    public $helpLink='//*[@id="help_button"]';

    const HEADTEXT="If you have any questions, please",
          SUPPORTTEXT="contact Support by calling",
          SUPPORTNUMBER="+7 964 725 15 55",
          SUPPORTMAILTEXT="or send an email to",
          SUPPORTMAIL="25mm@drugov.com";



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

    public function checkHelpContentAndBack(){
        $I=$this->acceptanceTester;
        $I->click($this->helpLink);
        $I->waitForElement($this->bBack,5);
        $I->see( self::HEADTEXT,$this->content);
        $I->see( self::SUPPORTTEXT,$this->content);
        $I->see( self::SUPPORTNUMBER,$this->content);
        $I->see( self::SUPPORTMAILTEXT,$this->content);
        $I->see( self::SUPPORTMAIL,$this->content);

        $I->click($this->bBack);
        $I->waitForElement($this->helpLink,5);

    }

}
