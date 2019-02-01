<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use business\Client;
use business\CookiesRequest;

class Library_test extends CI_Controller {

  public function __construct() {
    parent::__construct();

    require_once config_item('db-exception-class');
    require_once config_item('business-client-class');
    require_once config_item('business-cookies_request-class');
  }

  public function index() {
    echo "Controller: <b>" . __CLASS__ . "</b> cargado.";
  }

  public function client() {
    echo "Dentro de client controller";

    $obj = new Client();

    echo "<br><br>Ok";
  }

  public function load() {
    //$params = array('username'  => 'isela');

    $count = 0;
    echo "<h3><u>Test de carga de librerias</u></h3>";

    // -OK-
    echo "<pre>";
    echo "[load] GeoProfile_lib ==> ";
    $this->load->library("InstaApiWeb/InstaGeoProfile_lib", null, 'GeoProfile_lib');
    echo "(<b>ok</b>)";
    $count++;

    // -OK-
    echo "<br>[load] HashProfile_lib ==> ";
    //$this->load->library("InstaApiWeb/InstaHashProfile_lib", null, 'HashProfile_lib');
    echo "(<b>ok</b>)";
    $count++;

    // -OK-
    echo "<br>[load] PersonProfile_lib ==> ";
    //$this->load->library("InstaApiWeb/InstaPersonProfile_lib", null, 'PersonProfile_lib');
    echo "(<b>ok</b>)";
    $count++;
    
    // -OK-
 /* echo "<br>[load] InstaApi_lib ==> ";
    $this->load->library("InstaApiWeb/InstaApi_lib", null, 'InstaApi_lib');
    //$this->load->library("InstaApiWeb/InstaApi_lib", $params, 'InstaApi_lib');
    echo "(<b>ok</b>)";
    $count++;
  */

    // -OK-
    echo "<br>[load] InstaClient_lib ==> ";
    $this->load->library("InstaApiWeb/InstaClient_lib", null, 'InstaClient_lib');
    echo "(<b>ok</b>)";
    $count++;

    // -OK-
    //echo "<br>[load] InstaProfileList_lib ==> ";
    //$this->load->library("InstaApiWeb/InstaProfileList", null, 'InstaProfileList_lib');
    //echo "(<b>ok</b>)";
    //$count++;

    // -OK-
    echo "<br>[load] InstaProfile_lib ==> ";
    $this->load->library("InstaApiWeb/InstaProfile_lib", null, 'InstaProfile_lib');
    echo "(<b>ok</b>)";
    $count++;

    // -OK-
    //echo "<br>[load] Proxy_lib ==> ";
    //$this->load->library("InstaApiWeb/Proxy_lib", null, 'Proxy_lib');
    //echo "(<b>ok</b>)";
    //$count++;

    // -OK-
    //echo "<br>[load] Media_lib ==> ";
    //$this->load->library("InstaApiWeb/Media_lib", null, 'Media_lib');
    //echo "(<b>ok</b>)";
    //$count++;

    echo "<br><br>total: " . $count . " libs";
    echo "</pre>";
  }

