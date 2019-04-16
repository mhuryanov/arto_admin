<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Answer_model extends CI_Model
{
    public $table_name = 'answers';
    
    function addNew($data) {
        $this->db->trans_start();
        $this->db->insert($this->table_name, $data);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
}