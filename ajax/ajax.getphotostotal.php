<?php

require('../classes/connection.php');
require('../classes/photo.php');

$connection = new Connection();
$photo = new Photo($connection);

$data['total'] = $photo->getPhotoTotal();

echo json_encode($data);

?>