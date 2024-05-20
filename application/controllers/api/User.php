<?php

use Restserver\Libraries\REST_Controller;

require APPPATH . 'libraries/REST_Controller.php';

class User extends REST_Controller {

	public function __construct() {
		parent::__construct();
		// Load the UserModel which will be used for database interactions related to user data
		$this->load->model('UserModel');
	}

	
	// User Login function
	public function login_post() {

		// Get the user_id from the session
		$this->user_id = $this->session->userdata('user_id');

		// Get the username and password from the POST request
		$username = strip_tags($this->post('username'));
		$password = strip_tags($this->post('password'));

		// Call the loginUser method of the UserModel to check the credentials
		$result = $this->UserModel->loginUser($username, sha1($password));

		// If the result is not false, the login is successful
		if($result != false) {
			// Set session data
			$session_data = array(
				'user_id' => $result->user_id,
				'ip_address' => $_SERVER['REMOTE_ADDR'],
				'login_timestamp' => time(),
				'login_date' => date('Y-m-d H:i:s')
			);

			// Set the session data
			$this->session->set_userdata($session_data);

			// Send a response with the user data
			$this->response(array(
				'status' => TRUE,
				'message' => 'User has logged in successfully.',
				'data' => true,
				'username' => $result->username,
				'user_id' => $result->user_id,
				'premium' => $result->premium,
				'occupation' => $result->occupation,
				'userimage'=> $result->userimage,
				'name' => $result->name,
				'email' => $result->email,
			), REST_Controller::HTTP_OK);
		}
		else {
			$this->response("Enter valid username and password", REST_Controller::HTTP_BAD_REQUEST);
		}
	}


