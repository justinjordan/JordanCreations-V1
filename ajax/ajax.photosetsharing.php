<?php

require_once('../classes/connection.php');
require_once('../classes/login.php');
require_once('../classes/photo.php');

$data['success'] = false;

if (isset($_GET['id'], $_GET['setting']))
{
	$id = $_GET['id'];
	$setting = $_GET['setting'];
	
	$connection = new Connection();
	
	$login = new Login($connection);
	$photo = new Photo($connection);
	
	if ($login->logged_in && $login->rights >= 2)
	{
		$data['success'] = $photo->setSharing($id, $setting);
	}
	
}

echo json_encode($data);

?>
