<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @category CodeIgniter-Model: washdog_type_Model
 * 
 * @access public
 * 
 * @todo <description>
 * 
 */
class Washdog_type_model extends CI_Model {
  function construct() {
    parent::construct();
  }

  function save($action, $source) {
    $this->action = $action;
    $this->source = $source;
    $this->db->insert('washdog_type', $this);

    return $this->db->insert_id();
  }

  function remove($id) {
    $this->db->delete('washdog_type', array('id' => $id));
  }

  function update($id, $action, $source) {
    $this->action = $action;
    $this->source = $source;
    
    $this->db->update('washdog_type', $this, array('id' => $id));
  }

  function get_by_id($id) {
    $this->db->where('id', $id);
    $query = $this->db->get('washdog_type');

    return $query->row();
  }

  function get_all($offset = 0, $rows = 0){	
    $this->db->limit($offset, $rows);	
    $this->db->select('*')->from('washdog_type');
    //$this->db->order_by('<field>', '<type>'); ==> asc/desc
    $query = $this->db->get();

    return $query->result();
  }
}

?>

