<?php

class Contact
{
	private $link;
	private $table;
	
	public function __construct($link, $table = 'contact')
	{
		$this->link = $link;
		$this->table = $table;
	}
	
	public function sendMessage($name, $email, $msg)
	{
		if ($name != '' && $email != '' && $msg != '')
		{
			if ($this->testUser())
			{
				$ip = $_SERVER['REMOTE_ADDR'];
				if ($ip == '127.0.0.1')
				{
					return 1;
				}
				else
				{
					$body = "Name:  $name --- $ip\nEmail:  $email\n\n\n$msg";
						
					return mail('justin@jordancreations.com', $name . ' - Contact Form', $body, 'From: contact_form@jordancreations.com');
				}
			}
			else
			{
				return 0;  //  user fail --- return FALSE
			}
		}
		else
		{
			return 0;  //  Incomplete --- return FALSE
		}
	}
	
	private function testUser()
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		
		$query = "
			SELECT attempts, banned, time_to_sec(timediff(now(), lastMessage)) AS attempt_lapse
			FROM $this->table
			WHERE ip='$ip'
			";
		
		$result = $this->link->query($query);
		
		$row_count = $result->num_rows;
		
		if ($row_count != 0)
		{
			$row = $result->fetch_assoc();
			
			$attempt_lapse = $row['attempt_lapse'];
			$attempts = $row['attempts'];
			$banned = $row['banned'];
			
			if ($attempt_lapse > 86400 && !$banned)
			{
				$this->updateUser();
				return 1;  //  Allow user
			}
			else
			{
				if ($attempts >= 9)
					$this->banUser();
				
				$this->logAttempt();
				
				return 0;  //  Deny User
			}
		}
		else
		{
			$this->storeUser();
			return 1;
		}
	}
	
	private function storeUser()
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		
		$query = "
			INSERT INTO $this->table (ip, lastMessage)
			VALUES('$ip', now())
			";
		
		return $this->link->query($query);
	}
	private function updateUser()
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		
		$query = "
			UPDATE $this->table
			SET lastMessage=now(), attempts='0'
			WHERE ip='$ip'
			";
		
		return $this->link->query($query);
	}
	
	private function logAttempt()
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		
		$query = "
			UPDATE $this->table
			SET attempts=attempts+1
			WHERE ip='$ip'
			";
		
		return $this->link->query($query);
	}
	
	private function banUser()
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		
		$query = "
			UPDATE $this->table
			SET banned='1'
			WHERE ip='$ip'
			";
		
		return $this->link->query($query);
	}
}

?>
