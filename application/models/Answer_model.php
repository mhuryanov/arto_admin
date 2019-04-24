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

    function is_exist($data) {
        $this->db->where('story_id', $data['story_id']);
        $this->db->where('question_id', $data['question_id']);
        $this->db->where('answer_id', $data['answer_id']);

        $query = $this->db->get($this->table_name);
        $result = $query->result_array();

        if(count($result) > 0) {
            return true;
        }
        return false;
    }

    function update($data) {
        $this->db->where('story_id', $data['story_id']);
        $this->db->where('question_id', $data['question_id']);
        $this->db->where('answer_id', $data['answer_id']);

        $this->db->update($this->table_name, $data);
        return TRUE;
    }

}