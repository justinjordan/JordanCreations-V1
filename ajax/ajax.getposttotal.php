<?php

require('../classes/connection.php');
require('../classes/blog.php');

$connection = new Connection();
$blog = new Blog($connection);

$data['total'] = $blog->getPostTotal();

echo json_encode($data);

?>