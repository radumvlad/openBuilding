<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct(){        
        parent::__construct();
        
    }


    public function index(){
        $this->load->view('shared/_head');
        $this->load->view('shared/_header');
        $this->load->view('home/index');
        $this->load->view('shared/_footer');
    }
    
    public function map($id = 0){
        $this->load->view('shared/_head');
        $this->load->view('shared/_header');
        $this->load->view('home/map');
        $this->load->view('shared/_footer');
       
    }
    
    public function fb_callback(){
        
    }
}

