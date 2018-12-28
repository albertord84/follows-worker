<?php

class InstaProfileListLib {
    
   /* public function __construct() {
        $this->profile_list = array();
    }*/
    
    public function get_list_from_insta_follower_list($response) {
        $ApiInstaWeb = new ApiInstaWeb\InstaProfileListLib();
        $ApiInstaWeb->get_list_from_insta_follower_list($response);
    }
}
