<?php 
ob_start();
session_start();
require 'classes/control-class.php';
$obj_admin = new control_Class();
$auth = $obj_admin->auth();
