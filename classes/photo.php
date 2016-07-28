<?php

class Photo
{
	private $connection;
	private $table;
	private $dir;
	
	public function __construct($connection, $table = 'photo', $dir = '../images/photos/')
	{
		$this->connection = $connection;
		$this->table = $table;
		$this->dir = $dir;
	}
	
	private function cleanText($text)
	{
		$text = $this->connection->db->real_escape_string($text);
		$text = htmlspecialchars($text);
		
		return $text;
	}
	
	public function addPhoto($filename, $user)
	{
		$filename = $this->cleanText($filename);
		$user = $this->cleanText($user);
		
		$this->createThumbnail($filename, 230);
		
		$query = "
			INSERT INTO $this->table (file_name, date_uploaded, user)
			VALUES ('$filename', now(), '$user')
			";
		
		return $this->connection->query($query);
	}
	
	private function deletePhotoFiles($filename)
	{
		
		if (unlink($this->dir . $filename) && unlink($this->dir . 'thumbs/' . $filename))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	private function getPhoto($id)  // return row array
	{
		$id = $this->cleanText($id);
		
		$query = "
			SELECT *
			FROM $this->table
			WHERE id=$id
			";
			
		$result = $this->connection->query($query);
		
		return $result->fetch_assoc();
	}
	
	public function deletePhoto($id, $user)
	{
		$id = $this->cleanText($id);
		$user = $this->cleanText($user);
		
		$row = $this->getPhoto($id);
		
		$query = "
			DELETE FROM $this->table
			WHERE id=$id
		";
			
		if ($result = $this->connection->query($query))
		{
			$this->deletePhotoFiles($row['file_name']);
		}
		
		return $result;
	}
	
	public function createThumbnail($filename, $new_width)
	{
		$src = imagecreatefromjpeg($this->dir . $filename);
		
		$width = imagesx($src);
		$height = imagesy($src);
		
		$new_height = floor($height * ($new_width / $width));
		
		$image_buffer = imagecreatetruecolor($new_width, $new_height);
		
		imagecopyresampled($image_buffer, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		
		imagejpeg($image_buffer, $this->dir . "thumbs/" . $filename);
		
	}
	
	public function getAllPhotos()
	{
		$query = "
			SELECT *
			FROM $this->table
			ORDER BY id DESC";
		
		return $this->connection->query($query);
	}
	
	public function getPhotos($offset, $amount)  // return result object
	{
		if ($amount)
		{
			$query = "
				SELECT *
				FROM $this->table
				ORDER BY id DESC
				LIMIT $offset, $amount
			";
		}
		else
		{
			$query = "
				SELECT *
				FROM $this->table
				WHERE id='$offset'
			";
		}

		return $this->connection->query($query);
	}
	
	public function getPhotoTotal()  // return numberic value
	{
		$query = "SELECT count(*) AS total FROM $this->table";
		$result = $this->connection->query($query);
		$row = $result->fetch_assoc();
		return $row['total'];
	}
	
	public function setSharing($id, $bool)
	{
		if ($bool)
		{
			$query = "
				UPDATE $this->table
				SET share='1'
				WHERE id='$id'
			";
		}
		else
		{
			$query = "
				UPDATE $this->table
				SET share='0'
				WHERE id='$id'
			";
		}
		
		return $this->connection->query($query);
	}
	
}

?>
