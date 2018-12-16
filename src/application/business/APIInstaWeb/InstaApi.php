<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ApiInstaWeb
{

    require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/externals/vendor/autoload.php';
    
    /**
     * Description of InstaApi
     *
     * @author dumbu
     */
    class InstaApi {
        //put your code here
        /*
         *  : stdClass{cookies}
    +make_insta_friendships_command($login_data, $resource_id, $command = 'follow', $objetive_url = 'web/friendships', $Client = NULL, &$curl_str = "") : InstaResponse
     +make_insta_friendships_command_client($Client, $resource_id, $command = 'follow', $objetive_url = 'web/friendships') : InstaResponse
     +make_curl_friendships_command_str($url, $login_data, $proxy = NULL, $Client = NULL) : STRING
     +get_insta_chaining($login_data, $user, $N = 1, $cursor = NULL, $proxy = ""):  InstaResponse
         */

        public function login(string $username, string $password, Proxy $proxy)
        {
            $debug = false;
            $truncatedDebug = true;
            //////////////////////

            \InstagramAPI\Instagram::$allowDangerousWebUsageAtMyOwnRisk = true;
            
            try {
                $ig = new \InstagramAPI\Instagram($debug, $truncatedDebug);
        
                
//                $ig->setOutputInterface("191.252.110.140");
                
                //$ig->setProxy(['proxy'=>'tcp://70.39.250.32:23128']);
                $ig->setProxy("http://". $proxy->ToString());
                //$ig->setProxy("http://albertreye9917:3r4rcz0b1v@207.188.155.18:21316");

                $loginResponse = $ig->login($username, $password);
                
                $ig->client->loadCookieJar();
                //var_dump($ig);

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
                $source = 0;
                if (isset($id) && $id !== NULL && $id !== 0)
                    $source = 1;
                $myDB->InsertEventToWashdog($Client->id, $e->getMessage(), $source);
                $result->json_response->authenticated = false;
                $result->json_response->status = 'ok';                

                if ((strpos($e->getMessage(), 'Challenge required') !== FALSE) || (strpos($e->getMessage(), 'Checkpoint required') !== FALSE) || (strpos($e->getMessage(), 'challenge_required') !== FALSE)) {
                    $res = $exc->getResponse()->getChallenge()->getApiPath();
                    throw new Exceptions\InstaCheckpointRequiredException($e->getMessage(),$e,$res); 
                } else if (strpos($e->getMessage(), 'Network: CURL error 28') !== FALSE) { // Time out by bad proxy
                    $proxy_id = ($proxy->idProxy) % 8 + 1;
                    $myDB->SetProxyToClient($Client->id, $proxy_id);
                } else if (strpos($e->getMessage(), 'password you entered is incorrect') !== FALSE)
                    $result->json_response->message = 'incorrect_password';
                else if (strpos($e->getMessage(), 'there was a problem with your request') !== FALSE)
                    $result->json_response->message = 'problem_with_your_request';
                else
                    $result->json_response->message = $e->getMessage();
                return $result;
                
            }
        }
        
        public function make_insta_friendships_command(string $resource_id, \stdClass $cookies= NULL, string $command = 'follow', string $objetive_url = 'web/friendships', &$curl_str = "", Proxy $proxy = NULL) 
        {}
        
        
        public function get_insta_chaining(\stdClass $cookies= NULL, int $N = 1, string $cursor = NULL,Proxy  $proxy = NULL){}
        

    }
}
