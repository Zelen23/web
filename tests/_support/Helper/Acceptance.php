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
        //
        $data=gmdate('i:H d/m/Y',time());
    }
    function _getOTPByNumber($phonenum){
        $dbh = $this->getModule('Db');
        $data=gmdate('Y-m-d H:i:s',time());
        $jsonTxt=$dbh->grabFromDatabase('hs.sms_info','json_txt',[
            'phone'=>$phonenum,
            'to_send_time >='=>$data
            ]
        );
        $jtxt=(preg_replace("/;/",",",$jsonTxt));
        $decodeJson=json_decode($jtxt);
        print_r($data);
        print_r($decodeJson->password);
        return (isset($decodeJson->password)?$decodeJson->password:"111111");

    }
    function getOTPByNumber($phonenum)
    {

        $sql="SELECT json_txt
        FROM hs.sms_info where
        phone = '$phonenum'
        order by add_time desc limit 1";
        $dbh = $this->getModule('Db')->dbh;
        $this->debugSection('Query', $sql);
        $sth = $dbh->prepare($sql);

        $exec=$sth->execute();

        if ($exec){
            $jsonTxt= $sth->fetchAll()[0]['json_txt'];
            $jtxt=(preg_replace("/;/",",",$jsonTxt));
            $decodeJson=json_decode($jtxt);
            print_r($decodeJson->password."\r\n");

            return $decodeJson->password;
        }else{
            return "111112";
        }



    }

}
