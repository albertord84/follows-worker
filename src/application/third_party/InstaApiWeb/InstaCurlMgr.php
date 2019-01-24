<?php

namespace InstaApiWeb {
  
  require_once config_item('business-proxy-class');
  require_once config_item('business-cookies_request-class');
  
  use business\Proxy;
  use business\CookiesRequest;
  
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
  abstract class EnumBase
  {
    protected $enumValue;
    
    public function __construct(int $value) {
      $this->enumValue = $value;
    }
    
    public function getEnumValue(){
      return $this->enumValue;
    }
  
  }
  
  class EnumEntity extends EnumBase {
    
    const PERSON = 1;
    const HASHTAG = 2;
    const GEOLOCALIZATION = 3;
    const CLIENT = 4;

    public function __construct(int $value) {
      parent::__construct($value);     
    }
  
    function __toString(){      
      switch ($this->enumValue)
      {
        case 1: $str = "PERSON"; break;
        case 2: $str = "HASHTAG"; break;
        case 3: $str = "GEOLOCALIZATION"; break;
        case 4: $str = "CLIENT"; break;
      }
      
      return $str;
    }
    
  }
  
  class EnumAction extends EnumBase {
 
    const CMD_LOGIN = 1;
    const CMD_LIKE = 2;
    const CMD_FOLLOW= 3;
    const CMD_UNFOLLOW = 4;
    const CMD_CHECKPOINT = 5;
    const GET_POST = 6;
    const GET_FIRST_POST = 7;
    const GET_FOLLOWERS = 8;
    const GET_USER_INFO_POST = 9;
    const GET_PROFILE_INFO = 10;
    const GET_CHALLENGE_CODE = 11;
 
    public function __construct(int $value) {
      parent::__construct($value);     
    }
    
    function __toString(){            
      switch ($this->enumValue)
      {
        case 1: $str = "CMD_LOGIN"; break;
        case 2: $str = "CMD_LIKE"; break;
        case 3: $str = "CMD_FOLLOW"; break;
        case 4: $str = "CMD_UNFOLLOW"; break;
        case 5: $str = "CMD_CHECKPOINT"; break;
        case 6: $str = "GET_POST"; break;
        case 7: $str = "GET_FIRST_POST"; break;
        case 8: $str = "GET_FOLLOWERS"; break;
        case 9: $str = "GET_USER_INFO_POST"; break;
        case 10: $str = "GET_PROFILE_INFO"; break;
        case 11: $str = "GET_CHALLENGE_CODE"; break;
      }
      
      return $str;
    }
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
    private $MediaStr; 
    private $Headers = array(array());
    private $InstaURL = array(array());
    private $TagQuery = array(array());
    
    public function __construct(EnumEntity $profile, EnumAction $action) {
      
      $this->MediaStr = null;
      $this->ProfileType = $profile;
      $this->ActionType = $action;
           
      /* Instagram Base URLs */
      $this->InstaURL['Base']      = "https://www.instagram.com";
      $this->InstaURL['Graphql']   = "https://www.instagram.com/graphql/query/";
      $this->InstaURL['TopSearch'] = "https://www.instagram.com/web/search/topsearch/";
      
      /* Instagram cUrl Headers definitions */
      $this->Headers['X-Post']           = "-X POST";
      $this->Headers['Cookie']           = "-H 'Cookie: mid=%s; sessionid=%s;  csrftoken=%s; ds_user_id=%s'";
      $this->Headers['Origin']           = "-H 'Origin: https://www.instagram.com'";
      $this->Headers['AcceptEncoding']   = "-H 'Accept-Encoding: gzip, deflate'";
      $this->Headers['AcceptLanguage']   = "-H 'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4'";
      $this->Headers['UserAgent']        = "-H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0'";
      $this->Headers['X-Requested']      = "-H 'X-Requested-with: XMLHttpRequest'";
      $this->Headers['ContentType']      = "-H 'content-type: application/x-www-form-urlencoded'";
      $this->Headers['Accept']           = "-H 'Accept: */*'";
      $this->Headers['Referer']          = "-H 'Referer: https://www.instagram.com/'";
      $this->Headers['Authority']        = "-H 'Authority: www.instagram.com'";
      $this->Headers['X-CSRFToken']      = "-H 'X-CSRFToken: %s'";
      $this->Headers['X-Instagram-Ajax'] = "-H 'X-Instagram-Ajax: dad8d866382b'";
      $this->Headers['ContentLength']    = "-H 'Content-Length: 0'";  
      $this->Headers['compressed']       = "--compressed";      
      
      /* Instagram Hash-query definitions*/
      $this->TagQuery['Geo']     = "ac38b90f0f3981c42092016a37c59bf7";
      $this->TagQuery['HashTag'] = "ded47faa9a1aaded10161a2ff32abb6b";
      $this->TagQuery['Person']  = "37479f2b8209594dde7facb0d904896a";
        
           
      // Singlenton CI
      //$ci = &get_instance();
    }

    public function setMediaData (string $id, int $first, string $cursor) {
      /*// GEO-PROFILE
      $variables = "{\"id\":\"$this->insta_id\",\"first\":$N\"";
        if ($cursor != NULL && $cursor != "NULL") {
          $variables .= ",\"after\":\"$cursor\"";
        }
        $variables .= "}";
        
      // HASH-PROFILE
      $variables = "{\"tag_name\":\"$tag\",\"first\":2";
        if ($cursor != NULL && $cursor != "NULL") {
          $variables .= ",\"after\":\"$cursor\"";
        }
        $variables .= "}";
        
      // INSTA-CLIENTE.make_curl_chaining_str()
      $variables = "{\"id\":\"$insta_id\",\"first\":$N";
      if ($cursor != NULL && $cursor != "NULL") {
        $variables .= ",\"after\":\"$cursor\"";
      }
      $variables .= "}";*/
      
      $tag = ($this->ProfileType->getEnumValue() == EnumEntity::HASHTAG) ? "tag_name" : "id";
      $str_cur = ($cursor != null) ? sprintf(",\"after\":\"%s\"", $cursor) : "";
      $this->MediaStr = sprintf("{\"%s\":\"%s\",\"first\":\"%s\"%s}", $tag, $id, $first, $str_cur); 
      echo "<br>".$this->MediaStr;
      
    }
    
    public function make_curl_str(Proxy $proxy, CookiesRequest $cookies = NULL) {
      $str = sprintf("%s %s %s %s %s %s %s %s %s %s", 
        $this->Headers['Origin'],
        $this->Headers['AcceptEncoding'], 
        $this->Headers['AcceptLanguage'], 
        $this->Headers['UserAgent'], 
        $this->Headers['XRequested'],
        $this->Headers['ContentType'],
        $this->Headers['Accept'],
        $this->Headers['Referer'],
        $this->Headers['Authority'],
        $this->Headers['compressed']);
      
      return $str;
    }

    public function make_curl_obj(Proxy $proxy, CookiesRequest $cookies = NULL) {
      
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
      }*/
    
    
  }

}
