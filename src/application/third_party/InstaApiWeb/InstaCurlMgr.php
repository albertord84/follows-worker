<?php

namespace InstaApiWeb {
  
  use stdClass;
  
  /**
   * 
   * Define enum for Instagram Actions
   * 
   * @category Third-Party Instagram API
   * 
   * @access public
   *.
   * 
   */
  class InstaProfileType {
    const PERSON = 1;
    const HASHTAG = 2;
    const GEOLOCALIZATION = 3;
  }
  
   class InstaActionType {
    const LOGIN = 1;
    const LIKE = 2;
    const FOLLOW= 3;
    const UNFOLLOW = 4;
    const GET_FOLLOWERS = 5;
    const GET_RPOFILE = 6;
    const GET_POST = 7;
  }
  
  /**
   * 
   * Define
   * 
   * @category Third-Party Instagram API
   * 
   * @access public
   *.
   * 
   */
  class InstaCurlMgr {
    private $Config;
    
    private $ActionType;
    private $ProfileType;
    
    private $Headers = array(array());
    
    public function __construct(InstaProfileType $profile, InstaActionType $action) {
      require_once config_item('');
      
      $this->ActionType = $action;
      $this->ProfileType = $profile;
             
      $Headers['Origin'] = "-H 'Origin: https://www.instagram.com' ";
      
      $ci = &get_instance();
    }

    public static function make_curl(Proxy $proxy, stdClass $cookies = NULL) {

    }

    
    private function action_instagram ()
    {
      
    }
    
    /*
     * $variables = urlencode($variables);
      $graphquery_url = InstaURLs::GraphqlQuery;
      $url = "$graphquery_url?query_hash=$query&variables=$variables";
      $proxy_str = $proxy->ToString();
      $curl_str = "curl $proxy_str '$url' ";
      if ($cookies !== NULL) {
        if ($cookies->mid == NULL || $cookies->csrftoken == NULL || $cookies->sessionid == NULL ||
          $cookies->ds_user_id == NULL)
          return NULL;
        $curl_str .= "-H 'Cookie: mid=$cookies->mid; sessionid=$cookies->sessionid; s_network=; ig_pr=1; ig_vw=1855; csrftoken=$cookies->csrftoken; ds_user_id=$cookies->ds_user_id' ";
        $curl_str .= "-H 'X-CSRFToken: $cookies->csrftoken' ";
      }

      //$cnf = new \follows\cls\system_config();
      $curl_str .= "-H 'Origin: https://www.instagram.com' ";
      $curl_str .= "-H 'Accept-Encoding: gzip, deflate' ";
      $curl_str .= "-H 'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4' ";
      //$curl_str .= "-H 'User-Agent: $cnf->CURL_USER_AGENT' ";
      $curl_str .= "-H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0' ";
      $curl_str .= "-H 'X-Requested-with: XMLHttpRequest' ";
      $curl_str .= "-H 'content-type: application/x-www-form-urlencoded' ";*/
      //$curl_str .= "-H 'Accept: */*' ";
      /*$curl_str .= "-H 'Referer: https://www.instagram.com/' ";
      $curl_str .= "-H 'Authority: www.instagram.com' ";
      $curl_str .= "--compressed ";
      return $curl_str;
     */
    
    
  }

}
