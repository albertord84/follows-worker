<?php


class GeoProfileLib{

    /*    public function __construct() {
         $this->tag_query = "ac38b90f0f3981c42092016a37c59bf7";
     }
    */
    
        protected function process_insta_prof_data(\stdClass $content) {
             $ApiInstaWeb = new ApiInstaWeb\GeoProfile();
             $ApiInstaWeb->process_insta_prof_data($content);
        }

        public function get_insta_followers(\stdClass $cookies = NULL, int $N = 15, string& $cursor = NULL, \business\cls\Proxy $proxy = NULL) {
             $ApiInstaWeb = new ApiInstaWeb\GeoProfile();
             $ApiInstaWeb->get_insta_followers($cookies, $N, $cursor, $proxy);
        } 

        public function get_insta_media(\stdClass $cookies = NULL, int $N = 15, string $cursor = NULL, \business\cls\Proxy $proxy = NULL) {
             $ApiInstaWeb = new ApiInstaWeb\GeoProfile();
             $ApiInstaWeb->get_insta_media($cookies, $N, $cursor, $proxy);
        }

        public function get_post_user_info($post_reference, \stdClass $cookies = NULL, \business\cls\Proxy $proxy = NULL) {
             $ApiInstaWeb = new ApiInstaWeb\GeoProfile();
             $ApiInstaWeb->get_post_user_info($post_reference, $cookies, $proxy);
        }

}