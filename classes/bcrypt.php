<?php

class Bcrypt
{
	public function check($pass, $hash)  // checks to see if the password + salt creates the given hash
	{
		$salt = substr($hash, 0, 29);
		$newHash = $this->createHash($pass, $salt);
		
		return $newHash == $hash;
	}
	
	public function createHash($pass, $salt = null)  // encrypts the password using the provided salt
	{
		if ($salt == null)
			$salt = $this->createSalt(12);
			
		return crypt($pass, $salt);
	}
	
	public function createSalt($work_factor = 8)  // randomly creates a new salt
	{
		$salt = '$2a$' . str_pad($work_factor, 2, '0', STR_PAD_LEFT) . '$' . substr(uniqid('', true), 0, 22);
		
		return $salt;
	}
}

?>
