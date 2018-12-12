<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ApiInstaWeb
{

    require_once 'ApiInstaWeb/Exceptions';
    /**
     * Description of InstaClient
     *
     * @author dumbu
     */
    class InstaClient {

        public $cookies;

        public $insta_id;

        public $proxy;
        
        private $has_logs;

        public function __construct(string $insta_id, \stdClass $cookies, Proxy $proxy) {
            if(!InstaClient::verify_cookies($cookies))
            {
                throw  new Exceptions\CookiesWrongSyntaxException('the cookies you are passing are incompleate or wrong');
            }
            $this->insta_id = $insta_id;
            $this->cookies = $cookies;
            $this->proxy = $proxy;
            $has_log = TRUE;
        }
    //put your code here
 
        private function  make_curl_chaining_str(int $N, string $cursor = NULL)
        {
            $url = InstaURLs::ChainingURL;
        }

        private function obtine_cookie_value(string $name)
        {}

        private function get_cookies_value(string $key){}

        private function make_post()
        {
            $url = InstaURLs::MakePost;
        }

        private function get_insta_csrftoken($ch)
        {}

        public static function verify_cookies(\stdClass $cookies)
        {}

        public function make_insta_login()
        {}

        public function make_login(string $login, string $pass) 
        {}

        public function like_fist_post(string $fromClient_ista_id)
        {}

        public function curlResponseHeaderCallback($ch, string $headerLine)
        {}
        
        public function checkpoint_requested(string $login, string $pass)
        {
            
        }
                
        public function get_challenge_data(string $challenge, string $login, VerificationChoice $choice = 1)
        {}
    
        public function make_checkpoint(string $login, string $code)
        {}
        
        public function TurnOn_Logs(){ $has_logs = TRUE; }
   
        public function TurnOff_Logs(){ $has_logs = FALSE; }
    }
}