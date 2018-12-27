<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ApiInstaWeb;

/**
 * Description of InstaProfileList
 *
 * @author jose
 */
class InstaProfileList {
    
    public $profile_list;
    
    public function __construct() {
        $this->profile_list = array();
    }
    
    public function get_list_from_insta_follower_list($response) {
        
        foreach ($response as $edge)
        {
            if(isset($edge->node))
            {
                $insta_prof = new InstaProfile($edge->node);
                array_push($this->profile_list, $insta_prof);
            }            
        }
    }
    //put your code here
}
