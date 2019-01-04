<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @category CodeIgniter-Model: translation_Model
 * 
 * @access public
 * 
 * @todo <description>
 * 
 */
class Translation_model extends CI_Model {
  function construct() {
    parent::construct();
  }

  function save($token, $portugues, $ingles, $espanol, $active) {
    $this->token = $token;
    $this->portugues = $portugues;
    $this->ingles = $ingles;
    $this->espanol = $espanol;
    $this->active = $active;
    $this->db->insert('translation', $this);

    return $this->db->insert_id();
  }

  function remove($id) {
    $this->db->delete('translation', array('id' => $id));
  }

  function update($id, $token, $portugues, $ingles, $espanol, $active) {
    $this->token = $token;
    $this->portugues = $portugues;
    $this->ingles = $ingles;
    $this->espanol = $espanol;
    $this->active = $active;

    $this->db->update('translation', $this, array('id' => $id));
  }

  function get_by_id($id) {
    $this->db->where('id', $id);
    $query = $this->db->get('translation');

    return $query->row();
  }

  function get_all($offset = 0, $rows = 0) {	
    $this->db->limit($offset, $rows);	
    $this->db->select('*')->from('translation');
    //$this->db->order_by('<field>', '<type>'); ==> asc/desc
    $query = $this->db->get();

    return $query->result();
  }
}

?>

