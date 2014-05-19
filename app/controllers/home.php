<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
    public $user_id;

    public function __construct(){        
        parent::__construct();
        
        $this->load->library('info');
        $this->user_id = $this->info->user_id;
    }


    public function index(){
        $this->load->view('shared/_head', array('title' => 'OpenBuilding'));
        $this->load->view('shared/_header', array('search' => true,'actions' => '<button style="display:none" id="add_b" type="button" onclick="add_marker();" class="btn btn-info btn-sm navbar-btn pull-right">Add a building</button>'));
        $this->load->view('home/index');
        $this->load->view('shared/_footer');
    }
    
    public function map($id = 0){
        $this->load->view('shared/_head', array('title' => 'jlm'));
        $this->load->view('shared/_header', array('search' => true));
        $this->load->view('home/map');
        $this->load->view('shared/_footer');
       
    }
    
    public function profile($id = 0){

        if($id == 0)
            $id = $this->user_id;

        $this->load->model('User');

        if(!$this->User->exist($id))
            die();
        $res = $this->User->get_user_info($id);
        
        $this->load->view('shared/_head', array('title' => $res['info']->name));
        $this->load->view('shared/_header');
        $this->load->view('home/profile', $res);
        $this->load->view('shared/_footer');
    }

    public function administrate(){
        
        $this->load->model('Building');
        $this->load->model('User');

        if($this->User->is_admin($this->user_id))
            $res = $this->Building->get_to_administrate_admin();
        else
            $res = $this->Building->get_to_administrate($this->user_id);


        
        $this->load->view('shared/_head', array('title' => 'Administrate' ));
        $this->load->view('shared/_header');
        $this->load->view('home/administrate',$res);
        $this->load->view('shared/_footer');
    }

}

