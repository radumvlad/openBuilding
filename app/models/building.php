<?php

class Building extends CI_Model {
    
    public function __construct(){  
        parent::__construct();
        $this->load->database();
    }

    function is_creator($user_id, $building_id){
        $query = $this->db->query("select user_id from buildings where id = $building_id");
        $res = $query->result();
        
        if($res[0][0] == $user_id)
            return 1;
        return 0;

    }

    function get_buildings($search, $latitude, $longitude){
        
        $query = $this->db->query("select * from buildings 
                                    where name like '%$search%'");
        
        return $query->result();
    }

    function insert_building($name, $lat, $long, $user_id){
        if( $this->db->insert('buildings', array('name' => $name, 'latitude'=> $lat, 'longitude'=>$long, 'user_id'=> $user_id)))
            return $this->db->insert_id();
        
        return 0;
    }

    function update_building($id, $name, $lat, $long){
        return $this->db->update('buildings', array('name' => $name, 'latitude'=> $lat, 'longitude'=>$long), array('id' => $id)); 
    }

    function get_floor($building_id, $floor_nr){
        
        $query = $this->db->query("select * from floors 
                                    where building_id = $building_id
                                    and floor_number = $floor_nr");

        $res = $query->result();
        if(count($res) > 0)
            return $res[0];
        else
            return new stdClass();
    }

    function insert_floor($building_id, $nr , $json){
        return $this->db->insert('floors', array('building_id' => $building_id, 'floor_json'=> $json, 'floor_number'=> $nr));
    }
    
    function update_floor($id, $json){
        return $this->db->update('floors', array('floor_json'=>$json), array('id' => $id)); 
    }

    function start_session($user_id, $building_id){
        //todo: 
    }
    
    function end_session($user_id, $building_id){
        //todo:
    }

    function check_building_available($building_id){
        //todo
        return true;
    }

    function check_building_id($building_id){
        //todo
        return true;
    }

    function check_floor_nr($building_id, $floor_nr){
        //todo
        return true;
    }



    function check_session($user_id, $building_id){
        //todo
        return true;
    }

    function test(){
        return $this->db->update('floors', array('floor_number' => 1), array('id' => 1));
         
    }


}

    









?>