<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @category CodeIgniter-Model: credit_card_status_Model
 * 
 * @access public
 *
 * @todo <description>
 * 
 */
class Credit_card_status_model extends CI_Model {
	function construct() {
		parent::construct();
	}

	function save ($name) {
		$this->name = $name;
		$this->db->insert('credit_card_status', $this);

		return $this->db->insert_id();
	}

	function remove ($id) {
		$this->db->delete('credit_card_status', array('id' => $id));
	}

	function update ($id, $name){
		$this->name = $name;
		$this->db->update('credit_card_status', $this, array('id' => $id));
	}

	function get_by_id ($id) {
		$this->db->where('id', $id);
		$query = $this->db->get('credit_card_status');

		return $query->row();
	}

  function get_all($offset = 0, $rows = 0){	
    $this->db->limit($offset, $rows);		
		$this->db->select('*')->from('credit_card_status');
		//$this->db->order_by('<field>', '<type>'); ==> asc/desc
		$query = $this->db->get();

		return $query->result();
	}
}

?>

