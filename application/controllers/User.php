<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('UserModel');
	}
	
	/*public function test(){
		$name = $this->input->post('username');
		$A = $this->UserModel->generateNewToken($name);
		echo json_encode($A);
	
	}*/
	
	public function register(){
		
		$username 	= $this->input->post('username');
		$pass 		= $this->input->post('password');
		$email 		= $this->input->post('email');
		$address 	= $this->input->post('address');
		
		$endRespond = $this->UserModel->add($username, $pass, $email, $address);
		
		echo json_encode($endRespond);
		
	}
	
	public function login(){
		
		$username 	= $this->input->post('username');
		$pass 		= $this->input->post('password');
		
		$keyGenerated = $this->UserModel->verify($username, $pass);
		
		echo json_encode($keyGenerated);
		
	}
}