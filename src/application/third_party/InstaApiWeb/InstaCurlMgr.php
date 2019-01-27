<?php

namespace InstaApiWeb {
  
  require_once config_item('business-proxy-class');
  require_once config_item('business-cookies_request-class');
  require_once config_item('insta-curl-exception-class');
  
  use business\Proxy;
  use business\CookiesRequest;
  use InstaApiWeb\Exceptions\InstaCurlMediaException;
  use InstaApiWeb\Exceptions\InstaCurlActionException;
  use InstaApiWeb\Exceptions\InstaCurlArgumentException;
  
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
    
    const GEO = 1;
    const PERSON = 2;
    const HASHTAG = 4;
    const CLIENT = 8;

    private $TagQuery = array(array());
    
    public function __construct(int $value) {
      if ($value != EnumEntity::GEO && $value != EnumEntity::PERSON && 
          $value != EnumEntity::HASHTAG && $value != EnumEntity::CLIENT)
        throw new InstaCurlArgumentException("El valor de EnumEntity ($value) proporcionado es incorrecto");
      
      parent::__construct($value);  
      
      /* Instagram Hash-query definitions*/
      $this->TagQuery['Geo']     = "ac38b90f0f3981c42092016a37c59bf7";
      $this->TagQuery['Person']  = "37479f2b8209594dde7facb0d904896a";
      $this->TagQuery['HashTag'] = "ded47faa9a1aaded10161a2ff32abb6b";
    }
  
    public function getHashQuery () {
      $hash = null;
      switch ($this->enumValue){
        case EnumEntity::GEO    : $hash = $this->TagQuery['Geo'];     break;
        case EnumEntity::PERSON : $hash = $this->TagQuery['Person'];  break;
        case EnumEntity::HASHTAG: $hash = $this->TagQuery['HashTag']; break;
      }
      
      return $hash;
    }
      