  public function run ()
  {
    //======= GEO_PROFILE-LIB =======//
    echo "<pre>";
    echo "<h2>Test GeoProfile Library</h2>";
    echo "[load] GeoProfile_lib ==> ";
    $this->load->library("InstaApiWeb/InstaGeoProfile_lib", null, 'GeoProfile_lib');
    echo "(<b>ok</b>)<br>";
    
    echo "[exec] process_insta_prof_data() ==> ";
    $this->GeoProfile_lib->process_insta_prof_data(new \stdClass());
    echo "(<b>ok</b>)<br>";
   
    echo "[exec] get_insta_followers() ==> ";
    //$this->GeoProfile_lib->get_insta_followers();
    //echo "(<b>ok</b>)<br>";
    
    echo "[exec] get_insta_media() ==> ";
    //$this->GeoProfile_lib->get_insta_media();
    //echo "(<b>ok</b>)<br>";
    
    echo "[exec] get_post_user_info() ==> ";
    //$this->GeoProfile_lib->get_post_user_info();
    //echo "(<b>ok</b>)<br>";
    
    //======= HASH_PROFILE-LIB =======//
    echo "<h2>Test HashProfile Library</h2>";
    echo "[load] HashProfile_lib ==> ";
    $this->load->library("InstaApiWeb/InstaHashProfile_lib", null, 'HashProfile_lib');
    echo "(<b>ok</b>)<br>";
    
    echo "[exec] process_insta_prof_data() ==> ";
    //$this->HashProfile_lib->process_insta_prof_data();
    //echo "(<b>ok</b>)<br>";
    
    echo "[exec] get_insta_followers() ==> ";
    //$this->HashProfile_lib->get_insta_followers();
    //echo "(<b>ok</b>)<br>";
    
    echo "[exec] get_insta_media() ==> ";
    //$this->HashProfile_lib->get_insta_media();
    //echo "(<b>ok</b>)<br>";
    
    echo "[exec] get_post_user_info() ==> ";
    //$this->HashProfile_lib->get_post_user_info();
    //echo "(<b>ok</b>)<br>";
    
    //======= PERSON_PROFILE-LIB =======//
    echo "<h2>Test PersonProfile Library</h2>";
    echo "[load] HashProfile_lib ==> ";
    $this->load->library("InstaApiWeb/InstaPersonProfile_lib", null, 'PersonProfile_lib');
    echo "(<b>ok</b>)<br>";
        
    echo "[exec] get_insta_followers() ==> ";
    //$this->PersonProfile_lib->get_insta_followers();
    //echo "(<b>ok</b>)<br>";
    
    echo "[exec] get_insta_followers_list() ==> ";
    //$this->PersonProfile_lib->get_insta_followers_list();
    //echo "(<b>ok</b>)<br>";
    
    echo "[exec] get_insta_media() ==> ";
    //$this->PersonProfile_lib->get_insta_media();
    //echo "(<b>ok</b>)<br>";
    
    echo "[exec] get_post_user_info() ==> ";
    //$this->PersonProfile_lib->get_post_user_info();
    //echo "(<b>ok</b>)<br>";
    
    echo "[exec] get_insta_following_count() ==> ";
    //$this->PersonProfile_lib->get_insta_following_count();
    //echo "(<b>ok</b>)<br>";
    
    echo "[exec] get_reference_data() ==> ";
    //$this->PersonProfile_lib->get_reference_data();
    //echo "(<b>ok</b>)<br>";
    
    echo "[exec] process_insta_prof_data() ==> ";
    //$this->PersonProfile_lib->process_insta_prof_data();
    //echo "(<b>ok</b>)<br>";
    
    echo "[exec] make_curl_following_str() ==> ";
    //$this->PersonProfile_lib->make_curl_following_str();
    //echo "(<b>ok</b>)<br>";
    
    echo "[exec] parse_follow_count() ==> ";
    //$this->PersonProfile_lib->parse_follow_count();
    //echo "(<b>ok</b>)<br>";
    
    echo "[exec] exists_profile() ==> ";
    //$this->PersonProfile_lib->exists_profile();
    //echo "(<b>ok</b>)<br>";
       
    //======= INSTA_API_CLIENT-LIB =======//
    echo "<h2>Test InstaApiClient Library</h2>";
    echo "[load] InstaClient_lib ==> ";
    $this->load->library("InstaApiWeb/InstaClient_lib", null, 'InstaClient_lib');
    echo "(<b>ok</b>)<br>";
     
    echo "[exec] make_login() ==> ";
    $client = new Client();
    //$client->load_from_db(30864);
    $result = $this->InstaClient_lib->make_login("alberto_test", "alberto2");
    echo "(<b>ok</b>)<br>"; var_dump($result);

    
    echo "[exec] make_insta_friendships_command() ==> ";
    //$this->InstaClient_lib->make_insta_friendships_command();
    //echo "(<b>ok</b>)<br>"; 
    
    echo "[exec] make_curl_friendships_command_str() ==> ";
    //$this->InstaClient_lib->make_curl_friendships_command_str();
    //echo "(<b>ok</b>)<br>"; 
    
    echo "[exec] make_curl_chaining_str() ==> ";
    //$this->InstaClient_lib->make_curl_chaining_str();
    //echo "(<b>ok</b>)<br>"; 
    
    echo "[exec] obtine_cookie_value() ==> ";
    //$this->InstaClient_lib->obtine_cookie_value();
    //echo "(<b>ok</b>)<br>"; 
    
    echo "[exec] get_cookies_value() ==> ";
    //$this->InstaClient_lib->get_cookies_value();
    //echo "(<b>ok</b>)<br>"; 
    
    echo "[exec] make_post() ==> ";
    //$this->InstaClient_lib->make_post();
    //echo "(<b>ok</b>)<br>"; 
    
    echo "[exec] get_insta_csrftoken() ==> ";
    //$this->InstaClient_lib->get_insta_csrftoken();
    //echo "(<b>ok</b>)<br>"; 
    
    echo "[exec] verify_cookies() ==> ";
    //$this->InstaClient_lib->verify_cookies();
    //echo "(<b>ok</b>)<br>"; 
    
    echo "[exec] like_first_post() ==> ";
    //$this->InstaClient_lib->like_first_post();
    //echo "(<b>ok</b>)<br>"; 
    
    echo "[exec] curlResponseHeaderCallback() ==> ";
    //$this->InstaClient_lib->curlResponseHeaderCallback();
    //echo "(<b>ok</b>)<br>"; 
    
    echo "[exec] checkpoint_requested() ==> ";
    //$this->InstaClient_lib->checkpoint_requested();
    //echo "(<b>ok</b>)<br>"; 
    
    echo "[exec] get_challenge_data() ==> ";
    //$this->InstaClient_lib->get_challenge_data();
    //echo "(<b>ok</b>)<br>"; 
    
    echo "[exec] make_checkpoint() ==> ";
    //$this->InstaClient_lib->make_checkpoint();
    //echo "(<b>ok</b>)<br>"; 
    
    echo "[exec] TurnOn_Logs() ==> ";
    //$this->InstaClient_lib->TurnOn_Logs();
    //echo "(<b>ok</b>)<br>"; 
    
    echo "[exec] TurnOff_Logs() ==> ";
    //$this->InstaClient_lib->TurnOff_Logs();
    //echo "(<b>ok</b>)<br>"; 
    
    //$result = new InstaApiWeb\Responses\LoginResponse();
    //
    //var_dump($result);
    //echo "(<b>ok</b>)<br>";
    
    
    //---------------------------------DEPRECATED--------------------------------------//
    
    
    //======= INSTA_API-LIB =======//
/*  echo "<h2>Test InstaApi Library</h2>";
    echo "[load] InstaApi_lib ==> ";
    $this->load->library("InstaApiWeb/InstaApi_lib", null, 'InstaApi_lib');
    echo "(<b>ok</b>)<br>";
    
    echo "[exec] login() ==> ";
    //$this->InstaApi_lib->login();
    //echo "(<b>ok</b>)<br>";
    
    echo "[exec] make_query() ==> ";
    //$this->InstaApi_lib->make_query();
    //echo "(<b>ok</b>)<br>";
    
    //======= INSTA_PROFILE_LIST-LIB =======//
    echo "<h2>Test InstaProfileList Library</h2>";
    echo "[load] InstaProfileList_lib ==> ";
    $this->load->library("InstaApiWeb/InstaProfileList_lib", null, 'InstaProfileList_lib');
    echo "(<b>ok</b>)<br>";
    
    echo "[exec] get_list_from_insta_follower_list() ==> ";
    //$this->InstaProfileList_lib->get_list_from_insta_follower_list();
    //echo "(<b>ok</b>)<br>";
    
    //======= MEDIA-LIB =======//
    echo "<h2>Test Media Library</h2>";
    echo "[load] Media_lib ==> ";
    $this->load->library("InstaApiWeb/Media_lib", null, 'Media_lib');
    echo "(<b>ok</b>)<br>";
     
    //echo "[exec] () ==> ";
    //$this->Media_lib->
    //echo "(<b>ok</b>)<br>";
   
    //======= PROXY-LIB =======//
    echo "<h2>Test Proxy Library</h2>";
    echo "[load] Proxy_lib ==> ";
    $this->load->library("InstaApiWeb/Proxy_lib", null, 'Proxy_lib');
    echo "(<b>ok</b>)<br>";
    
    //echo "[exec] () ==> ";
    //$this->Proxy_lib->
    //echo "(<b>ok</b>)<br>";
*/    
    
       
    echo "</pre>";
  }
  
  public function curl()
  {
    echo "<pre>";
    echo "<h2>Test GeoProfile Library</h2>";
    echo "[load] GeoProfile_lib ==> ";
    $this->load->library("InstaApiWeb/GeoProfile_lib", null, 'GeoProfile_lib');
    $cookies = json_decode('{"json_response":{"authenticated":true,"user":true,"status":"ok"},"csrftoken":"kToHKxaPB4iPuVY7t2XzQdi3GeyxrI7D","sessionid":"5453435354%3AVg6DjXraZlISez%3A15","ds_user_id":"5453435354","mid":"W-SbgAAEAAGuwWxQcdNcdZ0xa8Mi"}');
    $this->GeoProfile_lib->get_insta_media(15,NULL,$cookies);
    echo "(<b>ok</b>)<br>";
    
    echo "<pre>";
    echo "<h2>Test HashProfile Library</h2>";
    echo "[load] HashProfile_lib ==> ";
    $this->load->library("InstaApiWeb/HashProfile_lib", null, 'HashProfile_lib');
    $this->HashProfile_lib->get_insta_media(15,NULL,$cookies);
    echo "(<b>ok</b>)<br>";
    
  }
}
