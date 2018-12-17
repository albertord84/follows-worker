<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @category CodeIgniter-Model: daily_work_Model
 * 
 * @access public
 *
 * @todo <description>
 * 
 */
class Daily_work_model extends CI_Model {
	function construct() {
		parent::construct();
	}

	function save ($to_follow, $to_unfollow, $cookies) {
		$this->to_follow = $to_follow;
    $this->to_unfollow = $to_unfollow;
    $this->cookies = $cookies;
		$this->db->insert('daily_work', $this);

		return $this->db->insert_id();
	}

	function remove ($reference_id) {
		$this->db->delete('daily_work', array('reference_id' => $reference_id));
	}

	function update ($reference_id, $to_follow, $to_unfollow, $cookies){
		$this->to_follow = $to_follow;
    $this->to_unfollow = $to_unfollow;
    $this->cookies = $cookies;

		$this->db->update('daily_work', $this, array('reference_id' => $reference_id));
	}

	function get_by_id ($reference_id) {
		$this->db->where('reference_id', $reference_id);
		$query = $this->db->get('daily_work');

		return $query->row();
	}

  function get_all($offset = 0, $rows = 0){	
    $this->db->limit($offset, $rows);		
		$this->db->select('*')->from('daily_work');
		//$this->db->order_by('<field>', '<type>'); ==> asc/desc
		$query = $this->db->get();

		return $query->result();
	}
}

?>

