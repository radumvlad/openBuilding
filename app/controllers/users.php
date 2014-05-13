<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct(){        
        parent::__construct();
        
        $this->load->model("User");
        $this->load->library('info');
    }

    public function login(){

        if(!isset($_POST['email']) || $_POST['email'] == '')
            die('{"status":0, "msg":"No or wrong email input"}');
        if(!isset($_POST['name']) || $_POST['name'] == '')
            die('{"status":0, "msg":"No or wrong name input"}');
        if(!isset($_POST['fb_id']) || !is_numeric($_POST['fb_id']))
            die('{"status":0, "msg":"No or wrong fb_id input"}');

        $id = $this->User->exists($_POST['fb_id']);

        if($id == 0)
            $id = $this->User->add($_POST['fb_id'], $_POST['email'], $_POST['name']);
        
        if($id == 0)
            die('{"status":0, "msg":"Database error"}');

        $this->info->setData($id, $_POST['name']);

        die('{"status":1, "msg":"", "id":' . $id . '}');
    }

    public function logout(){
        $this->info->destroyData();
        die('{"status":1, "msg":""}');
    }

    

}

