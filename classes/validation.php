<?php

class Validation
{
	public function validateUser($user)  // limits user characters
	{
		$search = '/^[a-zA-Z][a-zA-Z0-9_-]{2,19}$/';
		
		return preg_match($search, $user);
	}
	
	public function validatePass($pass)  // limits password to a minimum length
	{
		return strlen($pass) >= 8;
	}
}

?>