<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Answer extends BaseController
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
        $this->load->model('answer_model');
    }

    public function index() {
        $stories = $this->story_model->getAll();

        for($i = 0; $i < count($stories); $i++) {
            $questions = $this->question_model->getByStory($stories[$i]['id']);
            $stories[$i]['quesitons'] = $questions;
        }
        $data['stories'] = $stories;
        $this->load->view("answer/index", $data);
    }

    public function post() {
        $data = $this->input->post('data');
        $answer_id = uniqid();
        foreach ($data as $answer) {
            $answer['answer_id'] = $answer_id;
            $this->answer_model->addNew($answer);
        }
        echo json_encode(array("answer_id" => $answer_id));
    }
}