<?php

require('bcrypt.php');
require('validation.php');

class Login
{
	public $user;
	public $name;
	public $rights;
	public $logged_in = false;
	
	public $connection;
	
	protected $bcrypt;
	
	public function __construct($connection)
	{
		$this->bcrypt = new Bcrypt();
	
		$this->connection = $connection;
		
		session_start();
		$this->checkSession();
	}
	
	public function checkSession()
	{
		if (isset($_SESSION['user'], $_SESSION['name'], $_SESSION['rights']))  // REMINDER -- Possible vulnerability!  -- Should I check database to verify user everytime this is called?  Or would this waste server resources?
		{
			$this->user = $_SESSION['user'];
			$this->name = $_SESSION['name'];
			$this->rights = $_SESSION['rights'];
			$this->logged_in = true;
		}
	}
	
	public function createSession($user)
	{
		$userInfo = $this->getUserInfo($user);
		$_SESSION['user'] = $user;
		$_SESSION['name'] = $userInfo['first_name'];
		$_SESSION['rights'] = $userInfo['rights'];
	}
	
	public function endSession()
	{
		return session_destroy();
	}
	
	public function authenticate($user, $pass)
	{
		$userClean = $this->connection->sanitize($user);
		$passClean = $this->connection->sanitize($pass);
		
		if ($user == $userClean && $pass == $passClean)
		{
			$user = $userClean;
			$pass = $passClean;
		}
		else
		{
			return false;
		}
		
		$validation = new Validation();
		
		if ($validation->validateUser($user) && $validation->validatePass($pass))
		{
			
			$query = "SELECT hash FROM users WHERE user = '$user'";
				
			$result = $this->connection->db->query($query);
			$row = $result->fetch_assoc();
			
			$hash = $row['hash'];
			
			return $this->bcrypt->check($pass, $hash);
		}
		else
		{
			return false;
		}
	}
	
	public function changePassword($user, $old_pass, $new_pass)
	{
		$result = false;
		
		if ($this->authenticate($user, $old_pass))
		{
			$result = $this->setPassword($user, $new_pass);
		}
		
		return $result;
	}
	
	protected function setPassword($user, $pass)
	{
		$hash = $this->bcrypt->createHash($pass);
		
		$query = "
			UPDATE users 
			SET hash='$hash' 
			WHERE user='$user'
		";
			
		return $this->connection->query($query);
	}
	
	public function getUserInfo($user)
	{
		$query = "SELECT * FROM users WHERE user = '$user'";
		
		$result = $this->connection->query($query);
		$row = $result->fetch_assoc();
		
		return $row;
	}
}

?>
