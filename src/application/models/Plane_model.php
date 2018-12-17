<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @category CodeIgniter-Model: plane_Model
 * 
 * @access public
 * 
 * @todo <description>
 * 
 */
class Plane_model extends CI_Model {
  function construct() {
    parent::construct();
  }

  function save($initial_val, $normal_val, $to_follow, $gateway_prod_id, $gateway_plane_id) {
    $this->initial_val = $initial_val;
    $this->normal_val = $normal_val;
    $this->to_follow = $to_follow;
    $this->gateway_prod_id = $gateway_prod_id;
    $this->gateway_plane_id = $gateway_plane_id;
    $this->db->insert('plane', $this);
    
    return $this->db->insert_id();
  }

  function remove($id) {
    $this->db->delete('plane', array('id' => $id));
  }

  function update($id, $initial_val, $normal_val, $to_follow, $gateway_prod_id, $gateway_plane_id) {
    $this->initial_val = $initial_val;
    $this->normal_val = $normal_val;
    $this->to_follow = $to_follow;
    $this->gateway_prod_id = $gateway_prod_id;
    $this->gateway_plane_id = $gateway_plane_id;

    $this->db->update('plane', $this, array('id' => $id));
  }

  function get_by_id($id) {
    $this->db->where('id', $id);
    $query = $this->db->get('plane');

    return $query->row();
  }

  function get_all($offset = 0, $rows = 0){	
    $this->db->limit($offset, $rows);	
    $this->db->select('*')->from('plane');
    //$this->db->order_by('<field>', '<type>'); ==> asc/desc
    $query = $this->db->get();

    return $query->result();
  }
}

?>

