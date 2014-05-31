<?php 

class Test2 extends CI_Model {

	public function __construct(){        
		parent::__construct();
		$this->load->database();
	}

	public function tr(){
		return '1';
	}
}

?>