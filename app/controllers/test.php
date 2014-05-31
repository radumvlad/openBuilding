<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {

    public function __construct(){        
        parent::__construct();
    }

    public function index(){
        die("test");
    }


    public function db(){
    	
    	$this->load->model("Test2");
    	echo $this->Test2->tr();
    	echo 'xx';
    }
}