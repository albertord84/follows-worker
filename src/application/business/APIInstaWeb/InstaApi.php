<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ApiInstaWeb
{

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

        }
        
        public function make_insta_friendships_command(string $resource_id, \stdClass $cookies= NULL, string $command = 'follow', string $objetive_url = 'web/friendships', &$curl_str = "", Proxy $proxy = NULL) 
        {}
        
        
        public function get_insta_chaining(\stdClass $cookies= NULL, int $N = 1, string $cursor = NULL,Proxy  $proxy = NULL){}
        

    }
}
