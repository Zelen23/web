<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Acceptance extends \Codeception\Module
{

    /**
     * @param $pan
     * @return string
     */
    function maskPan($pan){
        //4785299000235458
        //'4785 **** **** 5458'

        return substr($pan,0,4).
        ' **** **** '.
        substr($pan,-4);


    }
    function generateDate(){
        // желательно получить время из сервака ACS
        // установить его
        //привести к формату
        $data=date('h:i d/m/Y',time());
    }

}
