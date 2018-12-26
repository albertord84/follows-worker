<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ApiInstaWeb
{

    /**
     * Description of GeoProfile
     *
     * @author dumbu
     */
    class GeoProfile extends ReferenceProfile {

         //begin ReferenceProfile
        protected function make_curl_str(\stdClass $cookies, int $N, string $cursor = NULL, Proxy $proxy = NULL) {

        }

        protected function process_insta_prof_data(\stdClass $content) {

             $Profile = NULL;
            if (is_object($content) && $content->status === 'ok') {
                $places = $content->places;
                // Get user with $ref_prof name over all matchs 
                if (is_array($places)) {
                    for ($i = 0; $i < count($places); $i++) {
                        if ($places[$i]->place->slug === $ref_prof) {
                            $Profile = $places[$i]->place;
                           // $Profile->follows = $this->get_insta_ref_prof_follows($ref_prof_id);
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

    }
}