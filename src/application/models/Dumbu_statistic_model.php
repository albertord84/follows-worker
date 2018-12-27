<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @category CodeIgniter-Model: dumbu_statistic_Model
 * 
 * @access public
 *
 * @todo <description>
 * 
 */
class Dumbu_statistic_model extends CI_Model {
	function construct() {
		parent::construct();
	}
	
	function save ($active, $blocked_by_payment, $blocked_by_insta, $deleted, $inactive, $pending, $unfollow, $beginner, $verify_account, $blocked_by_time, $dont_disturb, $paying_customers, $date) {
		$this->active = $active;
        $this->blocked_by_payment = $blocked_by_payment;
        $this->blocked_by_insta = $blocked_by_insta;
        $this->deleted = $deleted;
        $this->inactive = $inactive;
        $this->pending = $pending;
        $this->unfollow = $unfollow;
        $this->beginner = $beginner;
        $this->verify_account = $verify_account;
        $this->blocked_by_time = $blocked_by_time;
        $this->dont_disturb = $dont_disturb;
        $this->paying_customers = $paying_customers;
        $this->date = $date;
		$this->db->insert('dumbu_statistic', $this);

		return $this->db->insert_id();
	}

	function remove ($id) {
		$this->db->delete('dumbu_statistic', array('id' => $id));
	}

	function update ($id, $active, $blocked_by_payment, $blocked_by_insta, $deleted, $inactive, $pending, $unfollow, 
                   $beginner, $verify_account, $blocked_by_time, $dont_disturb, $paying_customers, $date){
		$this->active = $active;
        $this->blocked_by_payment = $blocked_by_payment;
        $this->blocked_by_insta = $blocked_by_insta;
        $this->deleted = $deleted;
        $this->inactive = $inactive;
        $this->pending = $pending;
        $this->unfollow = $unfollow;
        $this->beginner = $beginner;
        $this->verify_account = $verify_account;
        $this->blocked_by_time = $blocked_by_time;
        $this->dont_disturb = $dont_disturb;
        $this->paying_customers = $paying_customers;
        $this->date = $date;

		$this->db->update('dumbu_statistic', $this, array('id' => $id));
	}

	function get_by_id ($id) {
		$this->db->where('id', $id);
		$query = $this->db->get('dumbu_statistic');

		return $query->row();
	}

  function get_all($offset = 0, $rows = 0){	
    $this->db->limit($offset, $rows);		
		$this->db->select('*')->from('dumbu_statistic');
		//$this->db->order_by('<field>', '<type>'); ==> asc/desc
		$query = $this->db->get();

		return $query->result();
	}
}

?>

