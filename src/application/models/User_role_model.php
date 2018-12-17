<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @category CodeIgniter-Model: user_role_Model
 * 
 * @access public
 * 
 * @todo <description>
 * 
 */
class User_role_model extends CI_Model {
  function construct() {
    parent::construct();
  }

  function save($name) {
    $this->name = $name;
    $this->db->insert('user_role', $this);

    return $this->db->insert_id();
  }

  function remove($id) {
    $this->db->delete('user_role', array('id' => $id));
  }

  function update($id, $name) {
    $this->name = $name;
    $this->db->update('user_role', $this, array('id' => $id));
  }

  function get_by_id($id) {
    $this->db->where('id', $id);
    $query = $this->db->get('user_role');

    return $query->row();
  }

  function get_all($offset = 0, $rows = 0){	
    $this->db->limit($offset, $rows);	
    $this->db->select('*')->from('user_role');
    //$this->db->order_by('<field>', '<type>'); ==> asc/desc
    $query = $this->db->get();
    
    return $query->result();
  }
}

?>

