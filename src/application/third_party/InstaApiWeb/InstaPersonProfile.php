<?php

namespace InstaApiWeb {
  
  require_once 'InstaReferenceProfile.php';

  /**
   * Description of PersonProfile
   *
   * @author dumbu
   */
  class PersonProfile extends ReferenceProfile {

    //put your code here
    //begin ReferenceProfile
    /* protected function make_curl_str(\stdClass $cookies, int $N, string $cursor = NULL, Proxy $proxy = NULL) {

      } */
    public function __construct()  {
      parent::__construct();
      $this->tag_query = "37479f2b8209594dde7facb0d904896a";
      
    }

    public function process_insta_prof_data(\stdClass $content) {
      $Profile = NULL;
      if (is_object($content) && $content->status === 'ok') {
        $users = $content->users;
        // Get user with $ref_prof name over all matchs 
        if (is_array($users)) {
          for ($i = 0; $i < count($users); $i++) {
            if ($users[$i]->user->username === $ref_prof) {
              $Profile = $users[$i]->user;
              //var_dump($Profile);
              //  $Profile->follows = $this->get_insta_ref_prof_follows($ref_prof_id);
              $Profile->following = $this->get_insta_following_count($ref_prof);
              if (!isset($Profile->follower_count)) {
                $Profile->follower_count = isset($Profile->byline) ? $this->parse_follow_count($Profile->byline) : 0;
              }
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

    public function get_insta_followers(\stdClass $cookies = NULL, int $N = 15, string& $cursor = NULL, Proxy $proxy = NULL) {

      $follower_list = $this->get_insta_followers_list($cookies, $N, $cursor, $proxy);
      if ($follower_list != NULL) {
        if (is_object($json_response) && $json_response->status == 'ok') {
          if (isset($json_response->data->user->edge_followed_by)) { // if response is ok
            echo "Nodes: " . count($json_response->data->user->edge_followed_by->edges) . " <br>\n";
            $page_info = $json_response->data->user->edge_followed_by->page_info;
            $profiles = new InstaProfileList($json_response->data->user->edge_followed_by->edges);
            if ($page_info->has_next_page === FALSE && $page_info->end_cursor != NULL) { // Solo qdo es <> de null es que llego al final
              throw new Exceptions\WrongEndCursorException("It not has more pafes but end cursor it is diferent of NULL");
            } else if ($page_info->has_next_page === FALSE && $page_info->end_cursor === NULL) {
              throw new Exceptions\EndCursorException("The cursor has ended");
            }
          } else {
            throw new Exceptions\EndCursorException("The cursor has ended");
          }
          return $profiles;
        }
      }
      return $follower_list;
    }

    public function get_insta_followers_list(\stdClass $cookies = NULL, int $N = 15, string& $cursor = NULL, Proxy $proxy = NULL) {

      try {
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

          if (isset($json->data->user->edge_followed_by) && isset($json->data->user->edge_followed_by->page_info)) {
            if ($json->data->user->edge_followed_by->page_info->has_next_page === false) {
              if ($this->has_logs) {
                echo ("<br>\n END Cursor empty!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br>\n ");
                var_dump(json_encode($json));
                echo ("<br>\n Updated Reference Cursor to NULL!!<br>\n ");
              }
              $cursor = NULL;
            } else {
              $cursor = $json->data->user->edge_followed_by->page_info->end_cursor;
            }
          } else if ($this->has_logs) {
            var_dump($output);
            print_r($curl_str);
          }

          return $json;
        } else if ($this->has_logs) {
          var_dump($output);
          print_r($curl_str);
          return NULL;
        }
      } catch (\Exception $exc) {
        echo $exc->getTraceAsString();
      }
    }

    public function get_insta_media(int $N, string $cursor = NULL, CookiesRequest $cookies = NULL, Proxy $proxy = NULL) {
      
    }

    public function get_post_user_info($post_reference, \stdClass $cookies = NULL, Proxy $proxy = NULL) {
      
    }

    public function make_curl_following_str(\stdClass $cookies, int $N, string $cursor = NULL, Proxy $proxy = NULL) {
      
    }

    public function parse_follow_count($follow_count_str) {
      
    }

    public function get_insta_following_count() {
      
    }

    public function get_reference_data(\stdClass $cookies, string $referense_name) {
      
    }

    public function exists_profile(string $profile_name, ProfileType $type, string $insta_id = NULL, \stdClass $cookies = NULL, Proxy $proxy = NULL) {
      
    }

  }

}
