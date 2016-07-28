<?php

require('../classes/connection.php');
require('../classes/login.php');
require('../classes/blog.php');

$data['success'] = false;

if (isset($_POST['id']) && isset($_POST['title']) && isset($_POST['post']))
{
	$id = $_POST['id'];
	$title = $_POST['title'];
	$post = $_POST['post'];

	$connection = new Connection();

	$login = new Login($connection);
	$blog = new Blog($connection);

	if ($login->logged_in && $login->rights >= 2)
	{
		$data['success'] = $blog->editPost($id, $login->user, $title, $post);
	}
	else
	{
		$data['success'] = false;
	}
}

echo json_encode($data);

?>
