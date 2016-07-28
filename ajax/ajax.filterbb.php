<?php

require('../classes/bbcode.php');

$bb = new BBCode();
	
$data['string'] = $bb->filter($_POST['string']);

echo json_encode($data);

?>