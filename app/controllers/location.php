<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Location extends CI_Controller {

    public function __construct(){        
        parent::__construct();
        $this->load->model("Building");

        $user_id = 1;
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

        $user_id = 1;//testing purposes

        //validate input
        if(!isset($_POST['name']) || $_POST['name'] == '')
              die('{"status":0, "msg":"No or wrong name input"}');
        if(!isset($_POST['latitude']) || !is_numeric($_POST['latitude']))
              die('{"status":0, "msg":"No or wrong latitude input"}');
        if(!isset($_POST['longitude']) || !is_numeric($_POST['longitude']))
              die('{"status":0, "msg":"No or wrong longitude input"}');

        $insert_id = $this->Building->insert_building($_POST['name'] , $_POST['latitude'], $_POST['longitude'], $user_id);
        if($insert_id == 0)
            die('{"status":0, "msg":"Database error"}');

        die('{"status":1, "msg":"", "building_id":' . $insert_id . '}');
    }

    public function edit(){

        $user_id = 1;//testing purposes

        //validate input
        if(!isset($_POST['name']) || $_POST['name'] == '')
              die('{"status":0, "msg":"No or wrong name input"}');
        if(!isset($_POST['latitude']) || !is_numeric($_POST['latitude']))
              die('{"status":0, "msg":"No or wrong latitude input"}');
        if(!isset($_POST['longitude']) || !is_numeric($_POST['longitude']))
              die('{"status":0, "msg":"No or wrong longitude input"}');
        if(!isset($_POST['building_id']) || !is_numeric($_POST['building_id']))
              die('{"status":0, "msg":"No or wrong id input"}');

        //check input
        if(! $this->Building->check_building_id($_POST['building_id']))
            die('{"status":0, "msg":"Wrong id input"}');

        $this->load->model("User");
        if(!is_admin($user_id) && !is_creator($user_id, $building_id))
            die('{"status":0, "msg":"No permission"}');

        if(! $this->Building->update_building($_POST['id'], $_POST['name'] , $_POST['latitude'], $_POST['longitude']) == 1){
            die('{"status":0, "msg":"Database error"}');
        }

        die('{"status":1, "msg":""}');
    }

    public function delete(){

        $user_id = 1;//testing purposes

        if(!isset($_POST['building_id']) || !is_numeric($_POST['building_id']))
            die('{"status":0, "msg":"No or wrong id input"}');

        //check input
        if(! $this->Building->check_building_id($_POST['building_id']))
            die('{"status":0, "msg":"Wrong id input"}');

        $this->load->model("User");
        if(!is_admin($user_id) && !is_creator($user_id, $building_id))
            die('{"status":0, "msg":"No permission"}');

        if(! $this->Building->delete_building($_POST['id']) == 1){
            die('{"status":0, "msg":"Database error"}');
        }

        die('{"status":1, "msg":""}');
    }

    public function start_edit(){
        //validate input
        if(!isset($_POST['building_id']) || !is_numeric($_POST['building_id']) || ! $this->Building->check_building_id($_POST['building_id']))
              die('{"status":0, "msg":"No or wrong id input"}');

        if(! $this->Building->check_building_available($_POST['building_id']))
            die('{"status":0, "msg":"Not Available"}');

        $this->Building->start_session($user_id, $_POST['building_id']);


        die('{"status":1, "msg":""}');
    }

    public function end_edit(){


       

        //copy from session


        $this->Building->end_session($user_id, $_POST['building_id']);

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
              die('{"status":0, "msg":"No or wrong building_id input"}');
        if(!isset($_POST['floor_number']) || !is_numeric($_POST['floor_number']))
              die('{"status":0, "msg":"No or wrong floor_number input"}');
        

        //check input
        if(!$this->Building->check_building_id($_POST['building_id']))
            die('{"status":0, "msg":"Wrong building_id input"}');

        if(!$this->Building->check_session($user_id, $_POST['building_id']))
            die('{"status":0, "msg":"Wrong building_id input"}');

        //todo:check floor number
        if(!$this->Building->check_floor_nr($_POST['building_id'], $_POST['floor_number']))
            die('{"status":0, "msg":"floor_nr already exist"}');

        //other permissions?

        if(! $this->Building->insert_floor($_POST['building_id'], $_POST['floor_number'], '{}') == 1)
            die('{"status":0, "msg":"Database error"}');
        
        die('{"status":1, "msg":""}');
    }

    public function edit_floor(){
        //validate input
        if(!isset($_POST['floor_id']) || !is_numeric($_POST['floor_id']))
              die('{"status":0, "msg":"No or wrong building_id input"}');
        if(!isset($_POST['floor_number']) || !is_numeric($_POST['floor_number']))
              die('{"status":0, "msg":"No or wrong floor_number input"}');
        if(!isset($_POST['json']) || $_POST['json'] == '' || ! $this->isJson($_POST['json']))
              die('{"status":0, "msg":"No or wrong json input"}');

        //check input
        if(!$this->Building->check_building_id($_POST['building_id']))
            die('{"status":0, "msg":"Wrong building_id input"}');

        if(!$this->Building->check_session(1, $_POST['building_id']))
            die('{"status":0, "msg":"U sure u can do that?"}');

        //other permissions?


        if(! $this->Building->update_floor(1, '{a}') == 1)
            die('{"status":0, "msg":"Database error"}');

        die('{"status":1, "msg":""}');
    }
    
    public function test(){
        echo $this->Building->test();
        
    }

}

