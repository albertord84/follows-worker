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
    
    function __toString(){
        return (string)$this->enumValue;
    }
  }
  
  class EnumProfile extends EnumBase {
    
    const PERSON = 1;
    const HASHTAG = 2;
    const GEOLOCALIZATION = 3;

    public function __construct(int $value) {
      parent::__construct($value);     
    }
   
  }
  
  class EnumAction extends EnumBase {
 
    const LOGIN = 1;
    const LIKE = 2;
    const FOLLOW= 3;
    const UNFOLLOW = 4;
    const GET_FOLLOWERS = 5;
    const GET_RPOFILE = 6;
    const GET_POST = 7;
 
    public function __construct(int $value) {
      parent::__construct($value);     
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
    private $Headers = array(array());
    
    public function __construct(EnumProfile $profile, EnumAction $action) {
      
      $this->ProfileType = $profile->getEnumValue();
      $this->ActionType = $action->getEnumValue();
      
      /*       
      const Instagram = 'https://www.instagram.com';
      const GraphqlQuery = '"https://www.instagram.com/graphql/query/"';
      const MakePost = '';
      const TopSearch = '"https://www.instagram.com/web/search/topsearch/';
      */
      
      /* Instagram cUrl Headers definitions */
      $this->Headers['Origin']         = "-H 'Origin: https://www.instagram.com'";
      $this->Headers['AcceptEncoding'] = "-H 'Accept-Encoding: gzip, deflate'";
      $this->Headers['AcceptLanguage'] = "-H 'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4'";
      $this->Headers['UserAgent']      = "-H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0'";
      $this->Headers['XRequested']     = "-H 'X-Requested-with: XMLHttpRequest'";
      $this->Headers['ContentType']    = "-H 'content-type: application/x-www-form-urlencoded'";
      $this->Headers['Accept']         = "-H 'Accept: */*'";
      $this->Headers['Referer']        = "-H 'Referer: https://www.instagram.com/'";
      $this->Headers['Authority']      = "-H 'Authority: www.instagram.com'";
      $this->Headers['compressed']     = "--compressed";
      $this->Headers[''] = "";
            
      $ci = &get_instance();
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
