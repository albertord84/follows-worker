<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @category CodeIgniter-Model: worker_Model
 * 
 * @access public
 * 
 * @todo <description>
 * 
 */
class Worker_model extends CI_Model {
  function construct() {
    parent::construct();
  }

  function save($ip, $dir) {
    $this->ip = $ip;
    $this->dir = $dir;
    $this->db->insert('worker', $this);

    return $this->db->insert_id();
  }

  function remove($id) {
    $this->db->delete('worker', array('id' => $id));
  }

  function update($id, $ip, $dir) {
    $this->ip = $ip;
    $this->dir = $dir;
    
    $this->db->update('worker', $this, array('id' => $id));
  }

  function get_by_id($id) {
    $this->db->where('id', $id);
    $query = $this->db->get('worker');
    
    return $query->row();
  }

  function get_all($offset = 0, $rows = 0){	
    $this->db->limit($offset, $rows);	
    $this->db->select('*')->from('worker');
    //$this->db->order_by('<field>', '<type>'); ==> asc/desc
    $query = $this->db->get();

    return $query->result();
  }
}

?>

