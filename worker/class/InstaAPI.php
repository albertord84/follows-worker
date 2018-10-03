<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace follows\cls {
//    ini_set('xdebug.var_display_max_depth', 17);
//    ini_set('xdebug.var_display_max_children', 256);
//    ini_set('xdebug.var_display_max_data', 1024);

    require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/externals/vendor/autoload.php';
    //require_once '../../src/vendor/autoload.php'; //asi noooo, cojone

    /**
     * Description of InstaAPI
     *
     * @author albertord
     */
    class InstaAPI {

        public $Cookies = null;
        

        public function login($username, $password, $ip='207.188.155.18', $port='21316', $proxyuser='albertreye9917', $proxypass='3r4rcz0b1v') {
            /////// CONFIG ///////
            $debug = false;
            $truncatedDebug = true;
            //////////////////////

            \InstagramAPI\Instagram::$allowDangerousWebUsageAtMyOwnRisk = true;
            
            try {
                $ig = new \InstagramAPI\Instagram($debug, $truncatedDebug);
        
                
//                $ig->setOutputInterface("191.252.110.140");
                
                //$ig->setProxy(['proxy'=>'tcp://70.39.250.32:23128']);
                $ig->setProxy("http://$proxyuser:$proxypass@$ip:$port");
                //$ig->setProxy("http://albertreye9917:3r4rcz0b1v@207.188.155.18:21316");

                $loginResponse = $ig->login($username, $password);
                
                $ig->client->loadCookieJar();
                var_dump($ig);

                if ($loginResponse !== null && $loginResponse->isTwoFactorRequired()) {
                    $twoFactorIdentifier = $loginResponse->getTwoFactorInfo()->getTwoFactorIdentifier();

                    // The "STDIN" lets you paste the code via terminal for testing.
                    // You should replace this line with the logic you want.
                    // The verification code will be sent by Instagram via SMS.
                    $verificationCode = trim(fgets(STDIN));
                    $ig->finishTwoFactorLogin($verificationCode, $twoFactorIdentifier);
                }
                
                $Cookies = array();
                $loginResponse = array();
//                $loginResponse->Cookies = new \stdClass();
                $Cookies['sessionid'] =  $ig->client->getCookie('sessionid')->getValue();              
                $Cookies['csrftoken'] =  $ig->client->getCookie('csrftoken')->getValue();
                $Cookies['ds_user_id'] = $ig->client->getCookie('ds_user_id')->getValue();
                $Cookies['mid'] =  $ig->client->getCookie('mid')->getValue();
                $loginResponse['Cookies'] =(object)$Cookies;                
                return (object)$loginResponse;
                
            } catch (\Exception $e) {
                //echo '<br>Something went wrong: ' . $e->getMessage() . "\n</br>";
                //echo $e->getTraceAsString();                
                throw $e;
            }
        }

    }

}
