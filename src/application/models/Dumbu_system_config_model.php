<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @category CodeIgniter-Model: dumbu_system_config_Model
 * 
 * @access public
 *
 * @todo <description>
 * 
 */
class Dumbu_system_config_model extends CI_Model {
	function construct() {
		parent::construct();
	}

	function save ($value, $description) {
		$this->value = $value;
        $this->description = $description;
		$this->db->insert('dumbu_system_config', $this);

		return $this->db->insert_id();
	}

	function remove ($name) {
		$this->db->delete('dumbu_system_config', array('name' => $name));
	}

	function update ($name, $value, $description){
		$this->value = $value;
        $this->description = $description;

		$this->db->update('dumbu_system_config', $this, array('name' => $name));
	}

	function get_by_id ($name) {
		$this->db->where('name', $name);
		$query = $this->db->get('dumbu_system_config');

		return $query->row();
	}

  function get_all($offset = 0, $rows = 0){	
    $this->db->limit($offset, $rows);		
		$this->db->select('*')->from('dumbu_system_config');
		//$this->db->order_by('<field>', '<type>'); ==> asc/desc
		$query = $this->db->get();

		return $query->result();
	}
}

?>

