<?php

require('../classes/connection.php');
require('../classes/login.php');
require('../classes/photo.php');

$data['success'] = false;

if (isset($_GET['id']))
{
	$id = $_GET['id'];

	$connection = new Connection();
	
	$login = new Login($connection);
	$photo = new Photo($connection);
	
	if ($login->logged_in && $login->rights >= 2)
	{
		$data['success'] = $photo->deletePhoto($id, $login->user);
	}
	
}

echo json_encode($data);

?>
