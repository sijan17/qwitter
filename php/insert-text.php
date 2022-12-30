<?php 
    session_start();
    if(isset($_SESSION['user_id'])){
        date_default_timezone_set('Asia/Kathmandu');
        include_once "config.php";
        $outgoing_id = intval($_SESSION['user_id']);
        $time = date("Y-m-d H:i:s");
        echo $time;
        $incoming_id = intval(mysqli_real_escape_string($conn, $_POST['incoming_id']));
        
        $message = mysqli_real_escape_string($conn, nl2br($_POST['message']));
        echo $message;
        if(!empty($message)){
            $sql = mysqli_query($conn, "INSERT INTO messages (incoming_msg_id, outgoing_msg_id,msg_time, msg )
                                        VALUES ({$incoming_id}, {$outgoing_id},'{$time}', '{$message}')") or print_r(mysqli_error($conn));
        }
        var_dump($sql);
    }else{
        header("location: ../login.php");
    }


    
?>