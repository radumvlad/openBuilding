<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Location extends CI_Controller {

    public $user_id;

    public function __construct(){        
        parent::__construct();
        
        $this->load->model("Building");
        $this->load->library('info');
        
        $this->user_id = $this->info->user_id;
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
              die('{"status":0, "msg":"No or wrong name input"}');
        if(!isset($_POST['latitude']) || !is_numeric($_POST['latitude']))
              die('{"status":0, "msg":"No or wrong latitude input"}');
        if(!isset($_POST['longitude']) || !is_numeric($_POST['longitude']))
              die('{"status":0, "msg":"No or wrong longitude input"}');

        $insert_id = $this->Building->insert_building($_POST['name'] , $_POST['latitude'], $_POST['longitude'], $this->user_id);
        if($insert_id == 0)
            die('{"status":0, "msg":"Database error"}');

        $username = $this->info->username;
        die('{"status":1, "msg":"", "building_id":' . $insert_id . ', "owner": "'.$username.'"}');
    }

    public function edit(){
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
        if(! $this->User->is_admin($this->user_id) && ! $this->Building->is_creator($this->user_id, $_POST['building_id']))
            die('{"status":0, "msg":"No permission"}');

        if(! $this->Building->update_building($_POST['building_id'], $_POST['name'] , $_POST['latitude'], $_POST['longitude']) == 1){
            die('{"status":0, "msg":"Database error"}');
        }

        die('{"status":1, "msg":""}');
    }

    public function delete(){
        if(!isset($_POST['building_id']) || !is_numeric($_POST['building_id']) || ! $this->Building->check_building_id($_POST['building_id']))
              die('{"status":0, "msg":"No or wrong id input"}');

        $this->load->model("User");
        if(! $this->User->is_admin($this->user_id) && ! $this->Building->is_creator($this->user_id, $_POST['building_id']))
            die('{"status":0, "msg":"No permission"}');

        if(! $this->Building->delete_building($_POST['building_id']) == 1){
            die('{"status":0, "msg":"Database error"}');
        }

        die('{"status":1, "msg":""}');
    }

    public function start_edit(){
        //validate input
        if(!isset($_POST['building_id']) || !is_numeric($_POST['building_id']) || ! $this->Building->check_building_id($_POST['building_id']))
              die('{"status":0, "msg":"No or wrong id input"}');

        if(! $this->Building->is_editable($_POST['building_id'], $this->user_id))
            die('{"status":0, "msg":"Not Available"}');

        if($this->Building->is_editing($_POST['building_id'], $this->user_id))
            die('{"status":1,"new":0, "msg":""}');

        $this->Building->start_session($this->user_id, $_POST['building_id']);


        die('{"status":1,"new":1, "msg":""}');
    }

    public function accept_edit(){
        //validate input
        if(!isset($_POST['building_id']) || !is_numeric($_POST['building_id']) || ! $this->Building->check_building_id($_POST['building_id']))
              die('{"status":0, "msg":"No or wrong id input"}');


        $this->load->model("User");
        if(! $this->User->is_admin($this->user_id) && ! $this->Building->is_creator($this->user_id, $_POST['building_id']))
            die('{"status":0, "msg":"No permission"}');


        $this->Building->copy_from_session($_POST['building_id']);
        $this->Building->end_session($_POST['building_id']);

        die('{"status":1, "msg":""}');
    }

    public function decline_edit(){
        //validate input
        if(!isset($_POST['building_id']) || !is_numeric($_POST['building_id']) || ! $this->Building->check_building_id($_POST['building_id']))
              die('{"status":0, "msg":"No or wrong id input"}');

        $this->load->model("User");
        if(! $this->User->is_admin($this->user_id) && ! $this->Building->is_creator($this->user_id, $_POST['building_id']))
            die('{"status":0, "msg":"No permission"}');

        //delete from session
        $this->Building->delete_from_session($_POST['building_id']);
        
        die('{"status":1, "msg":""}');
    }

    public function save_floor(){
        //todo

        if(!isset($_POST['building_id']) || !is_numeric($_POST['building_id']) || ! $this->Building->check_building_id($_POST['building_id']))
              die('{"status":0, "msg":"No or wrong id input"}');

        if(!isset($_POST['floor_json']) || ! $this->isJson($_POST['floor_json']) )
              die('{"status":0, "msg":"No or wrong id input"}');
        if(!isset($_POST['floor_number']) || !is_numeric($_POST['floor_number']))
              die('{"status":0, "msg":"No or wrong floor_number input"}');

        $sess = $this->Building->get_session($this->user_id, $_POST['building_id']);

        if($sess == 0)
            die('{"status":0, "msg":"No permission"}');


        $id = $this->Building->exists_sfloor($sess, $_POST['building_id'], $_POST['floor_number']);

        if($id != 0)
            $this->Building->update_sfloor($id, $_POST['floor_json']);
        else
            $this->Building->insert_sfloor($sess, $_POST['building_id'], $_POST['floor_number'] , $_POST['floor_json']);


        $this->load->model("User");
        if($this->User->is_admin($this->user_id) || $this->Building->is_creator($this->user_id, $_POST['building_id'])){
            $this->Building->copy_from_session($_POST['building_id']);
        }

        die('{"status":1, "msg":""}');
    }    

    public function end_session(){
        if(!isset($_POST['building_id']) || !is_numeric($_POST['building_id']) || ! $this->Building->check_building_id($_POST['building_id']))
              die('{"status":0, "msg":"No or wrong id input"}');

        $sess = $this->Building->get_session($this->user_id, $_POST['building_id']);

        if($sess == 0)
            die('{"status":0, "msg":"No permission"}');

        $this->load->model("User");
        if($this->User->is_admin($this->user_id) || $this->Building->is_creator($this->user_id, $_POST['building_id'])){
            $this->Building->end_session($_POST['building_id']);
        }
        
        die('{"status":1, "msg":""}');
    }

    public function has_edited_floor(){
        if(!isset($_POST['building_id']) || !is_numeric($_POST['building_id']) || ! $this->Building->check_building_id($_POST['building_id']))
              die('{"status":0, "msg":"No or wrong id input"}');

        if(!isset($_POST['floor_number']) || !is_numeric($_POST['floor_number']))
              die('{"status":0, "msg":"No or wrong floor_number input"}');

        $sess = $this->Building->get_session($this->user_id, $_POST['building_id']);

        if($sess == 0)
            die('{"status":0, "msg":"No permission"}');


        $id = $this->Building->exists_sfloor($sess, $_POST['building_id'], $_POST['floor_number']);

        if($id == 0)
            die('{"status":1, "has":0, "msg":""}');

        die('{"status":1, "has":1, "msg":""}');
    }


    public function get_floor(){
        $lid = isset($_POST['building_id'])?$_POST['building_id']:0;
        $nr = isset($_POST['number'])?$_POST['number']:0;

        if(! $this->Building->check_building_id($lid))
            die('{"status":0, "msg":"Wrong id input"}');

        
        die($this->Building->get_floor($lid, $nr));
    }

    public function get_floor_edit(){
        $lid = isset($_POST['building_id'])?$_POST['building_id']:0;
        $nr = isset($_POST['number'])?$_POST['number']:0;

        if(! $this->Building->check_building_id($lid))
            die('{"status":0, "msg":"Wrong id input"}');

        $sess = $this->Building->get_session($this->user_id, $lid);

        if($sess == 0)
            die('{"status":0, "msg":"No permission"}');

        die($this->Building->get_floor_edit($sess, $lid, $nr));
    }

}

