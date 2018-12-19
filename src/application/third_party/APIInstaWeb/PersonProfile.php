<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ApiInstaWeb {


    /**
     * Description of PersonProfile
     *
     * @author dumbu
     */
    class PersonProfile extends ReferenceProfile {

        //put your code here
        //begin ReferenceProfile
        protected function get_insta_prof_data(\stdClass $cookies = NULL) {
            
        }

        protected function make_curl_str(\stdClass $cookies, int $N, string $cursor = NULL, Proxy $proxy = NULL) {
            $csrftoken = $login_data->csrftoken;
            $ds_user_id = $login_data->ds_user_id;
            $sessionid = $login_data->sessionid;
            $mid = $login_data->mid;
            if (($csrftoken === NULL || $csrftoken === "") && ($ds_user_id === NULL || $ds_user_id === "") &&
                    ($sessionid === NULL || $sessionid === "") && ($mid === NULL || $mid === ""))
                return NULL;
            $url .= "?query_hash=c56ee0ae1f89cdbd1c89e2bc6b8f3d18&variables=";
            $variables = "{\"id\":\"$ds_user_id\",\"include_reel\":false,\"first\":$N";
            if ($cursor != NULL && $cursor != "NULL") {
                $variables .= ",\"after\":\"$cursor\"";
            }
            $variables .= "}";
            $url .= urlencode($variables);
            $curl_str = "curl $proxy '$url' ";
            $curl_str .= "-H 'Cookie: mid=$mid; sessionid=$sessionid; csrftoken=$csrftoken; ds_user_id=$ds_user_id' ";
            $curl_str .= "-H 'Origin: https://www.instagram.com' ";
            $curl_str .= "-H 'Accept-Encoding: gzip, deflate' ";
            $curl_str .= "-H 'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4' ";
            $curl_str .= "-H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0' ";
            $curl_str .= "-H 'X-Requested-with: XMLHttpRequest' ";
            $curl_str .= "-H 'X-CSRFToken: $csrftoken' ";
            $curl_str .= "-H 'content-type: application/x-www-form-urlencoded' ";
            $curl_str .= "-H 'Accept: */*' ";
            $curl_str .= "-H 'Referer: https://www.instagram.com/' ";
            $curl_str .= "-H 'Authority: www.instagram.com' ";
            $curl_str .= "--compressed ";
            return $curl_str;
        }

        protected function process_insta_prof_data(\stdClass $content) {
            
        }

        public function get_insta_followers(\stdClass $cookies = NULL, int $N = 15, string $cursor = NULL, Proxy $proxy = NULL) {
            
        }

        public function get_insta_media(\stdClass $cookies = NULL, int $N = 15, string $cursor = NULL, Proxy $proxy = NULL) {
            
        }

        public function get_post_user_info($post_reference, \stdClass $cookies = NULL, Proxy $proxy = NULL) {
            
        }

        //end ReferenceProfile

        protected function make_curl_following_str(\stdClass $cookies, int $N, string $cursor = NULL, Proxy $proxy = NULL) {
            
        }

        //pasar para InstaPRofile as static function
        private function parse_follow_count($follow_count_str) {
            
        }

        //pasar para InstaProfile
        public function get_insta_following_count() {
            
        }

        //pasar para InstaProfile
        public function get_reference_data(\stdClass $cookies, string $referense_name) {
            
        }

        //pasar para InstaProfile
        public function exists_profile(string $profile_name, ProfileType $type, string $insta_id = NULL, \stdClass $cookies = NULL, Proxy $proxy = NULL) {
            
        }

    }

}
