<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ApiInstaWeb;

/**
 * Description of ReferenceProfile
 *
 * @author dumbu
 */
abstract class ReferenceProfile {
    //not need
    //public $id
    
    public $insta_id;
    
    public $insta_name;
    
    public $unfollowed;
    
    protected $has_logs = TRUE;
    
   abstract protected function  process_insta_prof_data(\stdClass $content);
   
   abstract protected function get_insta_prof_data(\stdClass $cookies=NULL);
   
   abstract protected function make_curl_str(\stdClass $cookies, int $N, string $cursor=NULL, string $proxy="");   
   
   abstract public function get_insta_followers(\stdClass $cookies = NULL, int $N = 15, string $cursor = NULL, string $proxy = "");
   
   abstract public function get_insta_media(\stdClass $cookies = NULL, int $N = 15, string $cursor = NULL, string $proxy = "");
   
   //Debe ser pasada para una clase de Post
   abstract public function get_post_user_info($post_reference, \stdClass  $cookies = NULL, string $proxy = NULL);
      
   public function TurnOn_Logs(){ $has_logs = TRUE; }
   
   public function TurnOff_Logs(){ $has_logs = FALSE; }
}
