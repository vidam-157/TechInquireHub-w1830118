<?php

use Restserver\Libraries\REST_Controller;

require APPPATH . 'libraries/REST_Controller.php';

class Question extends REST_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('QuestionModel');
	}

	public function displayAllQuestions_get($question_id = FALSE) {

		if ($question_id === FALSE) {
			$questions = $this->QuestionModel->getAllQuestions();
		} 
		else {
			$questions = $this->QuestionModel->getQuestion($question_id);
		}

		// Check if the user data exists
		if (!empty($questions)) {
			$this->response($questions, REST_Controller::HTTP_OK);
		}
		else {
			$this->response(array(
				'status' => FALSE,
				'message' => 'No questions found.'
			), REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function bookmarkQuestions_get($userid) {

		$questions = $this->QuestionModel->getBookmarkQuestions($userid);

		if($questions) {
			$this->response($questions, REST_Controller::HTTP_OK);
		} 
		else {
			$this->response(array(
				'status' => FALSE,
				'message' => 'No bookmarked questions found.'
			), REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function displaySearchQuestions_get($searchWord = FALSE) {

		if ($searchWord === FALSE) {
			$questions = $this->QuestionModel->getAllQuestions();
		} 
		else {
			$questions = $this->QuestionModel->getSearchQuestions($searchWord);
		}

		// Check if the user data exists
		if (!empty($questions)) {
			$this->response($questions, REST_Controller::HTTP_OK);
		} 
		else {
			$this->response(array(
				'status' => FALSE,
				'message' => 'No questions found.'
			), REST_Controller::HTTP_NOT_FOUND);
		}
	}

	// public function upvote_get($questionid) {

	// 	$upvote = $this->QuestionModel->upvote($questionid);

	// 	if($upvote) {
	// 		$this->response(array(
	// 			'status' => TRUE,
	// 			'message' => 'Question upvoted successfully.'
	// 		), REST_Controller::HTTP_OK);
	// 	} 
	// 	else {
	// 		$this->response("Failed to upvote question.", REST_Controller::HTTP_BAD_REQUEST);
	// 	}
	// }

	// public function downvote_get($questionid) {

	// 	$upvote = $this->QuestionModel->downvote($questionid);

	// 	if($upvote) {
	// 		$this->response(array(
	// 			'status' => TRUE,
	// 			'message' => 'Question downvoted successfully.'
	// 		), REST_Controller::HTTP_OK);
	// 	} 
	// 	else {
	// 		$this->response("Failed to downvote question.", REST_Controller::HTTP_BAD_REQUEST);
	// 	}
	// }

	public function addquestion_post() {
		$_POST = json_decode(file_get_contents("php://input"), true);
		$this->form_validation->set_rules('title', 'checkTitle', 'required');
		$this->form_validation->set_rules('question', 'checkQuestion', 'required');
		$this->form_validation->set_rules('tags', 'checkTags', 'required');
		$this->form_validation->set_rules('category', 'checkCategory', 'required');
		$this->form_validation->set_rules('difficulty', 'checkDifficulty', 'required');

		$userid = strip_tags($this->post('user_id'));
		$title = strip_tags($this->post('title'));
		$question = $this->post('question');
		$tags = strip_tags($this->post('tags'));
		$category = strip_tags($this->post('category'));
		$qaddeddate = strip_tags($this->post('qaddeddate'));
		$imageurl = strip_tags($this->post('questionimage'));

		// Initialize questionimage variable
		$questionimage = '';

		// Check if an image file is uploaded
		if (!empty($_FILES['image']['name'])) {
			// Define upload directory and file name
			$uploadDir = 'C:\xampp\htdocs\TechInquireHub\assets\images\question';
			$uploadFile = $uploadDir . basename($_FILES['image']['name']);

			// Attempt to move uploaded file to specified directory
			if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
				// File uploaded successfully, update image path
				$questionimage = $uploadFile;
			}
		}

		if (!empty($userid) && !empty($title) && !empty($question) && !empty($tags) && !empty($category) && !empty($qaddeddate)) {
			$tagArray = explode(',', $tags);

			// Pass the updated $questionimage variable to the addQuestion function
			$result = $this->QuestionModel->addQuestion($userid, $title, $question, $category, $qaddeddate, $tagArray, $imageurl);
			if ($result) {
				$this->response(array(
					'status' => TRUE,
					'message' => 'Question added successfully.'
				), REST_Controller::HTTP_OK);
			} else {
				$this->response("Failed to add question.", REST_Controller::HTTP_BAD_REQUEST);
			}
		}
	}

	public function getBookmark_post() {

		$questionid = $this->post('questionid');
		$userid = $this->post('userid');

		$bookmark = $this->QuestionModel->getBookmark($questionid, $userid);
		if($bookmark) {
			$this->response(array(
				'is_bookmark' => TRUE,
				'status' => TRUE,
				'message' => 'Question bookmarked successfully.'
			), REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				'is_bookmark' => FALSE,
				'status' => TRUE,
				'message' => 'Question bookmarked successfully.'
			), REST_Controller::HTTP_OK);
		}
	}

	public function remove_bookmark_post(){

		$questionid = $this->post('questionid');
		$userid = $this->post('userid');

		$bookmark = $this->QuestionModel->removeBookmark($questionid, $userid);

		if($bookmark) {
			$this->response(array(
				'status' => TRUE,
				'message' => 'Question removed from bookmark successfully.'
			), REST_Controller::HTTP_OK);
		} 
		else {
			$this->response("Failed to remove question from bookmark.", REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function add_bookmark_post() {

		$questionid = $this->post('questionid');
		$userid = $this->post('userid');

		$bookmark = $this->QuestionModel->addBookmark($questionid, $userid);
		if($bookmark) {
			$this->response(array(
				'status' => TRUE,
				'message' => 'Question added to the bookmark successfully.'
			), REST_Controller::HTTP_OK);
		} 
		else {
			$this->response("Failed to add question to the bookmark.", REST_Controller::HTTP_BAD_REQUEST);
		}
	}

}
