<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @category CodeIgniter-Model: black_and_white_list_Model
 * 
 * @access public
 *
 * @todo <description>
 * 
 */
class Black_and_white_list_model extends CI_Model {

  function construct() {
    parent::construct();
  }

  function save($client_id, $insta_id, $profile, $init_date, $end_date, $deleted, $black_or_white) {
    $this->client_id = $client_id;
    $this->insta_id = $insta_id;
    $this->profile = $profile;
    $this->init_date = $init_date;
    $this->end_date = $end_date;
    $this->deleted = $deleted;
    $this->black_or_white = $black_or_white;
    $this->db->insert('black_and_white_list', $this);

    return $this->db->insert_id();
  }

  function remove($id) {
    $this->db->delete('black_and_white_list', array('id' => $id));
  }

  function update($id, $client_id, $insta_id, $profile, $init_date, $end_date, $deleted, $black_or_white) {
    $this->client_id = $client_id;
    $this->insta_id = $insta_id;
    $this->profile = $profile;
    $this->init_date = $init_date;
    $this->end_date = $end_date;
    $this->deleted = $deleted;
    $this->black_or_white = $black_or_white;

    $this->db->update('black_and_white_list', $this, array('id' => $id));
  }

  function get_by_id($id) {
    $this->db->where('id', $id);
    $query = $this->db->get('black_and_white_list');

    return $query->row();
  }

  function get_all($offset = 0, $rows = 0){	
    $this->db->limit($offset, $rows);
    $this->db->select('*')->from('black_and_white_list');
    //$this->db->order_by('<field>', '<type>'); ==> asc/desc
    $query = $this->db->get();

    return $query->result();
  }
}

?>

