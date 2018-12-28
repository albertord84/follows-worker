<?php

class HashProfileLib{

    /*    public function __construct() {
         $this->tag_query = "ded47faa9a1aaded10161a2ff32abb6b";
     }
    */
    
        protected function process_insta_prof_data(\stdClass $content) {
             $ApiInstaWeb = new ApiInstaWeb\HashProfile();
             $ApiInstaWeb->process_insta_prof_data($content);
        }

        public function get_insta_followers(\stdClass $cookies = NULL, int $N = 15, string& $cursor = NULL, \business\cls\Proxy $proxy = NULL) {
             $ApiInstaWeb = new ApiInstaWeb\HashProfile();
             $ApiInstaWeb->get_insta_followers($cookies, $N, $cursor, $proxy);
        } 

        public function get_insta_media(\stdClass $cookies = NULL, int $N = 15, string $cursor = NULL, \business\cls\Proxy $proxy = NULL) {
             $ApiInstaWeb = new ApiInstaWeb\HashProfile();
             $ApiInstaWeb->get_insta_media($cookies, $N, $cursor, $proxy);
        }

        public function get_post_user_info($post_reference, \stdClass $cookies = NULL, \business\cls\Proxy $proxy = NULL) {
             $ApiInstaWeb = new ApiInstaWeb\HashProfile();
             $ApiInstaWeb->get_post_user_info($post_reference, $cookies, $proxy);
        }

}