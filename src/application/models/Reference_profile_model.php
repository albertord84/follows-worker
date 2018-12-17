<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @category CodeIgniter-Model: reference_profile_Model
 * 
 * @access public
 * 
 * @todo <description>
 * 
 */
class Reference_profile_model extends CI_Model {
  function construct() {
    parent::construct();
  }

  function save($insta_name, $insta_id, $status_id, $client_id, $insta_follower_cursor, $deleted, $end_date, $follows, $type, $last_access) {
    $this->insta_name = $insta_name;
    $this->insta_id = $insta_id;
    $this->status_id = $status_id;
    $this->client_id = $client_id;
    $this->insta_follower_cursor = $insta_follower_cursor;
    $this->deleted = $deleted;
    $this->end_date = $end_date;
    $this->follows = $follows;
    $this->type = $type;
    $this->last_access = $last_access;
    $this->db->insert('reference_profile', $this);

    return $this->db->insert_id();
  }

  function remove($id) {
    $this->db->delete('reference_profile', array('id' => $id));
  }

  function update($id, $insta_name, $insta_id, $status_id, $client_id, $insta_follower_cursor, $deleted, $end_date, $follows, $type, $last_access) {
    $this->insta_name = $insta_name;
    $this->insta_id = $insta_id;
    $this->status_id = $status_id;
    $this->client_id = $client_id;
    $this->insta_follower_cursor = $insta_follower_cursor;
    $this->deleted = $deleted;
    $this->end_date = $end_date;
    $this->follows = $follows;
    $this->type = $type;
    $this->last_access = $last_access;
    
    $this->db->update('reference_profile', $this, array('id' => $id));
  }

  function get_by_id($id) {
    $this->db->where('id', $id);
    $query = $this->db->get('reference_profile');

    return $query->row();
  }

  function get_all($offset = 0, $rows = 0){	
    $this->db->limit($offset, $rows);	
    $this->db->select('*')->from('reference_profile');
    //$this->db->order_by('<field>', '<type>'); ==> asc/desc
    $query = $this->db->get();
    
    return $query->result();
  }
}

?>

