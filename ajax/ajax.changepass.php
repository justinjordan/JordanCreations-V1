<?php

require('../classes/connection.php');
require('../classes/login.php');

$data['success'] = false;

if (isset($_POST['old_pass'], $_POST['new_pass']))
{
	$link = new Connection();
	$login = new Login($link);
	
	if ($login->logged_in)
	{
		$user = $login->user;
		$old_pass = $_POST['old_pass'];
		$new_pass = $_POST['new_pass'];
		
		$data['success'] = $login->changePassword($user, $old_pass, $new_pass);
	}
	
}

echo json_encode($data);

?>
