<?php
    require('../auth.php');
    $tid = $_POST['id'];
    $like = $obj_admin->like($tid);
     if($like == "liked"){
        $user = $obj_admin->display_one("tweets","tweet_id", $tid);
        $uid = $user['tweet_by'];
        if($uid != $_SESSION['user_id']){
        $notification = $obj_admin->addnotification($uid,$tid,1);
        }
    }
    echo $like;

    ?>