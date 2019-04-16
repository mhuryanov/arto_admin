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
            
            $questions = $this->question_model->getByStory($storyId);
            
            $this->global['pageTitle'] = 'Moneo : Questions';
            
            $data['story'] = $story;

            $data['questions'] = $questions;
            
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
}