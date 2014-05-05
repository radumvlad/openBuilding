<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Location extends CI_Controller {

    public function __construct(){        
        parent::__construct();
        $this->load->model("Building");
    }

    private function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    public function get(){
        //validate input
        $search = isset($_POST['search'])?$_POST['search']:'';
        $latitude = isset($_POST['latitude'])?$_POST['latitude']:0;
        $longitude = isset($_POST['longitude'])?$_POST['longitude']:0;

        $res = $this->Building->get_buildings($search, $latitude, $longitude);

        die(json_encode($res));
    }

	public function add(){
        //validate input
        if(!isset($_POST['name']) || $_POST['name'] == '')
              die('{"status":0, "msg":"No name input"}');
        if(!isset($_POST['latitude']) || !is_numeric($_POST['latitude']))
              die('{"status":0, "msg":"No latitude input"}');
        if(!isset($_POST['longitude']) || !is_numeric($_POST['longitude']))
              die('{"status":0, "msg":"No longitude input"}');

        if(! $this->Building->insert_building($_POST['name'] , $_POST['latitude'], $_POST['longitude'], 1) == 1)
            die('{"status":0, "msg":"Database error"}');

        die('{"status":1, "msg":""}');
    }

    public function edit(){
        //validate input
        if(!isset($_POST['name']) || $_POST['name'] == '')
              die('{"status":0, "msg":"No name input"}');
        if(!isset($_POST['latitude']) || !is_numeric($_POST['latitude']))
              die('{"status":0, "msg":"No latitude input"}');
        if(!isset($_POST['longitude']) || !is_numeric($_POST['longitude']))
              die('{"status":0, "msg":"No longitude input"}');
        if(!isset($_POST['building_id']) || !is_numeric($_POST['building_id']))
              die('{"status":0, "msg":"No id input"}');

        //check input
        if(! $this->Building->check_building_id($_POST['building_id']))
            die('{"status":0, "msg":"Wrong id input"}');

        //permissions?

        if(! $this->Building->update_building($_POST['id'], $_POST['name'] , $_POST['latitude'], $_POST['longitude']) == 1){
            die('{"status":0, "msg":"Database error"}');
        }

        $this->Building->end_session(1, $_POST['building_id']);

        die('{"status":1, "msg":""}');
    }

    public function start_blocking(){
        //validate input
        if(!isset($_POST['building_id']) || !is_numeric($_POST['building_id']) || ! $this->Building->check_building_id($_POST['building_id']))
              die('{"status":0, "msg":"No or wrong id input"}');

        if(! $this->Building->check_building_available($_POST['building_id']))
            die('{"status":0, "msg":"Not Available"}');

        $this->Building->start_session(1, $_POST['building_id']);


        die('{"status":1, "msg":""}');
    }

    public function get_floor(){
        //validate input
        $lid = isset($_POST['building_id'])?$_POST['building_id']:0;
        $nr = isset($_POST['number'])?$_POST['nr']:0;

        die(json_encode($this->Building->get_floor($lid, $nr)));
    }

    public function add_floor(){
        //validate input
        if(!isset($_POST['building_id']) || !is_numeric($_POST['building_id']))
              die('{"status":0, "msg":"No building_id input"}');
        if(!isset($_POST['floor_number']) || !is_numeric($_POST['floor_number']))
              die('{"status":0, "msg":"No floor_number input"}');
        if(!isset($_POST['json']) || $_POST['json'] == '' || ! $this->isJson($_POST['json']))
              die('{"status":0, "msg":"No json input"}');

        //check input
        if(!$this->Building->check_building_id($_POST['building_id']))
            die('{"status":0, "msg":"Wrong building_id input"}');

        if(!$this->Building->check_session(1, $_POST['building_id']))
            die('{"status":0, "msg":"Wrong building_id input"}');

        //other permissions?


        if(! $this->Building->insert_floor($_POST['building_id'], $_POST['floor_number'], $_POST['json']) == 1)
            die('{"status":0, "msg":"Database error"}');
        
        die('{"status":1, "msg":""}');
    }

    public function edit_floor(){
        //validate input
        if(!isset($_POST['floor_id']) || !is_numeric($_POST['floor_id']))
              die('{"status":0, "msg":"No building_id input"}');
        if(!isset($_POST['floor_number']) || !is_numeric($_POST['floor_number']))
              die('{"status":0, "msg":"No floor_number input"}');
        if(!isset($_POST['json']) || $_POST['json'] == '' || ! $this->isJson($_POST['json']))
              die('{"status":0, "msg":"No json input"}');

        //check input
        if(!$this->Building->check_building_id($_POST['building_id']))
            die('{"status":0, "msg":"Wrong building_id input"}');

        if(!$this->Building->check_session(1, $_POST['building_id']))
            die('{"status":0, "msg":"Wrong building_id input"}');

        //other permissions?


        if(! $this->Building->update_floor(1, '{a}') == 1)
            die('{"status":0, "msg":"Database error"}');

        die('{"status":1, "msg":""}');
    }
    
    public function test(){
        echo $this->Building->test();
        
    }

}

