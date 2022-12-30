<?php
    require('../auth.php');
    while($row = mysqli_fetch_assoc($query)){
        $user_info = $obj_admin->display_one('profile_info','user_id',$row['user_id']);
        $is_following = $obj_admin->display_one('follow','follows',"{$row['user_id']} AND user_id={$_SESSION['user_id']}");
        if($is_following['id'] != NULL){
            $iff = '<span class="float-right  px-2 py-0.5 border rounded-full border-black bg-white text-black font-[11px] cursor-pointer hover:text-black hover:bg-white">Following</span>';
        }else{
            $iff = '<span class="float-right  px-2 py-0.5 border rounded-full border-black bg-black text-white font-[11px] cursor-pointer hover:text-black hover:bg-white">Follow</span>';
        }
        $output .='<div class="bg-[#F2F2F2] user flex mb-2 bg-[#F2F2F2] px-3 py-4  rounded-[8px] justify-between" >
                    <div class="flex ">
                          
                            <img class="w-11 h-11 object-cover rounded-full" src="img/users/'.$user_info['picture'].'" alt="user image" />
                          
                          <div class="right ml-3">
                            <a href="./'.$row['user_username'].'">
                              <div class="top flex-auto">
                              <span class="name hover:underline block cursor-pointer">'.$row['user_fullname'].'</span>
                              <span class="font-thin -mt-2 ">@'.$row['user_username'].'</span>
                        </div>
                        </a>
                          <div class="bottom">
                              <div class="font-light flex justify-between text-[14px]">'.$user_info['bio'].'</div>
                          </div>
                          </div>
                        </div>
                           
                        </div>';
    }
    // echo mysqli_error($conn);
    // <div class="float-right"><a onclick = "follow('.$row['user_id'].')">'.$iff.'</a></div>
?>