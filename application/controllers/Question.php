<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Question extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('story_model');
        $this->load->model('question_model');
        $this->isLoggedIn();   
    }

    public function getByStory($storyId) {
        
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {   
            $story = $this->story_model->getStoryInfo($storyId);     
            var_dump($story);
            
            $questions = $this->question_model->getByStory($storyId);
            
            $this->global['pageTitle'] = 'Moneo : Questions';
            
            $data['story'] = $story;

            if(count($questions) > 0) {
                for($i = 0; $i < count($questions); $i++) {
                     $temp_sub = $this->getSubQuestion($storyId, $questions[$i]['id']);
                    if($temp_sub != null && count($temp_sub) > 0) {
                        $questions[$i]['subs'] = $temp_sub;
                    } 
                }
            }

            $data['questions'] = $questions;
            
            // var_dump($questions);
            
            $this->loadViews("question/list", $this->global, $data, NULL);
        }
    }

    public function addNew($storyId) {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {       
            $story = $this->story_model->getStoryInfo($storyId);   
            
            $data['story'] = $story;

            $this->global['pageTitle'] = 'Moneo : Add New Question';
            
            $this->loadViews("question/add", $this->global, $data, NULL);
        }
    }

    public function addNewP() {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('question','Question','trim|required|max_length[1024]');

            $storyId = $this->input->post('story_id');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->addNew($storyId);
            }
            else
            {
                $question = ucwords(strtolower($this->security->xss_clean($this->input->post('question'))));
                
                $questionInfo = array('question'=>$question, "story_id" => $storyId);
                
                $result = $this->question_model->addNew($questionInfo);
                
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New Question created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Question creation failed');
                }
                
                redirect('stories/'.$storyId."/qlist");
            }
        }
    }

    public function editQuestion($storyId, $questionId) {
        if($this->isAdmin() == TRUE )
        {
            $this->loadThis();
        }
        else
        {
            if($storyId == null || $questionId == null)
            {
                redirect('stories/'.$storyId."/qlist");
            }
            
            $data['story'] = $this->story_model->getStoryInfo($storyId);
            $data['question'] = $this->question_model->getQuestionInfo($questionId);

            if ($data['story'] == null || $data['question'] == null) {
                redirect('stories/'.$storyId."/qlist");
            }
            
            $this->global['pageTitle'] = 'Moneo : Edit Question';
            
            $this->loadViews("question/edit", $this->global, $data, NULL);
        }
    }

    public function editQuestionP()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('question','Question','trim|required|max_length[1024]');

            $id = $this->input->post('id');

            $story_id = $this->input->post("story_id");
            
            if($this->form_validation->run() == FALSE)
            {
                redirect('editQuestion/'.$story_id. "/".$id);
            }
            else
            {
                $name = ucwords(strtolower($this->security->xss_clean($this->input->post('question'))));
                
                $storyInfo = array('question'=>$name);
                
                $result = $this->question_model->edit($id, $story_id, $storyInfo);
                
                if($result)
                {
                    $this->session->set_flashdata('success', 'Question updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Question update failed');
                }
                
                redirect('editQuestion/'.$story_id. "/".$id);
            }
        }
    }

    public function delete($id, $storyId) {
        if($this->isAdmin() == TRUE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
           $this->question_model->delete($id);
           redirect('stories/'.$storyId."/qlist");
        }
    }

    function addq_b() {
        $data['question'] = $this->input->post("q");
        $data['q_parent'] = $this->input->post("qid");
        $data['story_id'] = $this->input->post("story_id");

        $result = $this->question_model->addNew($data);
                
        if($result > 0)
        {
           echo json_encode(array("success"=> true));
           return;
        }
         echo json_encode(array("success"=> false));
        
    }

    function getSubQuestion($storyId, $qid) {
        $subs = $this->question_model->getSubQuestions($storyId, $qid);
        if($subs != null && count($subs) > 0) {
            for ($i = 0; $i < count($subs); $i++) {
                $temp_sub = $this->getSubQuestion($storyId, $subs[$i]['id']);
                if($temp_sub != null && count($temp_sub) > 0) {
                    $subs[$i]['subs'] = $temp_sub;
                } 
            }

            return $subs;
        } else {
            return null;
        }
    }
}