<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @category CodeIgniter-Model: user_status_Model
 * 
 * @access public
 * 
 * @todo <description>
 * 
 */
class User_status_model extends CI_Model {
  function construct() {
    parent::construct();
  }

  function save($name, $description) {
    $this->name = $name;
    $this->description = $description;
    $this->db->insert('user_status', $this);

    return $this->db->insert_id();
  }

  function remove($id) {
    $this->db->delete('user_status', array('id' => $id));
  }

  function update($id, $name, $description) {
    $this->name = $name;
    $this->description = $description;

    $this->db->update('user_status', $this, array('id' => $id));
  }

  function get_by_id($id) {
    $this->db->where('id', $id);
    $query = $this->db->get('user_status');

    return $query->row();
  }

  function get_all($offset = 0, $rows = 0){	
    $this->db->limit($offset, $rows);	
    $this->db->select('*')->from('user_status');
    //$this->db->order_by('<field>', '<type>'); ==> asc/desc
    $query = $this->db->get();

    return $query->result();
  }
}

?>

