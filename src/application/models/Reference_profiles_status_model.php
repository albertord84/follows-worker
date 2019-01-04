<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @category CodeIgniter-Model: reference_profiles_status_Model
 * 
 * @access public
 * 
 * @todo <description>
 * 
 */
class Reference_profiles_status_model extends CI_Model {
  function construct() {
    parent::construct();
  }

  function save($status, $description) {
    $this->status = $status;
    $this->description = $description;
    $this->db->insert('reference_profiles_status', $this);

    return $this->db->insert_id();
  }

  function remove($id) {
    $this->db->delete('reference_profiles_status', array('id' => $id));
  }

  function update($id, $status, $description) {
    $this->status = $status;
    $this->description = $description;
    
    $this->db->update('reference_profiles_status', $this, array('id' => $id));
  }

  function get_by_id($id) {
    $this->db->where('id', $id);
    $query = $this->db->get('reference_profiles_status');

    return $query->row();
  }

  function get_all($offset = 0, $rows = 0){	
    $this->db->limit($offset, $rows);	
    $this->db->select('*')->from('reference_profiles_status');
    //$this->db->order_by('<field>', '<type>'); ==> asc/desc
    $query = $this->db->get();

    return $query->result();
  }
}

?>

