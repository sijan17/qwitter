<?php
    session_start();
    include_once "config.php";
    $outgoing_id = $_SESSION['user_id'];
    $a = array(10);

    $sql1 = "SELECT * FROM follow WHERE user_id = {$outgoing_id}";
    $query1 = mysqli_query($conn, $sql1);
    while($row1 = mysqli_fetch_assoc($query1)){
         array_push($a,$row1['follows']);
    }
    $array = implode(",",$a);
    
    $sql = "SELECT * FROM users WHERE user_id IN ({$array}) ORDER BY user_id DESC";
    $query = mysqli_query($conn, $sql);
    $output = "";
    if(mysqli_num_rows($query) == 1){
        $output .= "<p class = ' font-[13px] font-light'>Follow users and start conversation.</p>";
        include_once "data.php";
    }elseif(mysqli_num_rows($query) > 0){
        include_once "data.php";
    }
    echo $output;
?>