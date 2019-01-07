<?php

namespace ApiInstaWeb {

  /**
   * Description of InstaApi
   *
   * @author dumbu
   */
  class InstaApi {

    //put your code here
    /*
     *  : stdClass{cookies}
      +make_insta_friendships_command($login_data, $resource_id, $command = 'follow', $objetive_url = 'web/friendships', $Client = NULL, &$curl_str = "") : InstaResponse
      +make_insta_friendships_command_client($Client, $resource_id, $command = 'follow', $objetive_url = 'web/friendships') : InstaResponse
      +make_curl_friendships_command_str($url, $login_data, $proxy = NULL, $Client = NULL) : STRING
      +get_insta_chaining($login_data, $user, $N = 1, $cursor = NULL, $proxy = ""):  InstaResponse
     */

    protected $has_logs = TRUE;

    public function __construct() {
      require_once config_item('composer_autoload');
      require_once config_item('cookies_wrong_syntax-exception-class');
      require_once config_item('insta_checkpoint_required-exception-class');
      require_once config_item('incorrect_password-exception-class');
      require_once config_item('curl_nertwork-exception-class');
    }

    public function login(string $username, string $password, Proxy $proxy = null) {
      $debug = false;
      $truncatedDebug = true;
      //////////////////////

      \InstagramAPI\Instagram::$allowDangerousWebUsageAtMyOwnRisk = true;

      try {
        $ig = new \InstagramAPI\Instagram($debug, $truncatedDebug);


        //$ig->setOutputInterface("191.252.110.140");
        //$ig->setProxy(['proxy'=>'tcp://70.39.250.32:23128']);
        if ($proxy)
          $ig->setProxy("http://" . $proxy->ToString());
        //$ig->setProxy("http://albertreye9917:3r4rcz0b1v@207.188.155.18:21316");

        $loginIGResponse = $ig->login($username, $password);

        $ig->client->loadCookieJar();

        if ($loginIGResponse !== null && $loginIGResponse->isTwoFactorRequired()) {
          $twoFactorIdentifier = $loginIGResponse->getTwoFactorInfo()->getTwoFactorIdentifier();
          $verificationCode = trim(fgets(STDIN));
          $ig->finishTwoFactorLogin($verificationCode, $twoFactorIdentifier);
        }

        $Cookies = array();
        $Cookies['sessionid'] = $ig->client->getCookie('sessionid')->getValue();
        $Cookies['csrftoken'] = $ig->client->getCookie('csrftoken')->getValue();
        $Cookies['ds_user_id'] = $ig->client->getCookie('ds_user_id')->getValue();
        $Cookies['mid'] = $ig->client->getCookie('mid')->getValue();
        $loginResponse = new ApiInstaWeb\Responses\LoginResponse(
                'ok', true, "", (object) $Cookies
        );
        return $loginResponse;
      } catch (\Exception $e) {
        //echo '<br>Something went wrong: ' . $e->getMessage() . "\n</br>";
        //echo $e->getTraceAsString();                
        $source = 0;
        if (isset($id) && $id !== NULL && $id !== 0)
          $source = 1;

        if ((strpos($e->getMessage(), 'Challenge required') !== FALSE) || (strpos($e->getMessage(), 'Checkpoint required') !== FALSE) || (strpos($e->getMessage(), 'challenge_required') !== FALSE)) {
          $res = $e->getResponse()->getChallenge()->getApiPath();
          throw new Exceptions\InstaCheckpointRequiredException($e->getMessage(), $e->getPrevious(), $res);
        } else if (strpos($e->getMessage(), 'Network: CURL error 28') !== FALSE) { // Time out by bad proxy
          throw new Exceptions\CurlNertworkException($e->getMessage(), $e);
        } else if (strpos($e->getMessage(), 'password you entered is incorrect') !== FALSE)
          throw new Exceptions\IncorrectPasswordException($e->getMessage(), $e);
        else if (strpos($e->getMessage(), 'there was a problem with your request') !== FALSE)
          throw new \InstaException('problem_with_your_request', $e);
        else
          throw new \InstaException($e->getMessage(), $e);
      }
    }

    public static function make_query(string $query, string $variables, \stdClass $cookies, Proxy $proxy = NULL) {
      $variables = urlencode($variables);
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
      $curl_str .= "-H 'content-type: application/x-www-form-urlencoded' ";
      $curl_str .= "-H 'Accept: */*' ";
      $curl_str .= "-H 'Referer: https://www.instagram.com/' ";
      $curl_str .= "-H 'Authority: www.instagram.com' ";
      $curl_str .= "--compressed ";
      return $curl_str;
    }

  }

}
