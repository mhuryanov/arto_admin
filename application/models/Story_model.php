<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Story_model extends CI_Model
{
    public $table_name = 'stories';
    
    function getAll() {
        $query = $this->db->get($this->table_name);
        
        $result = $query->result_array();        
        return $result;
    }

    function addNew($data) {
        $this->db->trans_start();
        $this->db->insert($this->table_name, $data);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

    function getStoryInfo($id) {
        $this->db->where('id', $id);
        $this->db->from($this->table_name);
        $query = $this->db->get();
        
        $result =  $query->result_array();
        
        if(count($result) > 0 ) {
            return $result[0];
        }  else {
            return null;
        }
    }

    function edit($id, $data) {
        $this->db->where('id', $id);
        $this->db->update($this->table_name, $data);
        
        return TRUE;
    }

    function delete($id) {
        $this->db->where("id", $id);
        $this->db->delete($this->table_name);
    }
}