<?php
	
	class Connection
	{
		public $db;
		
		public function __construct($host = 'HOST_GOES_HERE', $user = 'USER_GOES_HERE', $pass = 'PASS_GOES_HERE', $db = 'DB_GOES_HERE')
		{
			$this->db = new mysqli($host, $user, $pass, $db);
		}
		
		public function __destruct()
		{
			$this->db->close();
		}
		
		public function query($query)
		{
			$result = $this->db->query($query);
			
			return $result;
		}
		
		public function sanitize($str)
		{
			if (get_magic_quotes_gpc())
			{
				return $str;
			}
			else
			{
				return $this->db->real_escape_string($str);
			}
		}
	}
?>
