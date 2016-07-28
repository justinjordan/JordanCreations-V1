<?php

require ('../classes/connection.php');
$connection = new Connection();

require ('../classes/login.php');
$login = new Login($connection);

require ('../classes/photo.php');
$photo = new Photo($connection);

if ($login->logged_in && $login->rights >= 3)
{
	$result = $photo->getAllPhotos();

	while ($row = $result->fetch_assoc())
	{
		$photo->createThumbnail($row['file_name'], 230);
	}
	
	echo "All photo thumbnails have been regenerated!";
}
else
{
	echo "You are not authorized to access this page!";
}

?>