	// Adding image for asking
	public function ask_question_image_post() {

		// Check if an image file is uploaded
		if (!empty($_FILES['image']['name'])) {
			// Define the upload directory
			$uploadDir = 'C:\xampp\htdocs\TechInquireHub\assets\images\question';

			// Set the upload configuration
			$config['upload_path'] = $uploadDir;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = 1024 * 10; // 10 MB

			// Load the upload library
			$this->load->library('upload', $config);

			// Perform the upload
			if ($this->upload->do_upload('image')) {
				// If the upload is successful, get the uploaded file data
				$uploadData = $this->upload->data();

				// Define the image path
				$imagePath = '../../assets/images/question/' . $uploadData['file_name'];

				// Send a response with the image path
				$this->response(array('imagePath' => $imagePath), REST_Controller::HTTP_OK);
			} 
			else {
				// If there was an error uploading the file, send a response with the error message
				$this->response(array('error' => $this->upload->display_errors()), REST_Controller::HTTP_BAD_REQUEST);
			}
		} 
		else {
			// error message empty image path
			$this->response(array('imagePath' => ''), REST_Controller::HTTP_OK);
		}
	}

	
	//  Function to handle user signup process
	public function register_post() {

		// Retrieve and sanitize input data
		$username = strip_tags($this->post('username'));
		$password = strip_tags($this->post('password'));
		$occupation = strip_tags($this->post('occupation'));
		$premium = strip_tags($this->post('premium'));
		$name = strip_tags($this->post('name'));
		$email = strip_tags($this->post('email'));

		// Check if all required fields are filled
		if(!empty($username) && !empty($password) &&!empty($occupation) && !empty($name) && !empty($email)){

			// Prepare user data for registration
			$userData = array(
				'username' => $username,
				'password' => sha1($password), // Password is hashed using SHA1
				'occupation' => $occupation,
				'premium' => $premium,
				'name' => $name,
				'email' => $email
			);

			// Check if the username already exists
			if($this->UserModel->checkUser($username)) {
				// Error message for existing username
				$this->response("Username already exists", 409);
			}else{
				// Register the user
				$userInformation = $this->UserModel->registerUser($userData);
				if($userInformation) {
					// If registration is successful, send a response with the user data
					$this->response(array(
							'status' => TRUE,
							'message' => 'User has been registered successfully.',
							'data' => $userInformation)
						, REST_Controller::HTTP_OK);
				}else{
					// If registration failed, send a response with an error message
					$this->response("Failed to register user", REST_Controller::HTTP_BAD_REQUEST);
				}
			}
		}
		else{
			// If not all required fields are filled, send a response with an error message
			$this->response("Enter valid information", REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	
	// Function for user infromation updating.
	public function edit_user_post() {

		// Retrieve and sanitize input data
		$user_id = strip_tags($this->post('user_id'));
		$username = strip_tags($this->post('username'));
		$occupation = strip_tags($this->post('occupation'));
		$premium = strip_tags($this->post('premium'));
		$name = strip_tags($this->post('name'));
		$email = strip_tags($this->post('email'));

		// Check if all required fields are filled
		if(!empty($user_id) && !empty($username) && !empty($occupation) && !empty($name) && !empty($email)){

			// Prepare user data for update
			$userData = array(
				'user_id' => $user_id,
				'username' => $username,
				'occupation' => $occupation,
				'premium' => $premium,
				'name' => $name,
				'email' => $email
			);

			// Update the user
			$updateUser = $this->UserModel->updateUser($user_id, $userData);
			if($updateUser !== false) {

				// User was updated successfully
				$this->response(array(
					'status' => TRUE,
					'message' => 'User has been updated successfully.',
					'data' => $userData) // Return updated user data
					, REST_Controller::HTTP_OK);
			} else {

				// Update was not performed, possibly due to data being already up to date
				$this->response(array(
					'status' => FALSE,
					'message' => 'User data is already up to date.',
					'data' => null) // Return null data or an appropriate message
					, REST_Controller::HTTP_OK);
			}

		}
		else {
			// If not all required fields are filled, send a response with an error message
			$this->response("Enter valid information", REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	
	// function for user image updating. 
	public function edit_user_image_post() {

		// Check if an image file is uploaded
		if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
			// Define the upload directory
			$uploadDir = '/Applications/XAMPP/xamppfiles/htdocs/TechInquireHub/assets/images/userimage/';

			// Set the upload configuration
			$config['upload_path'] = $uploadDir;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = 1024 * 10; // 10 MB

			// Load the upload library
			$this->load->library('upload', $config);

			// Perform the upload
			if ($this->upload->do_upload('image')) {
				// If the upload is successful, get the uploaded file data
				$uploadData = $this->upload->data();

				// Define the image path relative to the project root
				$imagePath = '../../assets/images/userimage/' . $uploadData['file_name'];

				// Send a response with the image path
				$this->response(array('imagePath' => $imagePath), REST_Controller::HTTP_OK);
			} 
			else {
				// Response with error message for errors in uploading the files
				$this->response(array('error' => $this->upload->display_errors()), REST_Controller::HTTP_BAD_REQUEST);
			}
		} 
		else {
			//  send a error response "empty image path", if no file was uploaded
			$this->response(array('imagePath' => ''), REST_Controller::HTTP_OK);
		}
	}


	//  Function to handle the logout process.
	public function logout_post() {

		// Destroy the session to log out the user
		$this->session->sess_destroy();

		// Send a response indicating the successful logout
		$this->response(array(
			'success' => true,
			'message' => 'Logout successful'
		), REST_Controller::HTTP_OK);
	}


	//  Function for profile image updating
	public function upload_image_post() {

		// Retrieve input data
		$user_id = strip_tags($this->post('user_id'));
		$userpic = strip_tags($this->post('userimage'));

		// Initialize userimage variable
		$userimage = '';

		// Check if an image file is uploaded
		if(!empty($_FILES['image']['name'])){

			// Define the upload directory
			$uploadDir = '/Applications/XAMPP/xamppfiles/htdocs/TechInquireHub/assets/images/userimage/';
			$uploadFile = $uploadDir . basename($_FILES['image']['name']);

			// Move the uploaded file to the upload directory
			if(move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)){
				$userimage = $uploadFile;
			}
		}

		// Check if user_id and userpic are not empty
		if(!empty($user_id) && !empty($userpic)) {

			// Prepare user data for update
			$userData = array (
				'user_id' => $user_id,
				'userimage' => $userpic
			);

			// Update the user image
			$updateUser = $this->UserModel->updateUserImage($user_id, $userData);
			if($updateUser !== false) {

				// User image was updated successfully
				$this->response(array(
					'status' => TRUE,
					'message' => 'User image has been updated successfully.',
					'data' => $userData) // Return updated user data
					, REST_Controller::HTTP_OK);
			} 
			else {

			// Update was not performed
			$this->response(array(
				'status' => FALSE,
				'message' => 'User image is already up to date.',
				'data' => null), REST_Controller::HTTP_OK);
			}
		}
	}
	
	
	//  Function password change.
	public function change_password_post(){

		// Retrieve and sanitize input data
		$user_id = strip_tags($this->post('user_id'));
		$oldpassword = strip_tags($this->post('oldpassword'));
		$newpassword = strip_tags($this->post('newpassword'));

		// Check if all required fields are filled
		if(!empty($user_id) && !empty($oldpassword) && !empty($newpassword)){

			// Hash the old and new passwords
			$oldpassword = sha1($oldpassword);
			$newpassword = sha1($newpassword);

			// Call the updatePassword method of the UserModel to change the password
			$updateUser = $this->UserModel->updatePassword($user_id, $oldpassword, $newpassword);
			if($updateUser !== false) {

				// Send a response indicating the successful password change
				$this->response(array(
					'status' => TRUE,
					'message' => 'User password has been updated successfully.') // Return updated user data
					, REST_Controller::HTTP_OK);
			} 
			else {
				// Send a response indicating the failed password change
				$this->response(array(
					'status' => FALSE,
					'message' => 'Please check the credentials.',
					'data' => null) // Return null data or an appropriate message
					, REST_Controller::HTTP_OK);
			}
		}
	}


	// Function for password reset for fogot password 
	public function forget_password_post(){

		// Retrieve and sanitize input data
		$username = strip_tags($this->post('username'));
		$newpassword = strip_tags($this->post('newpassword'));

		// Check if username and newpassword are not empty
		if(!empty($username) && !empty($newpassword)){

			// Hash the new password
			$newpassword = sha1($newpassword);

			// Call the forgetPassword method of the UserModel to reset the password
			$updateUser = $this->UserModel->forgetPassword($username, $newpassword);
			if($updateUser !== false) {

				// Send a response indicating the successful password reset
				$this->response(array(
					'status' => TRUE,
					'message' => 'User password has been updated successfully.') // Return updated user data
					, REST_Controller::HTTP_OK);
			} 
			else {
				// Send a response indicating the failed password reset
				$this->response(array(
					'status' => FALSE,
					'message' => 'Please check the credentials.',
					'data' => null) // Return null data
					, REST_Controller::HTTP_OK);
			}
		}
	}

}
