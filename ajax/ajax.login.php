<?php

require('../classes/connection.php');
require('../classes/login.php');

$data['success'] = false;

if (isset($_POST['user'], $_POST['pass']))
{
	$user = $_POST['user'];
	$pass = $_POST['pass'];

	$link = new Connection();
	$login = new Login($link);
	
	if ($data['success'] = $login->authenticate($user, $pass))
	{
		$login->createSession($user);
	}
	
}

echo json_encode($data);

?>
