<?php 
    session_start();
    require('../auth.php');
    if(isset($_SESSION['user_id'])){
        include_once "config.php";
        $outgoing_id = $_SESSION['user_id'];
        $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
        $output = "";
        $sql = "SELECT * FROM messages LEFT JOIN users ON users.user_id = messages.outgoing_msg_id
                WHERE (outgoing_msg_id = {$outgoing_id} AND incoming_msg_id = {$incoming_id})
                OR (outgoing_msg_id = {$incoming_id} AND incoming_msg_id = {$outgoing_id}) ORDER BY msg_id desc limit 10 ";
        $query = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query) > 0){
            while($row = mysqli_fetch_assoc($query)){
                $time = $obj_admin->time_ago($row['msg_time']);
                
                


                if($row['outgoing_msg_id'] === $outgoing_id){
                    $message= '<div class="outgoing  pr-1 flex">
    <div class="ml-auto float-right">
      <p class="bg-black text-white px-2.5 py-1 rounded-full rounded-tl-[18px] rounded-tr-[18px] rounded-br-[0px] rounded-bl-[18px]">'.$row['msg'].'</p>
      <p class="float-right font-light text-[10px]">'.$time.'</p>
    </div>
  </div>';
  $message.=$output;
        $output = $message;
  // border-radius: 18px 18px 18px 0;
                }else{
        $user_info = $obj_admin->display_one('profile_info','user_id',$incoming_id);
        // echo $user_info['picture'];
    $message= '<div class="incomming flex    rounded-[12px] w-full block">
    <img src="../img/users/'.$user_info['picture'].'" class="w-11 h-11 object-cover rounded-full" alt="">
    <div class="ml-3">
      <p class="bg-green-500  text-white px-2.5 py-1 rounded-tl-[18px] rounded-tr-[18px] rounded-br-[18px] rounded-bl-[0px]">'.$row['msg'].'</p>
      <p class="font-light text-[10px]">'.$time.'</p>
    </div>
  </div>';
        $message.=$output;
        $output = $message;
  
                }
            }
        }else{
            // echo mysqli_error($conn);
            $output .= '<div class="grid h-screen place-items-center text-center p-2 font-light">Say hello :)</div>';
        }
        echo $output;
    }else{
        header("location: ../login.php");
    }

?>