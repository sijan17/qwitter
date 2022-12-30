<?php
    require('../auth.php');
    while($row = mysqli_fetch_assoc($query)){
        $sql2 = "SELECT * FROM messages WHERE (incoming_msg_id = {$row['user_id']}
                OR outgoing_msg_id = {$row['user_id']}) AND (outgoing_msg_id = {$outgoing_id} 
                OR incoming_msg_id = {$outgoing_id}) ORDER BY msg_id DESC LIMIT 1";
        $query2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($query2);
        (mysqli_num_rows($query2) > 0) ? $result = $row2['msg'] : $result ="Tap to start conversation.";
        (strlen($result) > 28) ? $msg =  substr($result, 0, 28) . '...' : $msg = $result;
        if(isset($row2['outgoing_msg_id'])){
            ($outgoing_id == $row2['outgoing_msg_id']) ? $you = "You: " : $you = "";
        }else{
            $you = "";
        }
        ($row['status'] == "Offline now") ? $offline = "offline" : $offline = "";
        ($outgoing_id == $row['unique_id']) ? $hid_me = "hide" : $hid_me = "";
        $time = $obj_admin->time_ago($row2['msg_time']);
        $user_info = $obj_admin->display_one('profile_info','user_id',$row['user_id']);
        $output .= '<div class="message flex  bg-[#F2F2F2] mb-2 px-3 py-4 rounded-[8px] cursor-pointer" >
                    <div class="left">
                    <a href="/qwitter/'.$row['user_username'].'"><img class="w-11 h-11 object-cover rounded-full" src="img/users/'.$user_info['picture'].'" alt="user image" /></a>
                    </div>
                    <div class="right ml-3">
                        <div class="top">
                        <a href="/qwitter/'.$row['user_username'].'"><span class="name hover:underline">'.$row['user_fullname'].'</span>
                        <span class="font-thin">@'.$row['user_username'].'</span>
                        <span class="font-thin text-[13.5px]"> . '.$time.'</span>
                    </div>
                    <a href="/qwitter/qtext/'.$row['user_id'].'">
                    <div class="bottom w-full">
                        <p class="font-light">'. $you . $msg .'</p>
                    </div>
                </a>
                    </div>
              </div>';
    }
    echo mysqli_error($conn);
?>