<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ApiInstaWeb
{

    /**
     * Description of HashProfile
     *
     * @author dumbu
     */
    class HashProfile extends ReferenceProfile{

        //begin ReferenceProfile
         protected function get_insta_prof_data(\stdClass $cookies = NULL) {

        }

        protected function make_curl_str(\stdClass $cookies, int $N, string $cursor = NULL, Proxy $proxy = NULL) {

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

    }
}
