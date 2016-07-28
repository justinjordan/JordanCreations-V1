<?php

require ('../classes/connection.php');
$connection = new Connection();

require ('../classes/login.php');
$login = new Login($connection);

require ('../classes/photo.php');
$photo = new Photo($connection);

if ($login->logged_in && $login->rights >=2)
{
	$folder = "../images/photos/";
	$extension = strtolower(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION));
	
	$acceptedTypes = array("jpg", "jpeg");
	
	if (in_array($extension, $acceptedTypes))
	{
		$max_size = 20;  // megs
		if ($_FILES["file"]["size"] < $max_size * 1048576)
		{
			do
			{
				$name = md5(uniqid()) . '.' . $extension;
			} while (file_exists($folder . $name));

			if (move_uploaded_file($_FILES["file"]["tmp_name"], $folder . $name))
			{
				$photo->addPhoto($name, $login->user);
				
				
				echo "done";
			}
			else
			{
				echo "error";
			}
		}
		else
		{
			echo "size";
		}
	}
	else
	{
		echo "extension";
	}
}

?>