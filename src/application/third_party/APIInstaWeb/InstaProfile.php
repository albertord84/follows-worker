<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ApiInstaWeb{
     require_once \ApiInstaWeb\Responses;
    /**
     * Description of InstaProfile
     *
     * @author dumbu
     */
    class InstaProfile {
        //put your code here
        public $insta_id;

        public $insta_name;

        public $follows;

        public $following;

        public $image_url;

        public $instaProfileData;

        public function __construct(\stdClass $response) {

        }

    }
}