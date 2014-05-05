<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct(){        

    }


    public function index(){
        $this->load->view('home/index');
    }
    
    public function map(){
        $this->load->view('home/map');
    }
    
    public function fb_callback(){
        
    }
}

