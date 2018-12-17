<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @category CodeIgniter-Model: ranking_Model
 * 
 * @access public
 * 
 * @todo <description>
 * 
 */
class Ranking_model extends CI_Model {
  function construct() {
    parent::construct();
  }

  function save($client_id, $position, $date) {
    $this->client_id = $client_id;
    $this->position = $position;
    $this->date = $date;
    $this->db->insert('ranking', $this);

    return $this->db->insert_id();
  }

  function remove($id) {
    $this->db->delete('ranking', array('id' => $id));
  }

  function update($id, $client_id, $position, $date) {
    $this->client_id = $client_id;
    $this->position = $position;
    $this->date = $date;

    $this->db->update('ranking', $this, array('id' => $id));
  }

  function get_by_id($id) {
    $this->db->where('id', $id);
    $query = $this->db->get('ranking');

    return $query->row();
  }

  function get_all($offset = 0, $rows = 0){	
    $this->db->limit($offset, $rows);	
    $this->db->select('*')->from('ranking');
    //$this->db->order_by('<field>', '<type>'); ==> asc/desc
    $query = $this->db->get();

    return $query->result();
  }
}

?>

