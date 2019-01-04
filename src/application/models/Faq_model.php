<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @category CodeIgniter-Model: faq_Model
 * 
 * @access public
 *
 * @todo <description>
 * 
 */
class Faq_model extends CI_Model {
	function construct() {
		parent::construct();
	}

	function save ($token,$pregunta_pt,$pregunta_en,$pregunta_es,$respuesta_pt,$respuesta_en,$respuesta_es) {
		$this->token = $token;
        $this->pregunta_pt = $pregunta_pt;
        $this->pregunta_en = $pregunta_en;
        $this->pregunta_es = $pregunta_es;
        $this->respuesta_pt = $respuesta_pt;
        $this->respuesta_en = $respuesta_en;
        $this->respuesta_es = $respuesta_es;
		$this->db->insert('faq', $this);

		return $this->db->insert_id();
	}

	function remove ($id) {
		$this->db->delete('faq', array('id' => $id));
	}

	function update ($id, $token,$pregunta_pt,$pregunta_en,$pregunta_es,$respuesta_pt,$respuesta_en,$respuesta_es){
		$this->token = $token;
        $this->pregunta_pt = $pregunta_pt;
        $this->pregunta_en = $pregunta_en;
        $this->pregunta_es = $pregunta_es;
        $this->respuesta_pt = $respuesta_pt;
        $this->respuesta_en = $respuesta_en;
        $this->respuesta_es = $respuesta_es;

		$this->db->update('faq', $this, array('id' => $id));
	}

	function get_by_id ($id) {
		$this->db->where('id', $id);
		$query = $this->db->get('faq');

		return $query->row();
	}

  function get_all($offset = 0, $rows = 0){	
    $this->db->limit($offset, $rows);		
		$this->db->select('*')->from('faq');
		//$this->db->order_by('<field>', '<type>'); ==> asc/desc
		$query = $this->db->get();
		
		return $query->result();
	}
}

?>

