<?php 
            require('includes/header.php');
            require('auth.php');
            $uname = $_GET['u'];
            if($uname==NULL || $uname ==$_SESSION['user_username']){
                header('Location: profile.php');
            }
            $u = $obj_admin->display_one("users","user_username",$uname);
            if($u['user_id']==""){
                header('Location: profile.php');
            }else{
                $uid = $u['user_id'];
            }
            $info = $obj_admin->display_one("profile_info","user_id",$uid); 
            $time_ago = $obj_admin->time_ago($info['join_date']);
            if($info['location']==""){
                $location = 'unknown';
            }else{
                $location = $info['location'];
            }
            if($info['birthday']==""){
                $birthday = 'unknown';
            }else{
                $bd = $info['birthday'];
                $time = strtotime($bd);
                $day = intval(date("d", $time));
                $index = intval(date("m", $time));
                $months = array("January","February","March","April","May","June","July", "August","September","October","November","December");
                $month = $months[$index-1];
                $birthday = $month." ".$day;
            }

            $tweets = $obj_admin->display("tweets WHERE tweet_by = {$uid}" ,'tweet_id');
            // $profile = $obj_admin->display_one("profile_info","user_id",$uid); 
            $following = $obj_admin->count("follow","user_id","$uid");
            $followers = $obj_admin ->count("follow","follows","$uid");
            $tw = $obj_admin ->count("tweets","tweet_by","$uid");
            $isfollowing = $obj_admin ->count("follow","follows","{$uid} AND user_id = {$_SESSION['user_id']}");
            

 ?>
<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="css/style.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://kit.fontawesome.com/ef59fa3bdb.js" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Poppins" />
  <link rel="stylesheet" type="text/css" href="../fontawesome/css/all.css">
  <style>
    *{
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }
  </style>
  <title>User Profile</title>