    function __toString(){      
      switch ($this->enumValue)
      {
        case EnumEntity::GEO    : $str = "GEOLOCALIZATION"; break;
        case EnumEntity::PERSON : $str = "PERSON"; break;
        case EnumEntity::HASHTAG: $str = "HASHTAG"; break;
        case EnumEntity::CLIENT : $str = "CLIENT"; break;
      }
      
      return $str;
    }
    
  }
  
  class EnumAction extends EnumBase {
 
    //const CMD_LOGIN = 1;
    const CMD_LIKE = 1;
    const CMD_FOLLOW= 2;
    const CMD_UNFOLLOW = 4;
    const CMD_CHECKPOINT = 8;
    const GET_POST = 16;
    const GET_FIRST_POST = 32;
    const GET_FOLLOWERS = 64;
    const GET_USER_INFO_POST = 128;
    const GET_PROFILE_INFO = 256;
    const GET_CHALLENGE_CODE = 512;
 
    public function __construct(int $value) {
      if ($value != EnumAction::CMD_LIKE && $value != EnumAction::CMD_FOLLOW &&
          $value != EnumAction::CMD_UNFOLLOW && $value != EnumAction::CMD_CHECKPOINT &&
          $value != EnumAction::GET_POST && $value != EnumAction::GET_FIRST_POST &&
          $value != EnumAction::GET_FOLLOWERS && $value != EnumAction::GET_USER_INFO_POST &&
          $value != EnumAction::GET_PROFILE_INFO && $value != EnumAction::GET_CHALLENGE_CODE)
        throw new InstaCurlArgumentException("El valor de EnumAction ($value) proporcionado es incorrecto");
      
      parent::__construct($value);     
    }
    
    function __toString(){            
      switch ($this->enumValue)
      {
        //case EnumAction::CMD_LOGIN         : $str = "CMD_LOGIN"; break;
        case EnumAction::CMD_LIKE          : $str = "CMD_LIKE"; break;
        case EnumAction::CMD_FOLLOW        : $str = "CMD_FOLLOW"; break;
        case EnumAction::CMD_UNFOLLOW      : $str = "CMD_UNFOLLOW"; break;
        case EnumAction::CMD_CHECKPOINT    : $str = "CMD_CHECKPOINT"; break;
        case EnumAction::GET_POST          : $str = "GET_POST"; break;
        case EnumAction::GET_FIRST_POST    : $str = "GET_FIRST_POST"; break;
        case EnumAction::GET_FOLLOWERS      : $str = "GET_FOLLOWERS"; break;
        case EnumAction::GET_USER_INFO_POST: $str = "GET_USER_INFO_POST"; break;
        case EnumAction::GET_PROFILE_INFO  : $str = "GET_PROFILE_INFO"; break;
        case EnumAction::GET_CHALLENGE_CODE: $str = "GET_CHALLENGE_CODE"; break;
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
      $this->Headers['AcceptAll']        = "-H 'Accept: */*'";
      $this->Headers['AcceptJson']       = "-H 'Accept: application/json'";
      $this->Headers['Referer']          = "-H 'Referer: https://www.instagram.com/'";
      $this->Headers['Authority']        = "-H 'Authority: www.instagram.com'";
      $this->Headers['X-CSRFToken']      = "-H 'X-CSRFToken: %s'";
      $this->Headers['X-Instagram-Ajax'] = "-H 'X-Instagram-Ajax: dad8d866382b'";
      $this->Headers['ContentLength']    = "-H 'Content-Length: 0'";  
      $this->Headers['compressed']       = "--compressed";              
           
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
        
      // INSTA-CLIENT.make_curl_chaining_str()
      $variables = "{\"id\":\"$insta_id\",\"first\":$N";
      if ($cursor != NULL && $cursor != "NULL") {
        $variables .= ",\"after\":\"$cursor\"";
      }
      $variables .= "}";*/
      
      $tag = ($this->ProfileType->getEnumValue() == EnumEntity::HASHTAG) ? "tag_name" : "id";
      $str_cur = ($cursor != null) ? sprintf(",\"after\":\"%s\"", $cursor) : "";
      $this->MediaStr = sprintf("{\"%s\":\"%s\",\"first\":\"%s\"%s}", $tag, $id, $first, $str_cur); 
      //echo "<br>".$this->MediaStr;
      
    }
    
    public function setChallengeData (){
      
    }
    
    public function make_curl_str(Proxy $proxy, CookiesRequest $cookies = NULL) {
      $profile = $this->ProfileType->getEnumValue();
      $action = $this->ActionType->getEnumValue();
      
      switch ($profile + $action){
        case EnumEntity::CLIENT + EnumAction::CMD_FOLLOW:
          //
        break;

        case EnumEntity::CLIENT + EnumAction::CMD_UNFOLLOW:
          //
        break;
        
        case EnumEntity::CLIENT + EnumAction::CMD_LIKE:
          //
        break;
            
        case EnumEntity::GEO + EnumAction::GET_USER_INFO_POST:
        case EnumEntity::HASHTAG + EnumAction::GET_USER_INFO_POST:
          //
        break;
        
        case EnumEntity::GEO + EnumAction::GET_POST:
        case EnumEntity::PERSON + EnumAction::GET_POST:
        case EnumEntity::HASHTAG + EnumAction::GET_POST:
          $str_cur = $this->get_post($proxy, $cookies);
        break;           
      
        default:
          throw new InstaCurlActionException("La accion solicitada: ($this->ActionType) no es aplicable a: ($this->ProfileType)");
      }
      
      return $str_cur;
    }

    public function make_curl_obj(Proxy $proxy, CookiesRequest $cookies = NULL) {
      $profile = $this->ProfileType->getEnumValue();
      $action = $this->ActionType->getEnumValue();
      
      switch ($profile + $action){      
        case EnumEntity::CLIENT + EnumAction::CMD_CHECKPOINT:
          //
        break;
      
        case EnumEntity::CLIENT + EnumAction::GET_CHALLENGE_CODE:
          //
        break;
      
        case EnumEntity::GEO + EnumAction::GET_PROFILE_INFO:
        case EnumEntity::PERSON + EnumAction::GET_PROFILE_INFO:
        case EnumEntity::HASHTAG + EnumAction::GET_PROFILE_INFO:
          //
        break;
      
        default:
          throw new InstaCurlActionException("La accion solicitada: ($this->ActionType) no es aplicable a: ($this->ProfileType)");
      }
    }
    
    private function get_post (Proxy $proxy, CookiesRequest $cookies){
      // Paso 0. compruebo validez del MediaStr
      if ($this->MediaStr == null)
        throw new InstaCurlMediaException("No se han establecido los parametros <id, cursor, first> del media-cUrl");

      // Paso 1. configuracion inicial de la curl
      $curl_str = sprintf("curl %s '%s?query_hash=%s&variables=%s'", $proxy->ToString(), 
        $this->InstaURL['Graphql'], $this->ProfileType->getHashQuery(), urlencode($this->MediaStr));
      
      // Paso 2. agregando la cookies a la curl
      $curl_str = sprintf("%s -H 'Cookie: mid=%s; sessionid=%s; s_network=; ig_pr=1; ig_vw=1855; csrftoken=%s; ds_user_id=%s'", 
        $curl_str, $cookies->mid, $cookies->sessionid, $cookies->csrftoken, $cookies->ds_user_id);
      $curl_str = sprintf("%s -H 'X-CSRFToken: %s'", $curl_str, $cookies->csrftoken);
      
      // Paso 3. agregando el resto de los headers
      $curl_str = sprintf("%s %s %s %s %s %s %s %s %s %s %s", $curl_str, 
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
      
      return $curl_str;
      
      //- EJEMPLO PARA PASO 2-----
      /*$variables = urlencode($variables);
      $graphquery_url = InstaURLs::GraphqlQuery;
      $url = "$graphquery_url?query_hash=$query&variables=$variables";
      $proxy_str = $proxy->ToString();
      $curl_str = "curl $proxy_str '$url' ";*/
            
      /*if ($cookies !== NULL) {
        if ($cookies->mid == NULL || $cookies->csrftoken == NULL || $cookies->sessionid == NULL ||
          $cookies->ds_user_id == NULL)
          return NULL;
        $curl_str .= "-H 'Cookie: mid=$cookies->mid; sessionid=$cookies->sessionid; s_network=; ig_pr=1; ig_vw=1855; csrftoken=$cookies->csrftoken; ds_user_id=$cookies->ds_user_id' ";
        $curl_str .= "-H 'X-CSRFToken: $cookies->csrftoken' ";
      }*/

      //- EJEMPLO PARA PASO 3-----
      //$cnf = new \follows\cls\system_config();
      /*$curl_str .= "-H 'Origin: https://www.instagram.com' ";
      $curl_str .= "-H 'Accept-Encoding: gzip, deflate' ";
      $curl_str .= "-H 'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4' ";
      //$curl_str .= "-H 'User-Agent: $cnf->CURL_USER_AGENT' ";
      $curl_str .= "-H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0' ";
      $curl_str .= "-H 'X-Requested-with: XMLHttpRequest' ";
      $curl_str .= "-H 'content-type: application/x-www-form-urlencoded' ";
      $curl_str .= "-H 'Accept: /*' ";
      $curl_str .= "-H 'Referer: https://www.instagram.com/' ";
      $curl_str .= "-H 'Authority: www.instagram.com' ";
      $curl_str .= "--compressed ";
      return $curl_str;*/
    }
    
    private function get_user_info_post () {
      
    }
    
    private function checkpoint () {
      // INSTACLIENT - make_checkpoint
      $csrftoken = $this->cookies->csrftoken;
      $mid = $this->cookies->mid;
      $url = $this->InstaURL['Base'] . "/" . $this->cookies->checkpoint_url;
      $ch = curl_init($this->InstaURL['Base']);
      
      $postinfo = "security_code=$code";
      
      $headers = array();
      $headers[] = $this->Headers['Origin'];
      $headers[] = $this->Headers['UserAgent'];
      $headers[] = $this->Headers['AcceptAll'];
      $headers[] = $this->Headers['AcceptLanguage'];
      $headers[] = $this->Headers['AcceptEncoding'];
      $headers[] = "Referer: $url";
      $headers[] = "X-CSRFToken: $csrftoken";
      $headers[] = "X-Instagram-AJAX: 1";
      $headers[] = "Content-Type: application/x-www-form-urlencoded";
//            $headers[] = "Content-Type: application/json";
      $headers[] = "X-Requested-With: XMLHttpRequest";
      $headers[] = "Cookie: mid=$mid; csrftoken=$csrftoken";

      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      //curl_setopt($ch, CURLOPT_POST, true);
      //            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
      //            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
      curl_setopt($ch, CURLOPT_HEADER, 1);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_HEADERFUNCTION, array($this, "curlResponseHeaderCallback"));
      
      return $ch;
    }
    
    private function challenge ()
    {
      $ch = curl_init(InstaURLs::Instagram);
      $csrftoken = $this->get_insta_csrftoken($ch);
      $urlgen = $this->get_cookies_value('urlgen');
      $mid = $this->get_cookies_value('mid');
      $rur = $this->get_cookies_value('rur');
      $ig_vw = $this->get_cookies_value('ig_vw');
      $ig_pr = $this->get_cookies_value('ig_pr');
      $ig_vh = $this->get_cookies_value('ig_vh');
      $ig_or = $this->get_cookies_value('ig_or');

      $url = InstaURLs::Instagram;
      $url .= "/" . $challenge;

      $cookies = new \stdClass();
      $cookies->csrftoken = $csrftoken;
      $cookies->mid = $mid;
      $cookies->checkpoint_url = $challenge;

      $headers = array();
      $headers[] = "Origin: https://www.instagram.com";
      $headers[] = "User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:50.0) Gecko/20100101 Firefox/50.0' -H 'Accept: */*";
      $headers[] = "Accept-Language: en-US,en;q=0.5";
      $headers[] = "Referer: $url";
      $headers[] = "X-CSRFToken: $csrftoken";
      $headers[] = "X-Instagram-AJAX: 1";
      $headers[] = "Content-Type: application/x-www-form-urlencoded";
      $headers[] = "X-Requested-With: XMLHttpRequest";
      $headers[] = "Cookie: csrftoken=$csrftoken; mid=$mid; rur=$rur; ig_vw=$ig_vw; ig_pr=$ig_pr; ig_vh=$ig_vh; ig_or=$ig_or";
      $headers[] = "Connection: keep-alive";
      $postinfo = "choice=$choice";

      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      //curl_setopt($ch, CURLOPT_POST, true);
      //            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
      //            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
      curl_setopt($ch, CURLOPT_HEADER, 1);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      
      return $ch;
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
