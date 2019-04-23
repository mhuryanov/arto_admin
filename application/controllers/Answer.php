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
            if($questions != null && count($questions) > 0) {
                for($j = 0; $j < count($questions); $j++) {
                    $temp_sub = $this->getSubQuestion($stories[$i]['id'], $questions[$j]['id']);
                    if($temp_sub != null && count($temp_sub) > 0) {
                        $questions[$j]['subs'] = $temp_sub;
                    } 
                }
            }
            $stories[$i]['questions'] = $questions;
        }

        $data['stories'] = $stories;
        // var_dump($stories);
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