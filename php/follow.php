<?php
    require('../auth.php');
    $uid = $_POST['user'];
    $follow = $obj_admin->follow($uid);
    if($follow == "followed"){
        $notification = $obj_admin->addnotification($uid, 0, 0);
    }
    echo $follow;

    ?>