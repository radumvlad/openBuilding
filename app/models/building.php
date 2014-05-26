<?php

class Building extends CI_Model {

    public function __construct(){  
        parent::__construct();
        $this->load->database();
    }


    function is_creator($user_id, $building_id){
        $query = $this->db->query("select * from buildings where id = $building_id and user_id = $user_id");
        $res = $query->result();
        
        if(count($res)>0)
            return 1;
        return 0;

    }

    function get_to_administrate_admin(){
        $query = $this->db->query("select b.id, b.name, f.floor_json, f.floor_number, s.start_time, u.name as username
            from buildings b 
            join sessions s on (b.id = s.building_id) 
            join users u on (s.user_id = u.id)
            left join floors f on (b.id = f.building_id)
            where s.active = 1");
        
        $temp1 = $query->result();

        $query = $this->db->query("select b.id, f.floor_json, f.floor_number
            from buildings b 
            join sessions s on (b.id = s.building_id) 
            left join session_floors f on (s.id = f.session_id)
            where s.active = 1");
        
        $temp2 = $query->result();

        $res['arr'] = [];

        foreach($temp1 as $var){
            $res['arr'][$var->id]['initial'][$var->floor_number] = $var->floor_json;
            $res['arr'][$var->id]['name'] = $var->name;
            $res['arr'][$var->id]['date'] = $var->start_time;
            $res['arr'][$var->id]['user'] = $var->username;
        }

        foreach($temp2 as $var){
            $res['arr'][$var->id]['after'][$var->floor_number] = $var->floor_json;
        }

        return $res;

    }

    function get_to_administrate($id){
        $query = $this->db->query("select b.id, b.name, f.floor_json, f.floor_number, s.start_time, u.name as username
            from buildings b 
            join sessions s on (b.id = s.building_id) 
            join users u on (s.user_id = u.id)
            left join floors f on (b.id = f.building_id)
            where s.active = 1 and b.user_id = ".$id);
        
        $temp1 = $query->result();

        $query = $this->db->query("select b.id, f.floor_json, f.floor_number
            from buildings b 
            join sessions s on (b.id = s.building_id) 
            left join session_floors f on (s.id = f.session_id)
            where s.active = 1 and b.user_id = ".$id);
        
        $temp2 = $query->result();

        $res['arr'] = [];

        foreach($temp1 as $var){
            $res['arr'][$var->id]['initial'][$var->floor_number] = $var->floor_json;
            $res['arr'][$var->id]['name'] = $var->name;
            $res['arr'][$var->id]['date'] = $var->start_time;
            $res['arr'][$var->id]['user'] = $var->username;
        }

        foreach($temp2 as $var){
            $res['arr'][$var->id]['after'][$var->floor_number] = $var->floor_json;
        }

        return $res;
    }

    function get_buildings($search, $latitude, $longitude){
        $query = $this->db->query("select b.*, u.name as owner from buildings b
            join users u on b.user_id = u.id
            where b.name like '%$search%'");
        
        return $query->result();
    }

    function insert_building($name, $lat, $long, $user_id){

        $now = new DateTime();
        $now->setTimezone(new DateTimeZone('Europe/Bucharest'));
        if( $this->db->insert('buildings', array('name' => $name, 'latitude'=> $lat, 'longitude'=>$long, 'user_id' => $user_id, 'created_date' => $now->format('Y-m-d H:i:s') )))
            return $this->db->insert_id();
        
        return 0;
    }

    function update_building($id, $name, $lat, $long){
        return $this->db->update('buildings', array('name' => $name, 'latitude'=> $lat, 'longitude'=>$long), array('id' => $id)); 
    }

    function delete_building($id){
        return $this->db->delete('buildings', array('id' => $id)); 
    }

    function get_building_info($id){
        $query = $this->db->query("select b.id, b.name, f.floor_json, f.floor_number from buildings b
                                    left join floors f on (b.id = f.building_id and f.floor_number = b.initial_floor_number)
                                    where b.id = " . $id);

        return $query->row();
    }

    function get_floor($building_id, $floor_nr){

        $query = $this->db->query("select * from floors 
            where building_id = ".$building_id."
            and floor_number = ".$floor_nr);

        $res = $query->row();
        if(count($res) > 0)
            return $res->floor_json;
        else
            return '[]';
    }

    function get_floor_edit($session_id, $building_id, $floor_nr){
        $query = $this->db->query("select * from session_floors 
            where building_id = ".$building_id."
            and floor_number = ".$floor_nr."
            and session_id = ".$session_id);

        $res = $query->row();
        if(count($res) > 0)
            return $res->floor_json;
        else
            return '[]';
    }


    function exists_floor($building_id, $nr){
        $query = $this->db->get_where('floors', array('building_id' => $building_id, 'floor_number' => $nr));
        $temp = $query->result();
        
        if(count($temp)>0){
            $res = $temp[0];
            return $res->id;
        }
        return 0;
    }

    function insert_floor($building_id, $nr , $json){
        return $this->db->insert('floors', array('building_id' => $building_id, 'floor_json'=> $json, 'floor_number'=> $nr));
    }
    
    function update_floor($id, $json){
        return $this->db->update('floors', array('floor_json'=>$json), array('id' => $id)); 
    }

    function exists_sfloor($session_id, $building_id, $nr){
        $query = $this->db->get_where('session_floors', array('session_id' => $session_id, 'building_id' => $building_id, 'floor_number' => $nr));
        $res = $query->row();
        
        if(count($res)>0){
            return $res->id;
        }
        return 0;
    }

    function insert_sfloor($session_id, $building_id, $nr , $json){
        return $this->db->insert('session_floors', array('session_id' => $session_id,'building_id' => $building_id, 'floor_json'=> $json, 'floor_number'=> $nr));
    }
    
    function update_sfloor($id, $json){
        return $this->db->update('session_floors', array('floor_json'=>$json), array('id' => $id)); 
    }

    function start_session($user_id, $building_id){
        return $this->db->insert('sessions', array('building_id' => $building_id, 'user_id'=> $user_id));
    }

    function copy_from_session($building_id){
        $query = $this->db->get_where('sessions', array('building_id' => $building_id, 'active' => 1)); 
        $row = $query->row();
        $session_id = $row->id;

        $query = $this->db->get_where('session_floors', array('session_id' => $session_id)); 

        foreach ($query->result() as $row) {
            $id = $this->exists_floor($building_id, $row->floor_number);

            if($id != 0)
                $this->update_floor($id, $row->floor_json);
            else
                $this->insert_floor($building_id, $row->floor_number, $row->floor_json);
        }
    }

    function delete_from_session($building_id){
        $query = $this->db->get_where('sessions', array('building_id' => $building_id, 'active' => 1)); 
        $row = $query->row();
        $session_id = $row->id;

        $this->db->delete('session_floors', array('session_id' => $session_id)); 
        $this->db->delete('sessions', array('id' => $session_id)); 
        return true;
    }
    
    function end_session($building_id){
        $now = new DateTime();
        $now->setTimezone(new DateTimeZone('Europe/Bucharest'));
        return $this->db->update('sessions', array('end_time' => $now->format('Y-m-d H:i:s'), 'active' => 0), array('building_id' => $building_id, 'active' => 1)); 
    }

    function check_building_id($building_id){
        $query = $this->db->get_where('buildings', array('id' => $building_id)); 
        $res = $query->result();
        if(count($res)>0)
            return true;
        return false;
    }


    function get_session($user_id, $building_id){
        $query = $this->db->get_where('sessions', array('active' => 1, 'building_id'=> $building_id, 'user_id'=> $user_id)); 
        $res = $query->row();
        if(count($res)>0)
            return $res->id;
        return 0;
    }

    function is_editable($building_id, $user_id){
        $query = $this->db->query('select * from sessions 
                                    where building_id = ' . $building_id . ' 
                                    and active = 1 
                                    and user_id != ' . $user_id);
        

        $res = $query->result();
        if(count($res) > 0)
            return false;
        return true;        
    }

    function is_editing($building_id, $user_id){
        $query = $this->db->query('select * from sessions 
                                    where building_id = ' . $building_id . ' 
                                    and active = 1 
                                    and user_id = ' . $user_id);
        

        $res = $query->result();
        if(count($res) > 0)
            return true;
        return false;        
    }
}











?>