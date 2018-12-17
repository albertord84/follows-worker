<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @category CodeIgniter-Model: washdog1_Model
 * 
 * @access public
 * 
 * @todo <description>
 * 
 */
class Washdog1_model extends CI_Model {
  function construct() {
    parent::construct();
  }

  function save($user_id, $type, $date, $robot, $metadata) {
    $this->user_id = $user_id;
    $this->type = $type;
    $this->date = $date;
    $this->robot = $robot;
    $this->metadata = $metadata;
    $this->db->insert('washdog1', $this);

    return $this->db->insert_id();
  }

  function remove($id) {
    $this->db->delete('washdog1', array('id' => $id));
  }

  function update($id, $user_id, $type, $date, $robot, $metadata) {
    $this->user_id = $user_id;
    $this->type = $type;
    $this->date = $date;
    $this->robot = $robot;
    $this->metadata = $metadata;

    $this->db->update('washdog1', $this, array('id' => $id));
  }

  function get_by_id($id) {
    $this->db->where('id', $id);
    $query = $this->db->get('washdog1');

    return $query->row();
  }

  function get_all($offset = 0, $rows = 0){	
    $this->db->limit($offset, $rows);	
    $this->db->select('*')->from('washdog1');
    //$this->db->order_by('<field>', '<type>'); ==> asc/desc
    $query = $this->db->get();

    return $query->result();
  }
}

?>

