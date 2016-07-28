<?php

require('../classes/connection.php');
require('../classes/login.php');
require('../classes/blog.php');

$data['success'] = false;

if (isset($_POST['id']))
{
	$id = $_POST['id'];

	$connection = new Connection();
	
	$login = new Login($connection);
	$blog = new Blog($connection);
	
	$data['success'] = $blog->deletePost($id, $login->user);
	
}

echo json_encode($data);

?>
