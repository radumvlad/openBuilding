<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
    public $user_id, $logged, $role;

    public function __construct(){        
        parent::__construct();
        
        $this->load->library('info');
        $this->user_id = $this->info->user_id;

        if($this->user_id == 0)
            $this->logged = -1;
        else
            $this->logged = 1;

        $this->load->model('User');
        $this->role = $this->User->is_admin($this->user_id);
    }


    public function index(){

        $left_nav = '<ul class="nav navbar-nav"><div class="navbar-form navbar-left" role="search"><div class="form-group"><input type="text" id="searchInput" style="width:500px" class="form-control input-sm" placeholder="Search"></div></div></ul>';

        $right_nav = '<button style="display:none" id="add_b" type="button" onclick="add_marker();" class="btn btn-info btn-sm navbar-btn pull-right">Add a building</button>';

        $this->load->view('shared/_head', array('title' => 'OpenBuilding', 'logged'=> $this->logged, 'user_id' => $this->user_id, 'role' => $this->role));
        $this->load->view('shared/_header', array('left_nav' => $left_nav, 'right_nav' => $right_nav));
        $this->load->view('home/index');
        $this->load->view('shared/_footer');
    }
    
    public function map($id = 0){
        $this->load->model("Building");

        if($id == 0 || !is_numeric($id) || ! $this->Building->check_building_id($id))
            show_404();

        $actions_html = "";

        if($this->Building->is_editable($id, $this->user_id) && $this->user_id)
            $actions_html = '<button id="edit_b" type="button" onclick="goToEdit();" class="btn btn-success btn-sm navbar-btn pull-right">Edit</button><button style="display:none" id="view_b" type="button" onclick="goToView();" class="btn btn-warning btn-sm navbar-btn pull-right">Back</button>';

        $left_nav = '<ul class="nav navbar-nav"><div class="navbar-form navbar-left" role="search"><div class="form-group"><input type="text" id="searchInput" style="width:500px" class="form-control input-sm" placeholder="Search"></div></div></ul>';

        $info = $this->Building->get_building_info($id);


        $this->load->view('shared/_head', array('title' => $info->name, 'logged'=> $this->logged, 'user_id' => $this->user_id, 'role' => $this->role));
        $this->load->view('shared/_header', array('left_nav' => $left_nav, 'right_nav'=> $actions_html));
        $this->load->view('home/map', array('info' => $info ));
        $this->load->view('shared/_footer');

    }

    public function profile($id = 0){

        if($id == 0)
            $id = $this->user_id;

        

        if(!$this->User->exist($id))
            show_404();
        
        $res = $this->User->get_user_info($id);

        $this->load->view('shared/_head', array('title' => $res['info']->name, 'logged'=> $this->logged, 'user_id' => $this->user_id, 'role' => $this->role));
        $this->load->view('shared/_header');
        $this->load->view('home/profile', $res);
        $this->load->view('shared/_footer');
    }

    public function administrate(){

        $this->load->model('Building');

        if($this->User->is_admin($this->user_id))
            $res = $this->Building->get_to_administrate_admin();
        else
            $res = $this->Building->get_to_administrate($this->user_id);



        $this->load->view('shared/_head', array('title' => 'Administrate', 'logged'=> $this->logged, 'user_id' => $this->user_id, 'role' => $this->role));
        $this->load->view('shared/_header');
        $this->load->view('home/administrate',$res);
        $this->load->view('shared/_footer');
    }

}

