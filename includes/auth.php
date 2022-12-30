<?php yy
ob_start();
session_start();
require '../classes/control-class.php';
$obj_admin = new control_class();
// if(!isset($_SESSION['login'])){
// 	header('Location: ../auth/login.php');
// }
