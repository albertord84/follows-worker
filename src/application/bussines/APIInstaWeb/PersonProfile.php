<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ApiInstaWeb
{


    /**
     * Description of PersonProfile
     *
     * @author dumbu
     */
    class PersonProfile extends ReferenceProfile{
        //put your code here

        //begin ReferenceProfile
        protected function get_insta_prof_data(\stdClass $cookies = NULL) {

        }

        protected function make_curl_str(\stdClass $cookies, int $N, string $cursor = NULL, string $proxy = "") {

        }

        protected function process_insta_prof_data(\stdClass $content) {

        }

        public function get_insta_followers(\stdClass $cookies = NULL, int $N = 15, string $cursor = NULL, string $proxy = "") {

        }

        public function get_insta_media(\stdClass $cookies = NULL, int $N = 15, string $cursor = NULL, string $proxy = "") {

        }

        public function get_post_user_info($post_reference, \stdClass $cookies = NULL, string $proxy = NULL) {
            
        }

         //end ReferenceProfile

        protected function make_curl_following_str(\stdClass $cookies, int $N, string $cursor = NULL, string $proxy = "") {

        }

        //pasar para InstaPRofile as static function
        private function parse_follow_count($follow_count_str){}

        //pasar para InstaProfile
        public function get_insta_following_count(){}

        //pasar para InstaProfile
        public function get_reference_data(\stdClass $cookies, string $referense_name){}

        //pasar para InstaProfile
        public function exists_profile(string $profile_name, ProfileType $type, string $insta_id=NULL, \stdClass $cookies=NULL, string $proxy = ""){}
      

    }
}
