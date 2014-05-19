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

	public function exist($id){
		$query = $this->db->query("select id from users where id = " . $id );

		$res = $query->result();

		if(count($res)>0)
			return true;
		else
			return false;
	}

	public function get_user_info($id){
		$query = $this->db->query("select id, name from users where id = " . $id . "");

		$temp = $query->result();

		$res['info'] = $temp[0];

		$query = $this->db->query("select b.id, b.name, f.floor_json 
			from buildings b join floors f on b.id = f.building_id 
			where b.user_id = " . $id . "
			and f.floor_number = (select min(f2.floor_number) from floors f2
				where f2.building_id = b.id 
				and f2.floor_number >= 0)");

		$temp = $query->result();
		$res['own'] =$temp;


		$query = $this->db->query("select b.id, b.name, f.floor_json 
			from buildings b 
			join floors f on (b.id = f.building_id) 
			join sessions s on (b.id = s.building_id and s.user_id = " . $id . ")
			where f.floor_number = (select min(f2.floor_number) from floors f2
				where f2.building_id = b.id 
				and f2.floor_number >= 0)
			and s.active = 0
			and b.id not in (select id from buildings where user_id = " . $id . ")");

		
		$temp = $query->result();
		$res['contributed'] =$temp;

		return $res;
	}

	public function add($fb_id, $email, $name){
		if( $this->db->insert('users', array('fb_id' => $fb_id, 'email' => $email, 'name' => $name)))
			return $this->db->insert_id();

		return 0;
	}

}




?>