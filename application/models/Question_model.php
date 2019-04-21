<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Question_model extends CI_Model
{
    public $table_name = 'questions';
    
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

    function getByStory($storyId) {
        $this->db->where('story_id', $storyId);
        $this->db->where('q_parent', "0");
        $this->db->from($this->table_name);
        $query = $this->db->get();
        
        $result =  $query->result_array();
        
        if(count($result) > 0 ) {
            return $result;
        }  else {
            return null;
        }
    }

    function getQuestionInfo($id) {
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

    function edit($id,$story_id, $data) {
        $this->db->where('id', $id);
        $this->db->where('story_id', $story_id);
        $this->db->update($this->table_name, $data);
        
        return TRUE;
    }

    function delete($id) {
        $this->db->where("id", $id);
        $this->db->delete($this->table_name);
    }

    function getSubQuestions($storyId, $qid) {
        $this->db->where('story_id', $storyId);
        $this->db->where('q_parent', $qid);
        $this->db->from($this->table_name);
        $query = $this->db->get();
        
        $result =  $query->result_array();
        
        if(count($result) > 0 ) {
            return $result;
        }  else {
            return null;
        }
    }
}