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
 
        
        public function make_insta_friendships_command(string $resource_id, \stdClass $cookies= NULL, string $command = 'follow', string $objetive_url = 'web/friendships', Proxy $proxy = NULL) 
        {
            $insta = InstaURLs::Instagram;
            $curl_str = $this->make_curl_friendships_command_str("'$insta/$objetive_url/$resource_id/$command/'", $cookies, $proxy);
            
            exec($curl_str, $output, $status);
            $error = false;
            if (is_array($output) && count($output)) { // Retorna un arreglo con elementos
                $json_response = json_decode($output[count($output) - 1]);
                if ($json_response && (isset($json_response->result) || (isset($json_response->status) && $json_response->status === 'ok'))) {
                    return $json_response;
                } else {
                    if($this->has_logs)
                    {
                        echo "status fail in command $command from function make_insta_friendships_command\n";                     
                        var_dump($output);
                        var_dump($curl_str);
                    }
                    return $json_response;
                }
            } else if (is_array($output)) { // Retorno un arreglo vacio   
                if($this->has_logs)  echo "array empty in command $command from function make_insta_friendships_command\n";
                $error = true;
            } else {  // Retornar diferente de arreglo
                if($this->has_logs) echo "unknown error in command $command from function make_insta_friendships_command\n";
                $error = true;
            }

            if ($this->has_logs) {
                var_dump($output);
                var_dump($curl_str);
            }

            return $output;
        }
        
        private function make_curl_friendships_command_str(string $url) {
            if($cookies != NULL)
            {
                $csrftoken = $cookies->csrftoken;
                $ds_user_id = $cookies->ds_user_id;
                $sessionid = $cookies->sessionid;
                $mid = $cookies->mid;
                if (($csrftoken === NULL || $csrftoken === "") && ($ds_user_id === NULL || $ds_user_id === "") &&
                        ($sessionid === NULL || sessionid === "") && ($mid === NULL || $mid === ""))
                    return NULL;
            }
            $proxy_str = "";
            if($proxy != NULL)
                $proxy->ToString();
            $curl_str = "curl $proxy_str  $url ";
            $curl_str .= "-X POST ";
            $curl_str .= "-H 'Cookie: mid=$mid; sessionid=$sessionid;  csrftoken=$csrftoken; ds_user_id=$ds_user_id' ";
            $curl_str .= "-H 'origin: www.instagram.com' ";
            $curl_str .= "-H 'Accept-Encoding: gzip, deflate' ";
            $curl_str .= "-H 'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4' ";
            $curl_str .= "-H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0' ";
            $curl_str .= "-H 'X-Requested-with: XMLHttpRequest' ";
            $curl_str .= "-H 'X-CSRFToken: $csrftoken' ";
            $curl_str .= "-H 'X-Instagram-Ajax: dad8d866382b' ";
            $curl_str .= "-H 'Content-Type: application/x-www-form-urlencoded' ";
            $curl_str .= "-H 'Accept: */*' ";
            $curl_str .= "-H 'Referer: https://www.instagram.com/' ";
            $curl_str .= "-H 'Authority: www.instagram.com' ";
            $curl_str .= "-H 'Content-Length: 0' ";
            $curl_str .= "--compressed ";
            
            return $curl_str;
        }

                
        public function get_insta_chaining(int $N = 1, string $cursor = NULL)
        {
               
                $curl_str = $this->make_curl_chaining_str($N, $cursor, $proxy);
                if ($curl_str === NULL) {
                    if($has_logs){ var_dump("error in cookies line 708 function get_insta_chaining \n");}
                    return NULL;
                }
               
                exec($curl_str, $output, $status);
                $json = json_decode($output[0]);
               
                if (isset($json->data->user->edge_owner_to_timeline_media) && isset($json->data->user->edge_owner_to_timeline_media->edges)) {
                    return $json->data->user->edge_owner_to_timeline_media->edges;
                }
                if($has_logs)
                {
                    echo "Message Error in get_insta_chaining</br>\n";
                    var_dump($output);
                    var_dump($curl_str);
                }
                return FALSE;           
        }
        
        private function  make_curl_chaining_str(int $N, string $cursor = NULL)
        {
            $query = "bd0d6d184eefd4d0ce7036c11ae58ed9";
            $variables = "{\"id\":\"$user\",\"first\":$N";
            if ($cursor != NULL && $cursor != "NULL") {
                $variables .= ",\"after\":\"$cursor\"";
            }
            $variables .= "}";

            $curl_str = InstaApi::make_query($query, $variables, $cookies, $proxy);

            return $curl_str;
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