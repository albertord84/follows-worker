<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

use business\UserRole;
use business\UserStatus;

class Db_model extends CI_Model {

  function __construct() {
    parent::__construct();

    require_once config_item('db-exception-class');
    require_once config_item('business-user_role-class');
    require_once config_item('business-user_status-class');
  }
  
  //======================>GET<=======================//

  //FUNC 0 OK
  public function get_clients_by_status($user_status, $offset = 0, $rows = 50) {
    try {
      $sql = sprintf("SELECT * FROM users
                      INNER JOIN clients ON clients.user_id = users.id 
                      INNER JOIN plane ON plane.id = clients.plane_id 
                      WHERE users.status_id = '%d'
                      LIMIT %d, %d", $user_status, $offset, $rows);
      $query = $this->db->query($sql);

      return $query->result();
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 1 OK
  public function get_clients_data() {
    try {
      $CLIENT = UserRole::CLIENT;
      $ACTIVE = UserStatus::ACTIVE;
      $PENDING = UserStatus::PENDING;
      $VERIFY_ACCOUNT = UserStatus::VERIFY_ACCOUNT;
      $BLOCKED_BY_INSTA = UserStatus::BLOCKED_BY_INSTA;
      $BLOCKED_BY_TIME = UserStatus::BLOCKED_BY_TIME;
      $BEGINNER = UserStatus::BEGINNER;
      //$UNFOLLOW = UserStatus::UNFOLLOW;
      $sql = ""
            . "SELECT * FROM users "
            . "     INNER JOIN clients ON clients.user_id = users.id "
            . "     INNER JOIN plane ON plane.id = clients.plane_id "
            . "WHERE users.role_id = $CLIENT "
            . "     AND (clients.unfollow_total IS NULL OR clients.unfollow_total <> 1) "
            . "     AND ("
            . "          users.status_id = $ACTIVE OR "
            . "          users.status_id = $PENDING OR "
            . "          users.status_id = $VERIFY_ACCOUNT OR "
            . "          users.status_id = $BLOCKED_BY_INSTA OR "
            . "          users.status_id = $BLOCKED_BY_TIME"
            . "      ) "
            . "ORDER BY users.id; ";

      $query = $this->db->query($sql);
      return $query->result();
      
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 2 OK
  public function get_client_data($client_id) {
    try {
      $sql = ""
              . "SELECT * FROM users "
              . "     INNER JOIN clients ON clients.user_id = users.id "
              . "     INNER JOIN plane ON plane.id = clients.plane_id "
              . "WHERE users.id = $client_id; ";
      $query = $this->db->query($sql);

      return $query->row();
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 3 OK
  public function get_reference_profiles_data($client_id) {
    try {

      $sql = ""
              . "SELECT * FROM reference_profile "
              . "WHERE client_id = " . $client_id . ";";
//            . "  AND (reference_profile.deleted <> TRUE)"               
//            . "  (reference_profile.client_id = $client_id) AND "

      $query = $this->db->query($sql);
      return $query->result();
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 4 OK
  public function get_biginner_data($offset = 0, $rows = 50) {
    try {

      $BEGINNER = UserStatus::BEGINNER;
      $CLIENT = UserRole::CLIENT;
      $sql = ""
              . "SELECT * FROM users "
              . "     INNER JOIN clients ON clients.user_id = users.id "
              . "     INNER JOIN plane ON plane.id = clients.plane_id "
              . "WHERE users.role_id = $CLIENT "
              . "     AND (clients.unfollow_total IS NULL OR clients.unfollow_total <> 1) "
              . "     AND  users.status_id = $BEGINNER "
              . "ORDER BY users.id "
              . "LIMIT $offset, $rows; ";

      $this->db->limit($offset, $rows);
      $query = $this->db->query($sql);
      //  var_dump($query);
      return $query->result();
    } catch (Error $e) {
      var_dump($e);
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 5 OK
  public function get_clients_data_for_report() {
    try {

      $CLIENT = UserRole::CLIENT;
      $DELETED = UserStatus::DELETED;
      $BEGINNER = UserStatus::BEGINNER;
      $DONT_DISTURB = UserStatus::DONT_DISTURB;

      //$UNFOLLOW = UserStatus::UNFOLLOW;
      $sql = ""
              . "SELECT users.id, users.login FROM users "
              . "     INNER JOIN clients ON clients.user_id = users.id "
              . "     INNER JOIN plane ON plane.id = clients.plane_id "
              . "WHERE users.role_id = $CLIENT "
              . "     AND (users.status_id NOT IN ($DELETED, $BEGINNER, $DONT_DISTURB)) "
              . "ORDER BY users.id; ";

      $query = $this->db->query($sql);

      return $query->result();
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 6 OK
  public function get_unfollow_clients_data() {
    try {

      $CLIENT = UserRole::CLIENT;
      $ACTIVE = UserStatus::ACTIVE;
      $PENDING = UserStatus::PENDING;
      $UNFOLLOWS = UserStatus::UNFOLLOW;
      //$UNFOLLOW = UserStatus::UNFOLLOW;
      $sql = ""
              . "SELECT * FROM users "
              . "     INNER JOIN clients ON clients.user_id = users.id "
              . "     INNER JOIN plane ON plane.id = clients.plane_id "
              . "WHERE users.role_id = $CLIENT "
              . "     AND clients.unfollow_total = 1 "
              . "     AND (users.status_id = $ACTIVE OR "
              . "          users.status_id = $PENDING OR "
              . "          users.status_id = $UNFOLLOWS"
              . "          );";

      $query = $this->db->query($sql);

      return $query->result();
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 7 OK
  public function get_gateway_plane_id($dumbu_plane_id) {
    try {

      $query = $this->db->query(""
              . "SELECT gateway_plane_id FROM plane "
              . "WHERE plane.id = $dumbu_plane_id; "
      );

      return $query->row();

      //$object = $result ? $result->result_object() : NULL;
      //return isset($object->gateway_plane_id) ? $object->gateway_plane_id : 0;
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 8 OK
  public function get_client_payment_data($client_id) {
    try {
      
      $sql = "SELECT * FROM users "
            . "     INNER JOIN clients ON clients.user_id = users.id "
            . "     INNER JOIN client_payment ON client_payment.dumbu_client_id = clients.user_id "
            . "     INNER JOIN plane ON plane.id = client_payment.dumbu_plane_id "
            . "WHERE users.id = $client_id; ";
      
      $query = $this->db->query($sql);
      return $query->row();
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 9 OK
  public function get_client_login_data($client_id) {
    try {

      $result = $this->db->query(""
              . "SELECT id, login, pass, insta_id FROM users "
              . "     INNER JOIN clients ON clients.user_id = users.id "
              . "WHERE users.id = $client_id; ");
      
      return $result->row();
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 10 OK
  public function get_client_data_bylogin($login) {
    try {

      $sql = ""
              . "SELECT * FROM clients "
              . "     INNER JOIN users ON clients.user_id = users.id "
              . "WHERE users.login LIKE '$login' "
              . "ORDER BY user_id DESC "
              . "LIMIT 1; ";
      $result = $this->db->query($sql);
      return $result->row();
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 11 OK
  public function get_client_proxy($client_id) {
    try {

      $sql = ""
              . "SELECT * FROM clients "
              . "     INNER JOIN Proxy ON clients.proxy = Proxy.idProxy "
              . "WHERE user_id LIKE '$client_id';";
      $result = $this->db->query($sql);
      return $result->row();
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }
  
  //FUNC 12 OK
  public function get_client_instaid_data($client_id) {
    try {

      $result = $this->db->query(""
              . "SELECT insta_id FROM clients "
              . "WHERE user_id = $client_id; "
      );
      return $result->row();
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }
  
  //FUNC 13 OK
  public function get_client_id_from_reference_profile_id($ref_prof_id) {
    try {

    /*  $query = "SELECT client_id FROM dumbudb.reference_profile WHERE  id =" . $ref_prof_id . ";";
      $result = $this->db->query($query);
      $data = $result->result_object();
      if (isset($data->client_id))
        return $data->client_id;
      else
        return 0;*/
      
      $sql = "SELECT client_id FROM dumbudb.reference_profile WHERE  id =" . $ref_prof_id . ";";
      $query = $this->db->query($sql);
      return $query->row();
      
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 14 OK
  public function get_reference_profiles_follows($ref_prof_id) {
    try {

      //$client_id = $_SESSION['id'];
      $client_id = $this->get_client_id_from_reference_profile_id($ref_prof_id);
      $id = intval($client_id);
      //echo '<br>---->>>Perfil id = '.$ref_prof_id.' Client id = '.$client_id.'<br>';

      if ($id != '0' && $id != 0 && $ref_prof_id) {
        /*$result = mysqli_query($this->fConnection, ""
                . "SELECT COUNT(*) FROM `dumbudb.followed`.`$id` "
                . "WHERE  reference_id = $ref_prof_id; "
        );
        $data = $result->fetch_row();
        return $data[0];*/
        
      $sql = "SELECT COUNT(*) FROM `dumbudb.followed`.`$id` WHERE  reference_id =" . $ref_prof_id. ";";
      $query = $this->db->query($sql);
      return $query->row(); 
      
      } else
        return 0;
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 15 OK
  public function get_follow_work() {
    //$Elapsed_time_limit = $GLOBALS['sistem_config']->MIN_NEXT_ATTEND_TIME;
    try {
      // Get daily work
      $sql = ""
              . "SELECT *, "
              . "   daily_work.cookies as cookies, "
              . "   users.id as users_id, "
              . "   clients.cookies as client_cookies, "
              . "   reference_profile.insta_id as rp_insta_id, "
              . "   reference_profile.type as rp_type, "
              . "   reference_profile.id as rp_id "
              . "FROM daily_work "
              . "INNER JOIN reference_profile ON reference_profile.id = daily_work.reference_id "
              . "INNER JOIN clients ON clients.user_id = reference_profile.client_id "
              . "INNER JOIN users ON users.id = clients.user_id "
              . "WHERE ((daily_work.to_follow  > 0) "
              . "   OR  (daily_work.to_unfollow  > 0)) "
              . "   AND (reference_profile.deleted <> TRUE || daily_work.to_unfollow  > 0) "
              //. "WHERE (now - daily_work.last_access) >= $Elapsed_time_limit "
              . "ORDER BY clients.last_access ASC, reference_profile.last_access ASC "
              . "LIMIT 1;";

          $query = $this->db->query($sql);
          $object = $query->row();

      // Update daily work time
      if ($object && (!isset($object->last_access) || intval($object->last_access) < time())) {
        //$ref_prof_id = $object->rp_insta_id;
        $time = time();
        $sql2 = ""
                . "UPDATE clients "
                . "SET clients.last_access = '$time' "
                . "WHERE clients.user_id = $object->users_id; ";
        $result2 = $this->db->query($sql2);

        if (!$result2) {
          //var_dump($sql2);
        }

        $sql2 = ""
                . "UPDATE reference_profile "
                . "SET reference_profile.last_access = '$time' "
                . "WHERE reference_profile.id = $object->rp_id; ";
        $result2 = $this->db->query($sql2);
      }
      return $object;
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 16 OK
  public function get_follow_work_by_id($reference_id) {
    //$Elapsed_time_limit = $GLOBALS['sistem_config']->MIN_NEXT_ATTEND_TIME;
    try {
      // Get daily work
      $sql = ""
              . "SELECT *, "
              . "   daily_work.cookies as cookies, "
              . "   users.id as users_id, "
              . "   clients.cookies as client_cookies, "
              . "   reference_profile.insta_id as rp_insta_id, "
              . "   reference_profile.type as rp_type, "
              . "   reference_profile.id as rp_id "
              . "FROM daily_work "
              . "INNER JOIN reference_profile ON reference_profile.id = daily_work.reference_id "
              . "INNER JOIN clients ON clients.user_id = reference_profile.client_id "
              . "INNER JOIN users ON users.id = clients.user_id "
              . "WHERE daily_work.reference_id = $reference_id "
              . "LIMIT 1;";

          $query = $this->db->query($sql);
          $object = $query->row();

      // Update daily work time
      if ($object && (!isset($object->last_access) || intval($object->last_access) < time())) {
        //$ref_prof_id = $object->rp_insta_id;
        $time = time();
        $sql2 = ""
                . "UPDATE clients "
                . "SET clients.last_access = '$time' "
                . "WHERE clients.user_id = $object->users_id; ";
        $result2 = $this->db->query($sql2);
        if (!$result2) {
          //var_dump($sql2);
        }
      }
      return $object;
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 17 TO CHECK
  public function get_follow_work_by_client_id($client_id, $rp = NULL) {
    //$Elapsed_time_limit = $GLOBALS['sistem_config']->MIN_NEXT_ATTEND_TIME;
    try {
      // Get daily work
      $sql = ""
              . "SELECT *, "
              . "   daily_work.cookies as cookies, "
              . "   users.id as users_id, "
              . "   clients.cookies as client_cookies, "
              . "   reference_profile.insta_id as rp_insta_id, "
              . "   reference_profile.type as rp_type, "
              . "   reference_profile.id as rp_id "
              . "FROM daily_work "
              . "INNER JOIN reference_profile ON reference_profile.id = daily_work.reference_id "
              . "INNER JOIN clients ON clients.user_id = reference_profile.client_id "
              . "INNER JOIN users ON users.id = clients.user_id ";
      if ($rp == NULL) {
        $sql .= "WHERE daily_work.reference_id IN (SELECT id FROM reference_profile WHERE reference_profile.client_id = $client_id) ";
      } else {
        $sql .= "WHERE daily_work.reference_id = $rp ";
      }
      $sql .= "ORDER BY reference_profile.last_access ASC "
              . "LIMIT 1;";

      $result = $this->db->query($sql);
      $object = $result->result_object();
      if ($object != NULL) {
        $object->last_access = $object->last_access - 86400;
        // Update daily work time
        if ($object && (!isset($object->last_access) || intval($object->last_access) < time())) {
          //$ref_prof_id = $object->rp_insta_id;
          $time = time() + 86400;
          $sql2 = ""
                  . "UPDATE clients "
                  . "SET clients.last_access = '$time' "
                  . "WHERE clients.user_id = $object->users_id; ";
          $result2 = $this->db->query($sql2);
          if (!$result2) {
            var_dump($sql2);
          }

          $sql2 = ""
                  . "UPDATE reference_profile "
                  . "SET reference_profile.last_access = '$time' "
                  . "WHERE reference_profile.id = $object->rp_id; ";
          $result2 = $this->db->query($sql2);

          if (!$result2) {
            var_dump($sql2);
          }
        }
      }
      return $object;
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }
  
  //FUNC 18 TO CHECK
  public function get_unfollow_work($client_id) {
    try {
      // Get profiles to unfollow today for this Client... 
      // (i.e the last followed)
      $Limit = $GLOBALS['sistem_config']->REQUESTS_AT_SAME_TIME;
      $Elapsed_time_limit = $GLOBALS['sistem_config']->UNFOLLOW_ELAPSED_TIME_LIMIT;
      $result = mysqli_query($this->fConnection, ""
              . "SELECT * FROM `dumbudb.followed`.`$client_id` "
              . "WHERE unfollowed = false "
              . "     AND ((UNIX_TIMESTAMP(NOW()) - CAST(date AS INTEGER)) DIV 60 DIV 60) > $Elapsed_time_limit "
              . "ORDER BY date ASC "
              . "LIMIT $Limit;"
      );
      $query = $this->db->query($sql);
        return $query->result();
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }
  
  //FUNC 19 OK
  public function get_system_config_vars() {
    try {

      $sql = "SELECT * FROM dumbu_system_config;";
      $query = $this->db->query($sql);
      return $query->result();
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 20 OK
  public function get_white_list($id_user) {
    try {
      $sql = ""
              . "SELECT insta_id "
              . "FROM black_and_white_list "
              . "WHERE black_and_white_list.client_id = $id_user AND black_and_white_list.black_or_white = 1 AND black_and_white_list.deleted = 0 "
              . "ORDER BY black_and_white_list.insta_id;";
      $query = $this->db->query($sql);
      return $query->result();
      
      /*$new_array = NULL;
      while ($obj = $result->result_object()) {
        $new_array[] = $obj->insta_id; // Inside while loop
      }
      return $new_array;*/
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 21 OK
  public function get_white_list_paged($id_user, $offset, $rows) {
    try {
      $sql = ""
              . "SELECT insta_id "
              . "FROM black_and_white_list "
              . "WHERE black_and_white_list.client_id = $id_user AND black_and_white_list.black_or_white = 1 AND black_and_white_list.deleted = 0 "
              . "LIMIT $offset, $rows;";
            $this->db->limit($offset, $rows);
            $query = $this->db->query($sql);
            return $query->result();
      /*$new_array = NULL;
      while ($obj = $result->result_object()) {
        $new_array[] = $obj->insta_id; // Inside while loop
      }
      return $new_array;*/
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 22 OK
  public function get_black_list($id_user) {
    try {
      $sql = ""
              . "SELECT insta_id "
              . "FROM black_and_white_list "
              . "WHERE black_and_white_list.client_id = $id_user AND black_and_white_list.black_or_white = 0 AND black_and_white_list.deleted = 0 "
              . "ORDER BY black_and_white_list.insta_id;";
      $query = $this->db->query($sql);
      return $query->result();
      /*$new_array = NULL;
      while ($obj = $result->result_object()) {
        $new_array[] = $obj->insta_id; // Inside while loop
      }
      return $new_array;*/
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 23 OK
  public function get_client_with_white_list() {
    try {
      $sql = "SELECT DISTINCT client_id FROM dumbudb.black_and_white_list WHERE  black_or_white = 1;";
      $query = $this->db->query($sql);
      return $query->result();
      /*$new_array = NULL;
      while ($obj = $result->result_object()) {
        $new_array[] = $obj->client_id; // Inside while loop
      }
      return $new_array;*/
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }
  
  //FUNC 24 OK
  public function get_client_with_orderkey($orderkey) {

    try {
      $sql = "SELECT * FROM  clients "
              . "WHERE  clients.order_key = '$orderkey';";
      $query = $this->db->query($sql);
      return $query->row();
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 25 OK
  public function get_number_followed_today($client_id) {
    try {


      if ($client_id != '0' && $client_id != 0) {
        $limit = strtotime('today 02:00:00');

        if (time() > strtotime('today') && time() < strtotime('today 03:00:00'))
          $limit = strtotime('yesterday 02:00:00');
        $id = intval($client_id);
        $sql = ""
                . "SELECT COUNT(*) FROM `dumbudb.followed`.`$id` "
                . "WHERE unfollowed = 0 AND date > " . $limit . ";";
        $query = $this->db->query($sql);
        return $query->result();
       /* if ($result) {
          $data = $result->fetch_row();
          return $data[0];
        } else {
          return "???";
        }
      } else
        return 0;*/
      }
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 26 OK
  public function get_reference_profiles_with_problem($client_id) {
    try {

      $sql = ""
              . "SELECT * FROM reference_profile "
              . "WHERE "
              . "  (reference_profile.client_id = $client_id) "
              . "  AND (reference_profile.deleted <> TRUE)"
              . "  AND end_date IS NOT NULL AND end_date <> '';";
      $query = $this->db->query($sql);
      return $query->result();
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }
  
  //FUNC 27 OK
  public function get_not_reserved_proxy_list() {
    try {
      $sql = "SELECT * FROM Proxy WHERE isReserved = FALSE;";
      $query = $this->db->query($sql);
      return $query->result();
      
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 28 OK
  public function get_proxy($idProxy) {
    try {
      $sql = "SELECT * FROM Proxy WHERE idProxy = $idProxy;";
      $query = $this->db->query($sql);
      return $query->row();
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 29 OK
  public function get_proxy_plient_counts($proxy) {
    try {
      $BEGINNER = UserStatus::BEGINNER;
      $DELETED = UserStatus::DELETED;
      $sql = "SELECT COUNT(clients.user_id) as cnt FROM dumbudb.clients "
              . "INNER JOIN Proxy ON clients.proxy = Proxy.idProxy "
              . "INNER JOIN users ON user_id = users.id "
              . "WHERE dumbudb.Proxy.proxy = '$proxy' AND users.status_id NOT IN ($BEGINNER, $DELETED);";
      $query = $this->db->query($sql);
      return $query->row();
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 30 OK
  public function get_client_withou_proxy() {
    try {
      $BEGINNER = UserStatus::BEGINNER;
      $DELETED = UserStatus::DELETED;
      $sql = "SELECT user_id FROM  clients "
              . "INNER JOIN users ON user_id = users.id "
              . "WHERE proxy IS NULL AND users.status_id NOT IN ($BEGINNER, $DELETED);";
      $query = $this->db->query($sql);
      return $query->result();
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }
  
  //FUNC 31 OK
  public function get_dumbu_statistics() {
    try {
      //clientes por status
      $sql = "SELECT status_id,count(*) as cnt FROM dumbudb.users GROUP BY status_id;";
      $query = $this->db->query($sql);
      return $query->result();
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 32 OK
  public function get_dumbu_paying_customers() {
    try {
      //clientes pagantes
      $sql = "SELECT count(*) as cnt FROM dumbudb.users JOIN dumbudb.clients ON users.id=clients.user_id WHERE users.status_id in (1,3,5,6,7,9,10) AND credit_card_number<>'' AND credit_card_number<>'PAYMENT_BY_TICKET_BANK' AND credit_card_number is not NULL;";
      $query = $this->db->query($sql);
      return $query->row();
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 33 OK
  public function get_reference_profile_status() {
    try {
      //clientes por status
      $sql = "SELECT *  FROM reference_profiles_status;";
      $query = $this->db->query($sql);
      return $query->result();
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }
  
  //======================>SET<=======================//
  
  //FUNC 34
  public function set_client_status($client_id, $status_id) {
    try {

      $status_date = time();
      $sql = "UPDATE users "
              . "SET "
              . "      users.status_id   = $status_id, "
              . "      users.status_date = '$status_date' "
              . "WHERE users.id = $client_id; ";

      $result = $this->db->query($sql);
//                if ($result)
//                    print "<br>Update client_status! status_date: $status_date <br>";
//                else
//                    print "<br>NOT UPDATED client_status!!!<br> $sql <br>";
      return $result;
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 35
  public function set_client_status_by_login($login, $status_id) {
    try {

      $status_date = time();
      $sql = "UPDATE users "
              . "SET "
              . "      users.status_id   = $status_id, "
              . "      users.status_date = '$status_date' "
              . "WHERE users.login = '$login' "
              . "ORDER BY id DESC "
              . "LIMIT 1; ";

      $result = $this->db->query($sql);
//                if ($result)
//                    print "<br>Update client_status! status_date: $status_date <br>";
//                else
//                    print "<br>NOT UPDATED client_status!!!<br> $sql <br>";
      return $result;
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }
  
  //FUNC 36
  public function set_client_cookies($client_id, $cookies = NULL) {
    try {

      $sql = "UPDATE clients "
              . "SET ";
      $sql .= $cookies ? " clients.cookies   = '$cookies' " : " clients.cookies   = NULL ";
      $sql .= "WHERE clients.user_id = '$client_id'; ";

      $result = $this->db->query($sql);
      //if ($result)
      // print "<br>Update client_cookies! <br>";
      //else
      // print "<br>NOT UPDATED client_cookies!!!<br> $sql <br>";
      return $result;
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }
  
  //FUNC 37
  public function set_pasword($client_id, $password) {
    try {
      $sql = "UPDATE dumbudb.users SET pass='$password' WHERE id=$client_id";
      $result = $this->db->query($sql);
      return $result;
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }
  
  //FUNC 38
  public function set_cookies_to_null($client_id) {
    try {
      $sql = "UPDATE dumbudb.clients SET cookies=NULL WHERE user_id=$client_id";
      $result = $this->db->query($sql);
      return $result;
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }
  
  //FUNC 39
  public function set_client_last_access($client_id, $timestamp) {
    try {
      $sql = "UPDATE dumbudb.clients SET last_access='$timestamp' WHERE user_id=$client_id";
      $result = $this->db->query($sql);
      return $result;
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }
  
  //FUNC 40
  public function set_client_order_key($client_id, $order_key, $pay_day) {
    try {
      $sql = "UPDATE `dumbudb`.`clients` SET `pay_day`='$pay_day', `order_key`='$order_key' WHERE `user_id`=$client_id;";
      $result = $this->db->query($sql);
      return $result;
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 41
  public function set_proxy_to_client($client_id, $proxy_id) {
    try {
      $sql = "UPDATE clients SET clients.proxy = $proxy_id WHERE clients.user_id = $client_id;";
      $result = $this->db->query($sql);
      return $result;
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }
  
  //=======================>INSERT<========================//
  
  //FUNC 42
  public function insert_client_daily_report($client_id, $profile_data) {
    try {

      $date = time();
      $sql = "INSERT INTO daily_report "
              . "(client_id, followings, followers, date) "
              . "VALUES "
              . "($client_id, '$profile_data->following', '$profile_data->follower_count', '$date');";

      $result = $this->db->query($sql);
      return $result;
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }
  
  //FUNC 43
  public function insert_daily_work($ref_prof_id, $to_follow, $to_unfollow, $login_data) {
    try {
      $sql = ""
              . "INSERT INTO daily_work "
              . "(reference_id, to_follow, to_unfollow, cookies) "
              . "VALUES "
              . "($ref_prof_id, $to_follow, $to_unfollow, '$login_data');";

      $result = $this->db->query($sql);

      return $result;
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }
  
  //FUNC 44
  public function insert_event_to_washdog($user_id, $action, $source = 0, $robot_id = NULL, $metadata = NULL) {
    try {
      //mysqli_real_escape_string($escapestr)
      $action = mysqli_real_escape_string($this->connection, $action);
      $sql = "SELECT * FROM dumbudb.washdog_type WHERE action = '$action' AND source = '$source';";
      $time = time();
      $result = $this->db->query($sql);
      if ($result->num_rows == 0) {
        $sql = "INSERT INTO dumbudb.washdog_type (action, source) VALUE ('$action', '$source');";
        $result = $this->db->query($sql);
        //var_dump($result);
        $sql = "SELECT * FROM dumbudb.washdog_type WHERE action = '$action' AND source = '$source';";
        $time = time();
        $result = $this->db->query($sql);
      }

      $obj = $result->result_object();
      if (isset($robot_id) == true) {
        $sql = "INSERT INTO dumbudb.washdog1 (user_id, type, date, robot, metadata) VALUE ('$user_id','$obj->id', '$time', $robot_id, '$metadata');";
      } else {
        $sql = "INSERT INTO dumbudb.washdog1 (user_id, type, date, robot, metatdata) VALUE ('$user_id','$obj->id', '$time', NULL, '$metadata');";
      }
      $result = $this->db->query($sql);
      return $result;
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }
  
  //FUNC 45
  public function insert_dumbu_statistics($cols, $arr) {
    try {
      $sql = "INSERT INTO dumbudb.dumbu_statistic " . $cols . " VALUE " . $arr . ";";
      $result = $this->db->query($sql);
      return $result;
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }
  
  //=======================>UPDATE<========================//
  
  //FUNC 46
  public function update_daily_work($ref_prof_id, $follows, $unfollows, $faults = 0) {
    try {
      $sql = ""
              . "UPDATE daily_work "
              . "SET daily_work.to_follow   = (daily_work.to_follow   - $follows), "
              . "    daily_work.to_unfollow = (daily_work.to_unfollow - $unfollows) "
              . "WHERE daily_work.reference_id = $ref_prof_id; ";

      $result1 = $this->db->query($sql);
      // Record Client last access and foults
      $time = time();
      $sql = ""
              . "UPDATE clients "
              . "INNER JOIN reference_profile ON clients.user_id = reference_profile.client_id "
              . "SET clients.last_access = '$time', "
              . "    clients.foults = clients.foults + $faults "
              . "WHERE reference_profile.id = $ref_prof_id; ";
      $result2 = $this->db->query($sql);
      //    if ($result2) {
      //    }
      //$affected = mysqli_num_rows($result);
      if ($result1) {
        print "<br>Update daily_work! follows: $follows | unfollows: $unfollows <br>";
      } else
        print "<br>NOT UPDATED daily_work!!!<br> $sql <br>";
      return TRUE;
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 47
  public function update_reference_cursor($reference_id, $end_cursor) {
    $date = ($end_cursor == '' || $end_cursor == NULL) ? time() : NULL;
    $ended_status = (new reference_profiles_status())->ENDED;
    try {
      $sql = ""
              . "UPDATE reference_profile "
              . "SET "
              . "     reference_profile.insta_follower_cursor = '$end_cursor', "
              . "     reference_profile.end_date = '$date' ";
      if ($date !== NULL) {
        $sql .= ", reference_profile.status_id = $ended_status ";
      }
      $sql .= "WHERE reference_profile.id = $reference_id; ";

      $result = $this->db->query($sql);

//                if ($result)
//                    print "<br>Updated reference_cursor! reference_id: $reference_id <br>";
//                else
//                    print "<br>NOT UPDATED reference_cursor!!!<br> $sql <br>";

      return $result;
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 48
  public function update_reference_profile_status($status, $id) {
    return $this->update_table_field("reference_profile", "status_id", $status, "WHERE id = $id");
  }

  //FUNC 49
  public function update_table_field($table, $field, $value, $query) {
    try {
      $sql = "UPDATE $table SET $table.$field = $value $query;";
      $result = $this->db->query($sql);
      return $result;
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //======================>DELETED<========================//
  
  //FUNC 50
  public function delete_daily_work($ref_prof_id) {
    try {
      $sql = ""
              . "DELETE FROM daily_work "
              . "WHERE reference_id = $ref_prof_id; ";
      $result = $this->db->query($sql);

      return $result;
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 51
  public function delete_daily_work_client($client_id) {
    try {
      $sql = ""
              . "DELETE FROM daily_work WHERE daily_work.reference_id IN "
              . "(SELECT reference_profile.id "
              . "FROM reference_profile "
              . "INNER JOIN clients ON clients.user_id = reference_profile.client_id "
              . "WHERE clients.user_id = $client_id); ";
      $result = $this->db->query($sql);

      return $result;
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }
  
  //========================>SAVE<=========================//
  
  //FUNC 52
  public function save_unfollow_work($Followeds_to_unfollow) {
    try {
      foreach ($Followeds_to_unfollow as $unfollowed) {
        if ($unfollowed->unfollowed) {

          $result = $this->db->query(""
                  . "UPDATE followed "
                  . "SET followed.unfollowed = TRUE "
                  . "WHERE followed.id = $unfollowed->id; "
          );
        }
      }

      // TODO: UNCOMMENT
//                $sql = ""
//                        . "DELETE FROM followed "
//                        . "WHERE id = $unfollowed->id; ";
//                $result = $this->db->query($sql);

      return TRUE;
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 53
  public function save_unfollow_work_db2($Followeds_to_unfollow, $client_id) {
    try {
      foreach ($Followeds_to_unfollow as $unfollowed) {
        if ($unfollowed->unfollowed) {

          $result = mysqli_query($this->fConnection, ""
                  . "UPDATE `dumbudb.followed`.`$client_id` "
                  . "SET unfollowed = TRUE "
                  . "WHERE followed_id = $unfollowed->followed_id; "
          );
        }
      }

      // TODO: UNCOMMENT
//                $sql = ""
//                        . "DELETE FROM followed "
//                        . "WHERE id = $unfollowed->id; ";
//                $result = $this->db->query($sql);

      return TRUE;
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 54
  public function save_follow_work($Ref_profile_follows, $daily_work) {
    try {
      //daily work: reference_id 	to_follow 	last_access 	id 	insta_name 	insta_id 	client_id 	insta_follower_cursor 	user_id 	credit_card_number 	credit_card_status_id 	credit_card_cvc 	credit_card_name 	pay_day 	insta_id 	insta_followers_ini 	insta_following 	id 	name 	login 	pass 	email 	telf 	role_id 	status_id 	languaje 
      foreach ($Ref_profile_follows as $follow) {

        $time = time();
        $requested = ($follow->requested_by_viewer == 'requested') ? 'TRUE' : 'FALSE';
        /* $sql = ""
          . "INSERT INTO followed "
          . "(followed_id, client_id, reference_id, requested, date, unfollowed) "
          . "VALUES "
          . "($follow->id, $daily_work->client_id, $daily_work->reference_id, $requested, $time, FALSE);"; */

        $sql2 = ""
                . "INSERT INTO `dumbudb.followed`.`$daily_work->client_id`"
                . "(followed_id, reference_id, date, unfollowed, followed_login) "
                . "VALUES "
                . "($follow->id, $daily_work->reference_id, $time, FALSE, '$follow->username');";
        //$result = $this->db->query($sql);
        $result2 = mysqli_query($this->fConnection, $sql2);
      }

      $f_count = count($Ref_profile_follows);
      $sql = ""
              . "UPDATE reference_profile "
              . "	SET reference_profile.follows = reference_profile.follows + $f_count "
              . "WHERE reference_profile.id = $daily_work->reference_id; ";
      $result = $this->db->query($sql);

      return TRUE;
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }
  
  //FUNC 55
  public function save_http_server_vars($client_id, $HTTP_SERVER_VARS) {
    try {
      $sql = "UPDATE dumbudb.clients SET HTTP_SERVER_VARS='$HTTP_SERVER_VARS' WHERE user_id=$client_id";
      $result = $this->db->query($sql);
      return $result;
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }
  
  //=======================>RESET<========================//
  
  //FUNC 56
  public function reset_preference_profile_cursors() {
    try {
      $sql = ""
              . "UPDATE reference_profile "
              . "SET reference_profile.insta_follower_cursor = null;  ";
      $result = $this->db->query($sql);

      return $result;
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }
  
  //FUNC 57
  public function reset_referecne_prof($reference_id) {
    try {

      $result = $this->db->query("UPDATE `dumbudb`.`reference_profile` "
              . "SET `insta_follower_cursor`=NULL, `end_date`=NULL "
              . "WHERE `id`=$referece_id;");
      return $result;
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }
  
  //=======================>OTHERS<========================//
  
  //FUNC 58
  public function cmd_is_profile_followed($client_id, $followed_id) {
    try {
      $sql = ""
          . "SELECT id FROM `dumbudb.followed`.`$client_id` "
          . "WHERE `$client_id`.followed_id = $followed_id; ";
      
      $query = $this->db->query($sql);
      return $query->result();
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }
  
  //FUNC 59
  public function cmd_has_work($client_id, $rp = NULL) {
    //$Elapsed_time_limit = $GLOBALS['sistem_config']->MIN_NEXT_ATTEND_TIME;
    try {
      // Get daily work
      $sql = ""
              . "SELECT * "
              . "FROM daily_work "
              . "INNER JOIN reference_profile ON reference_profile.id = daily_work.reference_id "
              . "INNER JOIN clients ON clients.user_id = reference_profile.client_id "
              . "INNER JOIN users ON users.id = clients.user_id ";
      if ($rp == NULL) {
        $sql .= "WHERE daily_work.reference_id IN (SELECT id FROM reference_profile WHERE reference_profile.client_id = $client_id);";
      } else {
        $sql .= "WHERE daily_work.reference_id = $rp;";
      }

      $result = $this->db->query($sql);
      $object = $result->result();

      return $object != NULL;
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }
  
  //FUNC 60
  public function cmd_add_observation($client_id, $observation) {
    try {
      $observation = mysqli_real_escape_string($this->connection, $observation);
      $sql = "UPDATE dumbudb.clients SET observation='$observation' WHERE user_id=$client_id";
      $result = $this->db->query($sql);
      return $result;
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 61
  public function cmd_create_followed($client_id) {
    try {
      $sql = "CREATE TABLE IF NOT EXISTS `dumbudb.followed`.`$client_id` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `followed_id` VARCHAR(20) NULL,
                `reference_id` INT(1) NOT NULL,
                `date` VARCHAR(20) NULL,
                `unfollowed` TINYINT(1) NULL,
                `followed_login` VARCHAR(100) NULL DEFAULT NULL,
                PRIMARY KEY (`id`, `reference_ingd`),
                INDEX `fk__1_idx` (`reference_id` ASC),
                CONSTRAINT `fk__$client_id`
                  FOREIGN KEY (`reference_id`)
                  REFERENCES `dumbudb`.`reference_profile` (`id`)
                  ON DELETE NO ACTION
                  ON UPDATE NO ACTION);";
      $result = mysqli_query($this->fConnection, $sql);
      return $result;
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

  //FUNC 62
  public function cmd_increase_client_last_access($client_id, $hours = 1) {
    try {
      $timestamp = strtotime("+$hours hours", time());
      $this->set_client_last_access($client_id, $timestamp);
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }
  
  //FUNC 63
  public function cmd_truncate_daily_work() {
    try {
      $sql = "TRUNCATE daily_work;";

      $result = $this->db->query($sql);

      return $result;
    } catch (Error $e) {
      if ($this->db->error()['code'] != 0) {
        throw new Db_Exception($this->db->error(), $e);
      } else {
        throw $e;
      }
    }
  }

}