</head>
<body >
    <!-- <div class="h-20 bg-blue-500 w-screen m-0 p-0"></div> -->

    <div class='container p-2 max-w-md'>
    <div class="sticky top-0 bg-white z-100 flex justify-between items-center pb-2">
        <p></p>
        <p class="font-semibold text-[18px] ">Profile</p>
        <p></p>
    </div>

    <div class="center">
        <div class="flex items-center p-3">
            <img src="img/users/<?php echo $info['picture'] ?>" alt="" class="h-[67px] w-[67px] object-cover rounded-full">
            <span class="px-2">
                <p class="font-semibold text-[18px]"><?php echo $u['user_fullname'] ?></p>
                <p class="font-light text-[14px]">@<?php echo $u['user_username'] ?></p>
            </span>
        </div>

        <div class="flex justify-between  px-3 text-[14px]">
            <div>
            <p class="text-[13px] font-light mr-4"><?php echo $info['bio'] ?></p>
            <div class="font-light text-[12px]   mt-2 grid grid-cols-2"> 
                <span class="col-span-2"><i class="fa-solid fa-location-dot"></i> <a class="ml-1"><?php echo $location; ?></a></span>    
                <span ><i class="fa-solid fa-cake-candles"></i> <a class="ml-1"><?php echo $birthday ?></a></span>
                <span class="ml-3"><i class="fa-solid fa-calendar-days"></i> <a class="ml-1"><?php echo $time_ago ?></a></span>
            </div>
            </div>
            <span class="shrink-0">
                <div class="flex items-center">
                <span class=""><a href="qtext/<?php echo $uid ?>"><i class="fa-regular fa-message border border-black rounded-full px-3 py-1 text-[16px]"></i></a></span>
                <span id="follow" class="float-right  border border-black rounded-full px-3 py-1.5 ml-4 bg-[#006175] text-white text-[14px] cursor-pointer">
                <input type="hidden" id="user_id" value="<?php echo $uid ?>">
                <a id="f-u" class="" ><?php $msg= $isfollowing==1? "Following" :  "Follow" ; echo $msg; ?></a>
                </span>
            </div>
            </span>   
        </div>
        
        


        <div class="grid grid-cols-3 text-center my-4">
            <span class="border-r border-black">
                <p class="font-semibold"><?php echo $tw ?></p>
                <p>Tweets</p>
            </span>
            <span class="border-r border-black">
                <p class="font-semibold"><a id="following"><?php echo $following ?></a></p>
                <p>Following</p> 
            </span>
            <span>
                <p class="font-semibold"><a id="followers"><?php echo $followers ?></a></p>
                <p>Followers</p>
            </span>
        </div>

        <div class="tweets">
        <p class="text-[14px] font-semibold mx-3 py-2 border-t border-gray-400">Tweets</p>
    

        <?php
        if ($tweets[0]['tweet_id']=="") { ?>
             <div class="p-3" >This user has no tweets :(  </div>
        <?php }else{
             foreach($tweets as $t){ 
                $likes = $obj_admin->count("likes","tweet_id",$t['tweet_id']);
                $msg = $likes == 0 ? "Be the first one to like" : "";
                $isliked = $obj_admin ->count("likes","tweet_id","{$t['tweet_id']} AND liked_by = {$_SESSION['user_id']}");
                $color = $isliked>0 ? "text-red-500" : "";
                $user = $obj_admin->display_one("users", "user_id", $t['tweet_by']);
                $user_p =  $obj_admin->display_one("profile_info", "user_id", $user['user_id']);
                // die($user_p['picture']);
        
        
                   ?>

                  <div class="postarea bg-[#F2F2F2] my-3 p-3 rounded-[8px]">
                    <div class="flex ">
                        <div class="img shrink-0">
                            <img src="img/users/<?php echo $user_p['picture'] ?>"  class="w-11 h-11 object-cover rounded-full" alt="">
                        </div>

                        <div class="content pl-3 w-screen">
                            <a href="user.php?u=<?php echo $user['user_id'] ?>">
                             <span class="font-bold hover:underline"><?php echo $user['user_fullname'] ?></span> 
                            </a>
                            <span class="font-thin pl-1">@<?php echo $user['user_username'] ?></span>
                            <span class="font-thin text-[13px] pl-2 float-right"><?php echo $obj_admin->time_ago($t['tweet_time']); ?></span>

                            <p class="font-light"><?php echo $t['tweet_content']; ?></p>

                            <div class="react-section">

            <span>
                <span class="<?php echo $color ?> text-[20px] float-right" id="toggle-<?php echo $t['tweet_id'] ?>"  class="">
                    <i id="icon-<?php echo $t['tweet_id'] ?>" class="fa-regular fa-heart mr-2 " 
                    onclick="like('<?php echo $t['tweet_id'] ?>')"></i>
                    <a id="likecount"><span id="likes-<?php echo $t['tweet_id'] ?>"><?php echo $likes; ?></span></a>
                </span>
                    <a class="text-[12px] font-thin ml-3" id="msg-<?php echo $t['tweet_id'] ?>"><?php echo $msg ?></a>
             </span>
  </div>
                        </div>
                    </div>
                </div>


            <?php } }?>
            </div>
        
    </div>
   
       </div>


<?php require('includes/footer.php') ?>
<script type="text/javascript">
    	$("#profile").addClass('active');

        $("#follow").click(function(){
            let flrs = $("#followers").text();
            // alert(flrs);
            fl =parseInt(flrs);
            let uid = $("#user_id").val();
            // alert(uid);
            $.ajax({
            type: "POST",
            url: 'php/follow.php',
            dataType: "text",
            data: {user : uid},
            success: function(result) {
                if(result == "followed"){
                    $("#f-u").text("Following");
                    $("#followers").text(fl+1);
                }else{
                    $("#f-u").text("Follow");
                    $("#followers").text(fl-1);
                }
                    
                }
        });
        });

        function like(id){
                let likes = $("#likes-"+id).text();
            // alert(flrs);
            lks =parseInt(likes);
            let tid = id;
            // alert(tid);
            $.ajax({
            type: "POST",
            url: 'php/like.php',
            dataType: "text",
            data: {id : tid},
            success: function(result) {
                if(result == "liked"){
                    if(lks==0){
                        $("#msg-"+id).addClass("text-red-500");
                        $("#msg-"+id).text("Always number one !");                        
                    }
                    $("#likes-"+id).text(lks+1);
                    $("#toggle-"+id).addClass('text-red-500');
                }else{
                    if(lks==1){
                        $("#msg-"+id).removeClass("text-red-500");
                        $("#msg-"+id).text("Be the first one to like !");                        
                    }
                    $("#likes-"+id).text(lks-1);
                    $("#toggle-"+id).removeClass('text-red-500');
                }
                    
                }
        });
            }
    </script>
</body>
</html>