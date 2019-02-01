<?php

namespace InstaApiWeb {
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

      protected $tag_query;

      public function __construct() {
            $this->insta_id = 212673249;            
            require_once config_item('thirdparty-insta_api-resource');
            require_once config_item('thirdparty-insta_curl_mgr-resource');      
            require_once config_item('thirdparty-cookies');
            //require_once config_item('thirdparty-proxy');
      }
    
      protected static function get_insta_data_from_client($ref_prof, \stdClass $cookies, $proxy = NULL) {
          if ($ref_prof == "" || $ref_prof == NULL) {
              throw new \Exception("This was and empty or null referece profile (ref_prof)");
          }
          $csrftoken = isset($cookies->csrftoken) ? $cookies->csrftoken : 0;
          $ds_user_id = isset($cookies->ds_user_id) ? $cookies->ds_user_id : 0;
          $sessionid = isset($cookies->sessionid) ? $cookies->sessionid : 0;
          $mid = isset($cookies->mid) ? $cookies->mid : 0;
          $headers = array();
          $headers[] = "Host: www.instagram.com";
          $headers[] = "User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0";
          $headers[] = "Accept: */*";
          $headers[] = "Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4";
          $headers[] = "Accept-Encoding: deflate, sdch";
          $headers[] = "Referer: https://www.instagram.com/";
          $headers[] = "Content-Type: application/x-www-form-urlencoded";
          $headers[] = "X-Requested-With: XMLHttpRequest";
          $headers[] = "Authority: www.instagram.com";
          if ($cookies != NULL) {
              $headers[] = "X-CSRFToken: $csrftoken";
              $headers[] = "Cookie: mid=$mid; sessionid=$sessionid; s_network=; ig_pr=1; ig_vw=1855; csrftoken=$csrftoken; ds_user_id=$ds_user_id";
          }
          $url = InstaURLs::TopSearch;
          $url .= "?context=blended&query=$ref_prof";
          $ch = curl_init(InstaURLs::Instagram);
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_HEADER, FALSE);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

          if ($proxy != NULL) {
              curl_setopt($ch, CURLOPT_PROXY, $proxy->ip);
              curl_setopt($ch, CURLOPT_PROXYPORT, $proxy->port);
              curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
              curl_setopt($ch, CURLOPT_PROXYUSERPWD, "$proxy->user:$proxy->password");
          }
          $output = curl_exec($ch);
          //$string = curl_error($ch);
          curl_close($ch);
          return json_decode($output);
      }

     public function get_insta_prof_data(\stdClass $cookies=NULL, Proxy $proxy = NULL)
     {
        try {
          $Profile = NULL;
          $content = ReferenceProfile::get_insta_data_from_client($this->insta_name, $cookies, $proxy);
          $Profile = $this->process_insta_prof_data($content);
          return $Profile;
        } catch (\Exception $ex) {
          if($this->has_logs)
          {
              print_r($ex->message);
          }
          return NULL;
        }
      }

     abstract protected function  process_insta_prof_data(\stdClass $content);   

     abstract public function get_insta_followers(\stdClass $cookies = NULL, int $N = 15, string& $cursor = NULL, Proxy $proxy = NULL);

     abstract public function get_insta_media(int $N, string $cursor = NULL, CookiesRequest $cookies = NULL, Proxy $proxy = NULL);

     //Debe ser pasada para una clase de Post
     abstract public function get_post_user_info($post_reference, \stdClass  $cookies = NULL, Proxy $proxy = NULL);

     public function TurnOn_Logs(){ $has_logs = TRUE; }

     public function TurnOff_Logs(){ $has_logs = FALSE; }
  }

}