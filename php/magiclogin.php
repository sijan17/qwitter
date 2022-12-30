<?php 
    session_start();
    include_once "config.php";
   
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    if(!empty($password)){
        $user_pass = md5($password);
        $sql = mysqli_query($conn, "SELECT * FROM users WHERE password= '{$user_pass}'");
        if(mysqli_num_rows($sql) > 0){
            $row = mysqli_fetch_assoc($sql);
            $enc_pass = $row['password'];
                $status = "Active now";
                $sql2 = mysqli_query($conn, "UPDATE users SET status = '{$status}' WHERE unique_id = {$row['unique_id']}");
                if($sql2){
                    $_SESSION['unique_id'] = $row['unique_id'];
                    echo "success";
                                    }else{
                    echo "Something went wrong. Please try again!";
                }

        }else{
            echo "Invalid key, try again.";
        }
    }else{
        echo "All input fields are required!";
    }
?>