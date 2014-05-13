<?php 

class info 
{
	public $is_logged, $user_id, $username;

	public function __construct(){
		if (!session_id()) {
			session_start();
		}

		if(isset($_SESSION['user_id'])){
			$this->user_id = $_SESSION['user_id'];
			$this->username = $_SESSION['username'];
			$this->is_logged = true;
		}
		else
		{
			$this->user_id = 0;
			$this->is_logged = false;
		}
	}

	public function setData($id,$name){
		if (!session_id()) {
			session_start();
		}

		$this->is_logged = true;
		$_SESSION['user_id'] = $id;
		$_SESSION['username'] = $name;
	}

	public function destroyData(){
		session_destroy();
		$this->user_id = 0;
		$this->is_logged = false;
	}

}

?>
