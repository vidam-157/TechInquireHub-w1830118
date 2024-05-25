<?php

use Restserver\Libraries\REST_Controller;

require APPPATH . 'libraries/REST_Controller.php';

class Answer extends REST_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('AnswerModel');
	}

	public function getAnswers_get($questionid) {

		// Retrieve answers from the AnswerModel based on the provided question ID
		$answers = $this->AnswerModel->getAnswers($questionid);

		// Check for answers retrved
		if (!empty($answers)) {
			$this->response($answers, REST_Controller::HTTP_OK);
		} 
		else {
			$this->response(array(), REST_Controller::HTTP_OK);
		}
	}

	public function ans_image_post() {
		// Check for the file is not being empty
		if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
			$uploadDir = 'c:\xampp\htdocs\TechInquireHub\assets\images\answer';

			// Upload configurations
			$config['upload_path'] = $uploadDir;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = 1024 * 10; // 10 MB

			// Load upload library
			$this->load->library('upload', $config);

			// Uplaod images
			if ($this->upload->do_upload('image')) {
				$uploadData = $this->upload->data();
				$imagePath = '../../assets/images/answer/' . $uploadData['file_name'];

				$this->response(array('imagePath' => $imagePath), REST_Controller::HTTP_OK);
			} 
			else {
				// Error uploading file
				$this->response(array('error' => $this->upload->display_errors()), REST_Controller::HTTP_BAD_REQUEST);
			}
		} 
		else {
			// No file uploaded, return a default image path or an empty response
			$this->response(array('imagePath' => ''), REST_Controller::HTTP_OK);
		}
	}


	public function add_answer_post() {
		$_POST = json_decode(file_get_contents("php://input"), true);

		$questionid = strip_tags($this->post('questionid'));
		$userid = strip_tags($this->post('userid'));

		$answer = $this->post('answer');

		$imageurl = strip_tags($this->post('answerimage'));
		$answeraddreddate = strip_tags($this->post('answeraddeddate'));
		$rate = strip_tags($this->post('rate'));

		// Initialize answerimage variable
		$answerimage = '';

		// Check if an image file is uploaded
		if (!empty($_FILES['image']['name'])) {
			$uploadDir = 'C:\xampp\htdocs\TechInquireHub\assets\images\answer'; // Define upload directory
			$uploadFile = $uploadDir . basename($_FILES['image']['name']); // Define file name

			// Attempt to move uploaded file to specified directory
			if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
				// Update image path
				$answerimage = $uploadFile;
			}
		}

		if (!empty($questionid) && !empty($userid) && !empty($answer) && !empty($answeraddreddate)) {
			$result = $this->AnswerModel->addAnswer($questionid, $userid, $answer, $answeraddreddate, $imageurl, $rate);
			if ($result) {
				$this->response(array(
					'status' => TRUE,
					'message' => 'Answer added successfully.'
				), REST_Controller::HTTP_OK);
			} 
			else {
				$this->response("Failed to add answer.", REST_Controller::HTTP_BAD_REQUEST);
			}
		}
	}
}