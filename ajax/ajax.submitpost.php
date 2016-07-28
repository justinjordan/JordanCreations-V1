<?php

require('../classes/connection.php');
require('../classes/login.php');
require('../classes/blog.php');

$data['success'] = false;

if (isset($_POST['title']) && isset($_POST['post']))
{
	$title = $_POST['title'];
	$post = $_POST['post'];

	$connection = new Connection();
	
	$login = new Login($connection);
	$blog = new Blog($connection);
	
	$data['success'] = $blog->submitPost($login->user, $title, $post);
	
}

echo json_encode($data);

?>
