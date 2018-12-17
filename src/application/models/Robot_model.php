<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @category CodeIgniter-Model: robot_Model
 * 
 * @access public
 * 
 * @todo <description>
 * 
 */
class Robot_model extends CI_Model {
  function construct() {
    parent::construct();
  }

  function save($ip, $dir) {
    $this->ip = $ip;
    $this->dir = $dir;
    $this->db->insert('robot', $this);

    return $this->db->insert_id();
  }

  function remove($id) {
    $this->db->delete('robot', array('id' => $id));
  }

  function update($id, $ip, $dir) {
    $this->ip = $ip;
    $this->dir = $dir;

    $this->db->update('robot', $this, array('id' => $id));
  }

  function get_by_id($id) {
    $this->db->where('id', $id);
    $query = $this->db->get('robot');

    return $query->row();
  }

  function get_all($offset = 0, $rows = 0){	
    $this->db->limit($offset, $rows);	
    $this->db->select('*')->from('robot');
    //$this->db->order_by('<field>', '<type>'); ==> asc/desc
    $query = $this->db->get();

    return $query->result();
  }
}

?>

