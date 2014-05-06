<?php 

class User extends CI_Model {

    public function __construct(){        
        parent::__construct();
        $this->load->database();
    }

    public function is_admin($id){
    	$query = $this->db->query("select role from users where id = $id");
        
       	$res = $query->result();
        
       	return $res[0][0];
    }

}




?>