<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Story extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('story_model');
        $this->isLoggedIn();   
    }

    public function getAll() {
        
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {   
            $stories = $this->story_model->getAll();     
            
            $this->global['pageTitle'] = 'Moneo : Stories';
            
            $data['stories'] = $stories;
            
            $this->loadViews("story/list", $this->global, $data, NULL);
        }
    }

    public function addNew() {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {       
            $this->global['pageTitle'] = 'Moneo : Add New Story';
            
            $this->loadViews("story/add", $this->global, NULL, NULL);
        }
    }

    public function addNewStoryP() {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('story_name','Full Name','trim|required|max_length[256]');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->addNew();
            }
            else
            {
                $name = ucwords(strtolower($this->security->xss_clean($this->input->post('story_name'))));
                
                $storyInfo = array('story_name'=>$name);
                
                $result = $this->story_model->addNew($storyInfo);
                
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New Story created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Story creation failed');
                }
                
                redirect('addNewStory');
            }
        }
    }

    public function editStory($storyId) {
        if($this->isAdmin() == TRUE )
        {
            $this->loadThis();
        }
        else
        {
            if($storyId == null)
            {
                redirect('stories');
            }
            
            $data['storyInfo'] = $this->story_model->getStoryInfo($storyId);

            if ($data['storyInfo'] == null) {
                redirect('stories');
            }
            
            $this->global['pageTitle'] = 'Moneo : Edit Story';
            
            $this->loadViews("story/edit", $this->global, $data, NULL);
        }
    }

    public function editStoryP()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('story_name','Full Name','trim|required|max_length[256]');

            $id = $this->input->post('id');
            
            if($this->form_validation->run() == FALSE)
            {
                redirect('editStory/'.$id);
            }
            else
            {
                $name = ucwords(strtolower($this->security->xss_clean($this->input->post('story_name'))));
                $color = $this->input->post('color');
                
                $storyInfo = array('story_name'=>$name, 'color'=>$color);
                
                $result = $this->story_model->edit($id, $storyInfo);
                
                if($result)
                {
                    $this->session->set_flashdata('success', 'Story updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Story update failed');
                }
                
                redirect('editStory/'.$id);
            }
        }
    }

    public function delete($id) {
         if($this->isAdmin() == TRUE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
           $this->story_model->delete($id);
           redirect('stories');
        }
    }

    public function order() {
        $order = $this->input->post('order');

        $i = 0;
        foreach ($order as $item) {
            $this->story_model->edit($item, array("s_order"=>$i));
            $i++;
        }
        echo json_encode($order);
    }
}