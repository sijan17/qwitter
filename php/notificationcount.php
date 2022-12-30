<?php 
require('../auth.php');
$notification = $obj_admin->count("notification","n_to","{$_SESSION['user_id']} AND n_status=0 ");
echo $notification;

?>