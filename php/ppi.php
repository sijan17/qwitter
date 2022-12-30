<?php
require('../auth.php');
$data = $obj_admin->display("users","user_id");
echo $data;


?>