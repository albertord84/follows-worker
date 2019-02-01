<?php

namespace InstaApiWeb {

use stdClass;
//use InstaApiWeb\InstaApi;
use InstaApiWeb\InstaCurlMgr;
use InstaApiWeb\ReferenceProfile;
use InstaApiWeb\CookiesRequest;

require_once 'InstaReferenceProfile.php';

  /**
   * Description of GeoProfile
   *
   * @author dumbu
   */
  class GeoProfile extends ReferenceProfile {

    //begin ReferenceProfile
    /*  protected function make_curl_str(\stdClass $cookies, int $N, string $cursor = NULL, Proxy $proxy = NULL) {

      } */

    public function __construct() {
      parent::__construct();
      $this->insta_id = 235572176;
    
      
      // Deprecated!!!
      //$this->tag_query = "ac38b90f0f3981c42092016a37c59bf7";
    }

    public function process_insta_prof_data(stdClass $content) {
      $Profile = NULL;
      if (is_object($content) && $content->status === 'ok') {
        $places = $content->places;
        // Get user with $ref_prof name over all matchs 
        if (is_array($places)) {
          for ($i = 0; $i < count($places); $i++) {
            if ($places[$i]->place->slug === $ref_prof) {
              $Profile = $places[$i]->place;
              // $Profile->follows = $this->get_insta_ref_prof_follows($ref_prof_id);
              break;
            }
          }
        }
      } else {
        //var_dump($content);
        //var_dump("null reference profile!!!");
      }
      return $Profile;
    }

    public function get_insta_followers(stdClass $cookies = NULL, int $N = 15, string& $cursor = NULL, Proxy $proxy = NULL) {

      $json_response = $this->get_insta_media($cookies, $N, $cursor, $proxy);
      $profiles = new InstaProfileList();
      if (is_object($json_response) && $json_response->status == 'ok') {
        if (isset($json_response->data->location->edge_location_to_media)) { // if response is ok
          if ($this->has_logs) {
            echo "Nodes: " . count($json_response->data->location->edge_location_to_media->edges) . " <br>\n";
          }
          $page_info = $json_response->data->location->edge_location_to_media->page_info;
          foreach ($json_response->data->location->edge_location_to_media->edges as $Edge) {
            $profile = new \stdClass();
            $profile->node = $this->get_post_user_info($Edge->node->shortcode, $cookies, $proxy);
            array_push($profiles->profile_list, $profile);
          }
        } else {
          throw new Exceptions\EndCursorException("The cursor has ended");
        }
        return $profiles;
      }
      return new \InstaException("unknown exception");
    }

    /**
     * 
     * @param int $N
     * @param string $cursor
     * @param \stdClass $cookies
     * @param \InstaApiWeb\Proxy $proxy
     */
    public function get_insta_media(int $N, string $cursor = NULL, CookiesRequest $cookies = NULL, Proxy $proxy = NULL) {
      try {
        $mngr = new InstaCurlMgr(new EnumEntity(EnumEntity::GEO), new EnumAction(EnumAction::GET_POST));
        $mngr->setMediaData($this->insta_id, $N, $cursor);
        $curl_str = $mngr->make_curl_str($proxy, $cookies);
        var_dump($curl_str);
        exec($curl_str, $output, $status);
        var_dump($output);
      } catch (Exception $e) {
        var_dump($e);
      }
      
      /*
        $variables = "{\"id\":\"$this->insta_id\",\"first\":$N";
        if ($cursor != NULL && $cursor != "NULL") {
        $variables .= ",\"after\":\"$cursor\"";
        }
        $variables .= "}";

        $api = new InstaApi();
        $curl_str = $api->make_query($this->tag_query, $variables, $cookies, $proxy);
        if ($curl_str === NULL)
        return NULL;
        exec($curl_str, $output, $status);
        if (count($output) > 0 && isset($output[0])) {
        $json = json_decode($output[0]);
        if (isset($json->data->location->edge_location_to_media) && isset($json->data->location->edge_location_to_media->page_info)) {
        $cursor = $json->data->location->edge_location_to_media->page_info->end_cursor;
        if (count($json->data->location->edge_location_to_media->edges) == 0) {
        if ($this->has_logs) {
        echo ("<br>\n No nodes!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!");
        }
        $cursor = NULL;
        }
        } else if (isset($json->data) && $json->data->location == NULL) {
        //var_dump($output);
        if ($this->has_logs) {
        print_r($curl_str);
        }
        $cursor = NULL;
        } else if ($this->has_logs) {
        var_dump($output);
        print_r($curl_str);
        echo ("<br>\n Untrated error!!!");
        }
        return $json;
        } else if ($this->has_logs) {
        var_dump($output);
        print_r($curl_str);
        }
        return NULL;
        } catch (\Exception $exc) {
        if ($this->has_logs)
        echo $exc->getTraceAsString();
        } */
    }

    public function get_post_user_info($post_reference, \stdClass $cookies = NULL, Proxy $proxy = NULL) {
      //echo " -------Obtindo dados de perfil que postou na geolocalizacao------------<br>\n<br>\n";
      if ($cookies != NULL) {
        $csrftoken = isset($cookies->csrftoken) ? $cookies->csrftoken : 0;
        $ds_user_id = isset($cookies->ds_user_id) ? $cookies->ds_user_id : 0;
        $sessionid = isset($cookies->sessionid) ? $cookies->sessionid : 0;
        $mid = isset($cookies->mid) ? $cookies->mid : 0;
      }
      $url = "https://www.instagram.com/p/$post_reference/?taken-at=$this->insta_id&__a=1";
      $curl_str = "curl $proxy '$url' ";
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
      $object = json_decode($output[0]);
      if (is_object($object) && isset($object->graphql->shortcode_media->owner)) {
        return $object->graphql->shortcode_media->owner;
      }
      return NULL;
    }

    //end ReferenceProfile
  }

}