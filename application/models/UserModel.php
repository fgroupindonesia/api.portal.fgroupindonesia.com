<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserModel extends CI_Model {

	function __construct() {
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		
	}
	
	public function generateRespond($statusIn){
		
		$stat = array(
			'status' => $statusIn
		);
		
		return $stat;
	}
	
	public function add($usernameIn, $passIn, $emailIn, $addressIn){
		
		$stat = 'invalid';
		
		$data = array(
			'username' 	=> $usernameIn,
			'pass' 		=> $passIn,
			'email' 	=> $emailIn,
			'address' 	=> $addressIn
		);
		
		$foundInDB = $this->checkDuplicates($emailIn);
		
		if($foundInDB != true){
			$this->db->insert('data_user', $data);
			$stat = 'valid';
		}
		
		return $this->generateRespond($stat);
	}
	
	public function checkDuplicates($emailIn){
		
		$duplicate = false;
		
		$checker = array(
			'email' => $emailIn
		);
		
		
		$query = $this->db->get_where('data_user', $checker);
		
		foreach ($query->result() as $row)
		{
			$duplicate = true;
		}
		
		return $duplicate;
	}
	
	public function verify($usernameIn, $passIn){
		
		$endResult = $this->generateRespond('invalid');
		
		$multiParam = array(
			'username' => $usernameIn,
			'pass' => $passIn
		);
		
		$this->db->where($multiParam);		
		$query = $this->db->get('data_user');
		
		foreach ($query->result() as $row)
		{
			$endResult['status'] = 'valid';
			$endResult['multi_data'] = $this->generateNewToken($usernameIn);
		}
		
		if($endResult['status'] == 'invalid'){
			unset($endResult['multi_data']);
		}
		
		return $endResult;
		
	}
	
	public function generateNewToken($usernameIn){
		
		// get 75 character randomly generated
		$token =  substr(sha1(time()), 0, 75); 
		$exp_date = date('Y-m-d H:m:s', strtotime('+1 day'));
		
		$data = array(
			'username' => $usernameIn,
			'token' => $token,
			'expired_date' => $exp_date
		);
		
		$this->db->insert('data_token', $data);
		return $data;
		
	}
}