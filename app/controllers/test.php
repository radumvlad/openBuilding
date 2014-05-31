<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {

    public function __construct(){        
        parent::__construct();
    }

    public function index(){
        die("test");
    }


    public function db(){
    	$heroku_url = parse_url(getenv("CLEARDB_DATABASE_URL"));


    	echo $heroku_url["host"] . '<br>';
    	echo $heroku_url["user"] . '<br>';
    	echo $heroku_url["pass"] . '<br>';
    	echo substr($heroku_url["path"],1) . '<br>';
    }
}