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

    public function postInd() {
        $data['answer'] = $this->input->post('data');
        $data['story_id'] = $this->input->post("storyid");
        $data['question_id'] = $this->input->post("qid");
        $data['answer_id'] = $this->input->post("uid");

        if($this->answer_model->is_exist($data)) {
            $this->answer_model->update($data);
        } else {
            $this->answer_model->addNew($data);
        }

        $data['success'] = true;
        echo json_encode($data);
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

    function getSubQuestionAnswer($storyId, $qid, $answerid) {
        $subs = $this->question_model->getSubQuestions($storyId, $qid);
        if($subs != null && count($subs) > 0) {
            for ($i = 0; $i < count($subs); $i++) {
                $answer = $this->answer_model->getAnswer($storyId, $qid, $answerid);
                
                $subs[$i]['answer'] = $answer;
                $temp_sub = $this->getSubQuestionAnswer($storyId, $subs[$i]['id'], $answerid);
                if($temp_sub != null && count($temp_sub) > 0) {
                    $subs[$i]['subs'] = $temp_sub;
                } 
            }

            return $subs;
        } else {
            return null;
        }
    }

    function showStory($answerid) {
        $stories = $this->story_model->getAll();

        for($i = 0; $i < count($stories); $i++) {
            $questions = $this->question_model->getByStory($stories[$i]['id']);
            
            if($questions != null && count($questions) > 0) {
                for($j = 0; $j < count($questions); $j++) {
                    $answer = $this->answer_model->getAnswer($stories[$i]['id'], $questions[$j]['id'], $answerid);
                    $questions[$j]['answer'] = $answer;
                    $temp_sub = $this->getSubQuestionAnswer($stories[$i]['id'], $questions[$j]['id'], $answerid);
                    if($temp_sub != null && count($temp_sub) > 0) {
                        $questions[$j]['subs'] = $temp_sub;
                    } 
                }
            }
            $stories[$i]['questions'] = $questions;
        }

        $data['stories'] = $stories;
        $data['answers'] = $this->answer_model->getAllAnswerById($answerid);
        // var_dump($data);
        $this->load->view("answer/showStory", $data);
    }

    function getAnswers($answerid) {
        $stories = $this->story_model->getAll();

        for($i = 0; $i < count($stories); $i++) {
            $questions = $this->question_model->getByStory($stories[$i]['id']);
            
            if($questions != null && count($questions) > 0) {
                for($j = 0; $j < count($questions); $j++) {
                    $answer = $this->answer_model->getAnswer($stories[$i]['id'], $questions[$j]['id'], $answerid);
                    $questions[$j]['answer'] = $answer;
                    $temp_sub = $this->getSubQuestionAnswer($stories[$i]['id'], $questions[$j]['id'], $answerid);
                    if($temp_sub != null && count($temp_sub) > 0) {
                        $questions[$j]['subs'] = $temp_sub;
                    } 
                }
            }
            $stories[$i]['questions'] = $questions;
        }

        $data['stories'] = $stories;
        $data['answers'] = $this->answer_model->getAllAnswerById($answerid);
        $data['answerid'] = $answerid;
        // var_dump($data);
        $this->load->view("answer/questionAndAnswer", $data);
    }

    function myStory() {
        $this->isLoggedIn();
        $answerid = $this->vendorId;

        $this->global['pageTitle'] = 'Moneo : My Story';

        $stories = $this->story_model->getAll();

        for($i = 0; $i < count($stories); $i++) {
            $questions = $this->question_model->getByStory($stories[$i]['id']);
            
            if($questions != null && count($questions) > 0) {
                for($j = 0; $j < count($questions); $j++) {
                    $answer = $this->answer_model->getAnswer($stories[$i]['id'], $questions[$j]['id'], $answerid);
                    $questions[$j]['answer'] = $answer;
                    $temp_sub = $this->getSubQuestionAnswer($stories[$i]['id'], $questions[$j]['id'], $answerid);
                    if($temp_sub != null && count($temp_sub) > 0) {
                        $questions[$j]['subs'] = $temp_sub;
                    } 
                }
            }
            $stories[$i]['questions'] = $questions;
        }

        $data['stories'] = $stories;
        $data['answers'] = $this->answer_model->getAllAnswerById($answerid);
        $data['answerid'] = $answerid;
        // var_dump($data);
        $this->loadViews("answer/myStory", $this->global, $data, NULL);
    }
}