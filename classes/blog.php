<?php

class Blog
{
	private $connection;
	private $table;
	
	public function __construct($connection, $table = 'blog')
	{
		$this->connection = $connection;
		$this->table = $table;
	}
	
	public function cleanText($text)
	{
		$text = $this->connection->db->real_escape_string($text);
		$text = htmlspecialchars($text);
		
		return $text;
	}
	
	public function submitPost($user, $title, $post)  // return boolean
	{
		$user = $this->cleanText($user);
		$title = $this->cleanText($title);
		$post = $this->cleanText($post);
		
		$query = "
			INSERT INTO $this->table (user, title, post, date)
			VALUES ('$user', '$title', '$post', now())
			";
		
		return $this->connection->db->query($query);
	}
	
	public function editPost($id, $user, $title, $post)  // return boolean
	{
		$id = $this->cleanText($id);
		$user = $this->cleanText($user);
		$title = $this->cleanText($title);
		$post = $this->cleanText($post);
		
		$row = $this->getPost($id);
		
		if ($row['user'] == $user)
		{
			$query = "
				UPDATE $this->table 
				SET title='$title', post='$post'
				WHERE id='$id'
				";
			
			return $this->connection->db->query($query);
		}
		else
		{
			return false;
		}
	}
	
	public function deletePost($id, $user)  // return boolean
	{
		$id = $this->connection->db->real_escape_string($id);
		$user = $this->connection->db->real_escape_string($user);
		
		$row = $this->getPost($id);
		
		if ($row['user'] == $user)
		{
			$query = "
				DELETE FROM $this->table
				WHERE id='$id' AND user='$user'
				";
			
			return $this->connection->db->query($query);
		}
		else
		{
			return false;
		}
	}
	
	public function getPost($id = 0, $byRow = false)  // return row array
	{
		$id = $this->connection->db->real_escape_string($id);
		
		if ($byRow)
		{
			$query = "
					SELECT *
					FROM $this->table
					ORDER BY id DESC
					LIMIT $id, 1
					";
		}
		else
		{
			if ($id > 0)
			{
				$query = "
					SELECT *
					FROM $this->table
					WHERE id='$id'
					";
			}
			else
			{
				$query = "
					SELECT *
					FROM $this->table
					WHERE id=(
						SELECT max(id) FROM $this->table
					)
					";

			}
		}
			
		$result = $this->connection->db->query($query);
		
		return $result->fetch_assoc();
	}
	
	public function getPostTotal()  // return numberic value
	{
		$query = "SELECT count(*) AS total FROM $this->table";
		$result = $this->connection->db->query($query);
		$row = $result->fetch_assoc();
		return $row['total'];
	}
	
	public function getSinglePost($id)  // return result object
	{
		$id = $this->connection->db->real_escape_string($id);
		
		$query = "
					SELECT id, user, title, post, date, time_to_sec(timediff(now(), date)) AS secLapse, datediff(now(), date) AS dayLapse
					FROM $this->table
					WHERE id='$id'
					";
		
		return $this->connection->db->query($query);
	}
	
	public function getMultiplePosts($offset, $amount)  // return result object
	{
		$query = "
		SELECT id, user, title, post, date, time_to_sec(timediff(now(), date)) AS secLapse, datediff(now(), date) AS dayLapse
		FROM $this->table
		ORDER BY id DESC
		LIMIT $offset, $amount";
		
		return $this->connection->db->query($query);
	}

	public function humanizeDate($dayLapse, $secLapse)
	{
		$text = "";
		
		/*  SECONDS  */					
		if ($secLapse < 60)
		{
			if ($secLapse > 1)
				$text .=  $secLapse . ' seconds ago';
			else
				$text .=  'a second ago';
		}
		
		/*  MINUTES  */
		else if ($secLapse < 3600)
		{
			$t = floor($secLapse / 60);
			
			if ($t > 1)
				$text .=  $t . ' minutes ago';
			else
				$text .=  'a minute ago';
		}
		
		/*  HOURS  */
		else if ($secLapse < 86400)
		{
			$t = floor($secLapse / 3600);
			
			if ($t > 1)
				$text .=  $t . ' hours ago';
			else
				$text .=  'an hour ago';
		}
		
		/*  DAYS  */
		else if ($dayLapse < 7)
		{
			if ($dayLapse > 1)
				$text .=  $dayLapse . ' days ago';
			else
				$text .=  'a day ago';
		}
		
		/*  WEEKS  */
		else if ($dayLapse < 30)
		{
			$t = floor($dayLapse / 7);  /*  CALC # OF FULL WEEKS  */
			$d = $dayLapse - ($t * 7);  /*  CALC DAYS INTO WEEK   */
			
			if ($t > 1)
				$text .=  $t . ' weeks';
			else
				$text .=  'a week';
				
			if ($d > 0)
			{
				switch ($d)
				{
					case 1:
						$text .= ' and a day';
						
						break;
					default:
						$text .= ' and ' . $d . ' days';
				}
			}
			
			$text .= ' ago';
		}
		
		/*  MONTHS  */
		else if ($dayLapse < 365)
		{
			$t = floor($dayLapse / 30);  /*  CALC FULL MONTHS */
			$w = floor(($dayLapse - ($t * 30)) / 7);  /*  CALC WEEKS INTO MONTH  */
			
			if ($w == 4)  /*  ROUND 4 WEEKS UP TO A MONTH  */
			{
				$text .=  'almost ' . ($t + 1) . ' months ago';
			}
			else
			{
				if ($t > 1)
					$text .=  $t . ' months';
				else
					$text .=  'a month';
				
				if ($w > 0)
				{
					switch ($w)
					{
						case 1:
							$text .= ' and a week';
							
							break;
						default:
							$text .= ' and ' . $w . ' weeks';
					}
				}
				
				$text .=  ' ago';
			}
		}
		
		/*  YEARS  */
		else
		{
			$t = floor($dayLapse / 365);
			
			if ($t > 1)
				$text .=  $t . ' years ago';
			else
				$text .=  'a year ago';
		}
		
		return $text;
	}
}

?>
