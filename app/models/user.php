<?php 

class User extends CI_Model {

	public function __construct(){        
		parent::__construct();
		$this->load->database();
	}

	public function is_admin($id){
		$query = $this->db->query("select role from users where id = $id");

		$res = $query->result();

		return $res[0]->role;
	}

	public function exists($fb_id){
		$query = $this->db->query("select id from users where fb_id like '" . $fb_id . "'");

		$res = $query->result();

		if(count($res)>0)
			return $res[0]->id;
		else
			return 0;
	}

	public function add($fb_id, $email){
		if( $this->db->insert('users', array('fb_id' => $fb_id, 'email'=> $email)))
			return $this->db->insert_id();

		return 0;
	}

}




?>