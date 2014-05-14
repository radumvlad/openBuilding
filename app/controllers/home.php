<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct(){        
        parent::__construct();
        
        $this->load->library('info');
        $user_id = $this->info->user_id;
    }


    public function index(){
        $this->load->view('shared/_head', array('title' => 'OpenBuilding'));
        $this->load->view('shared/_header', array('search' => true,'actions' => '<button style="display:none" id="add_b" type="button" onclick="add_marker();" class="btn btn-info btn-sm navbar-btn pull-right">Add a building</button>'));
        $this->load->view('home/index');
        $this->load->view('shared/_footer');
    }
    
    public function map($id = 0){
        $this->load->view('shared/_head', array('title' => 'OpenBuilding'));
        $this->load->view('shared/_header');
        $this->load->view('home/map');
        $this->load->view('shared/_footer');
       
    }
    
    public function admin(){
        $this->load->view('shared/_head', array('title' => 'Administrate'));
        $this->load->view('shared/_header', array('search' => true));
        $this->load->view('home/admin');
        $this->load->view('shared/_footer');
    }

}

