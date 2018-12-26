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
        protected function make_curl_str(\stdClass $cookies, int $N, string $cursor = NULL, Proxy $proxy = NULL) {

        }

        protected function process_insta_prof_data(\stdClass $content) {
            $Profile = NULL;
            if (is_object($content) && $content->status === 'ok') {
                $users = $content->users;
                // Get user with $ref_prof name over all matchs 
                if (is_array($users)) {
                    for ($i = 0; $i < count($users); $i++) {
                        if ($users[$i]->user->username === $ref_prof) {
                            $Profile = $users[$i]->user;
                            //var_dump($Profile);
                          //  $Profile->follows = $this->get_insta_ref_prof_follows($ref_prof_id);
                            $Profile->following = $this->get_insta_following_count($ref_prof);
                            if (!isset($Profile->follower_count)) {
                                $Profile->follower_count = isset($Profile->byline) ? $this->parse_follow_count($Profile->byline) : 0;
                            }
                            break;
                        }
                    }
                }
            } else {
                //var_dump($content);
                //var_dump("null reference profile!!!");
            }
            return $Profile;
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
        private function parse_follow_count($follow_count_str){}

        //pasar para InstaProfile
        public function get_insta_following_count(){}

        //pasar para InstaProfile
        public function get_reference_data(\stdClass $cookies, string $referense_name){}

        //pasar para InstaProfile
        public function exists_profile(string $profile_name, ProfileType $type, string $insta_id=NULL, \stdClass $cookies=NULL, Proxy $proxy = NULL){}
      

    }
}
