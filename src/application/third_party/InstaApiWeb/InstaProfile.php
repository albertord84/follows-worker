<?php

namespace InstaApiWeb {
  //require_once \InstaApiWeb\Responses;

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

    public function __construct($response, $cookies = NULL) {
      $this->insta_id = $response->id;
      $this->insta_name = $response->username;
      $this->image_url = $response->profile_pic_url;
      $profile_data = $this->get_reference_user($login_data, $Profile->username);
      $this->instaProfileData = new \stdClass();
      if (isset($profile_data->uset->is_private)) {
        $this->instaProfileData->is_private = $profile_data->user->is_private;
      }
      if (isset($profile_data->user->media->count)) {
        $this->instaProfileData->posts_count = $profile_data->user->media->count;
      }
      if (isset($profile_data->user->follows_viewer)) {
        $this->instaProfileData->follows_viewer = $profile_data->user->follows_viewer;
      }
    }

    public function get_reference_user($cookies, $reference_user_name) {
      if ($cookies != NULL) {
        $csrftoken = isset($cookies->csrftoken) ? $cookies->csrftoken : 0;
        $ds_user_id = isset($cookies->ds_user_id) ? $cookies->ds_user_id : 0;
        $sessionid = isset($cookies->sessionid) ? $cookies->sessionid : 0;
        $mid = isset($cookies->mid) ? $cookies->mid : 0;
      }
      $url = InstaURLs::Instagram;
      $url .= "/$reference_user_name/?__a=1";
      $curl_str = "curl '$url' ";
      $curl_str .= "-H 'Accept-Encoding: gzip, deflate, br' ";
      $curl_str .= "-H 'X-Requested-With: XMLHttpRequest' ";
      $curl_str .= "-H 'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4' ";
      $curl_str .= "-H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0' ";
      $curl_str .= "-H 'Accept: */*' ";
      $curl_str .= "-H 'Referer: https://www.instagram.com/' ";
      $curl_str .= "-H 'Authority: www.instagram.com' ";
      if ($cookies != NULL) {
        $curl_str .= "-H 'Cookie: mid=$mid; sessionid=$sessionid; s_network=; ig_pr=1; ig_vw=1855; csrftoken=$csrftoken; ds_user_id=$ds_user_id' ";
      }
      $curl_str .= "--compressed ";
      $result = exec($curl_str, $output, $status);
      return json_decode($output[0]);
    }

  }

}