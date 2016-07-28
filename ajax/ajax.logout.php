<?php

require('../classes/connection.php');
require('../classes/login.php');

$connection = new Connection();
$login = new Login($connection);

$login->endSession();

?>
