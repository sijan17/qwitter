<?php 
            require('includes/header.php');
            require('auth.php');
            $info = $obj_admin->profile_info();
            $time_ago = $obj_admin->time_ago($info['join_date']);
            
            // die($info);
            $tweet = "";
            if(isset($_POST['tweet'])){
                $tweet = $obj_admin->tweet($_POST);
            }
            $tweets = $obj_admin->display('tweets','tweet_id');

            $uid =$_SESSION['user_id'];

 ?>
 <title>Home</title>
<body>
<div class='container p-3 max-w-md'>
        <div class="navbar sticky grid grid-cols-10 gap-3">
           <input type="search" placeholder="Type something..." class="border-2 border-blue-300 px-2 py-1  rounded-[7px] col-span-9 items-center" />
           <a href="notification"><span><img src="img/mycollection/notification.png" class="w-8 py-1"/><span id="n-count" class="hidden w-5 h-5 absolute top-0 -right-1 rounded-full text-center z-100 bg-red-500 text-white font-bold font-[14px]"></span></span></a>
        <!--
           <img src="img/mycollection/notification.png" class="w-8 py-1"/>    -->
       </div>

       <div class="tweet my-3">
         <div class=" relative story w-full h-28 mr-3">
         <form method="POST">
         <textarea name="content" class="resize-none w-full h-28 px-3 py-2 bg-[#F2F2F2] border-[0.5px] border-black rounded-[7px] focus:border-[1px] focus:border-[#006175] focus:outline-none" placeholder="What's happening ?"></textarea>
               <input type="submit" name="tweet" class="w-16 text-center text-white rounded-[4px] absolute bottom-1 right-1 z-10 bg-[#006175] py-1 " value="Tweet">
               </form>
               
           </div>

    <span class="mt-2"><?php echo $tweet; ?></span>
       </div>
   
   <!--
       <div class="stories py-3 flex ">
           <div class="bg-[#F2F2F2] relative story w-20 h-28 border border-black rounded-[7px] mr-3">
               <div class="w-8 h-8 border border-black rounded-full absolute -bottom-4 left-6 z-10 bg-white"><img src="img/mycollection/plus.png"  class="p-2"/></div>
           </div>
   
           <div class="relative story w-20 h-28 border border-black rounded-[7px] mr-3">
               <div class="w-8 h-8 border border-black rounded-full absolute -bottom-4 left-6 z-10 bg-white"></div>
           </div>
   
           <div class="relative story w-20 h-28 border border-black rounded-[7px] mr-3">
               <div class="w-8 h-8 border border-black rounded-full absolute -bottom-4 left-6 z-10 bg-white"></div>
           </div>
   
           <div class="relative story w-20 h-28 border border-black rounded-[7px] mr-3">
               <div class="w-8 h-8 border border-black rounded-full absolute -bottom-4 left-6 z-10 bg-white"></div>
           </div>
   </div>
   -->

        <main class="relative text-base">
            
            <div class="main mb-20">

        <?php foreach($tweets as $t){ 
        $likes = $obj_admin->count("likes","tweet_id",$t['tweet_id']);
        $msg = $likes == 0 ? "Be the first one to like" : "";
        $isliked = $obj_admin ->count("likes","liked_by","$uid AND tweet_id = {$t['tweet_id']}");
        // echo $isliked;
        $color = $isliked > 0 ? "text-red-500" : "";
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
                            <a href="/qwitter/<?php echo $user['user_username'] ?>">
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
  
            <?php } ?>


            </div>
            

     </div>
    <?php include('includes/footer.php'); ?>
    <script type="text/javascript">
    	$("#home").attr("src","img/mycollection/home-active.png");

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


        setInterval(function(){
          $.ajax({
            type: "POST",
            url: 'php/notificationcount.php',
            dataType: "text",
            data: {},
            success: function(result) {
                if(result>0){
                $("#n-count").removeClass('hidden');
                $("#n-count").addClass('block');
                $("#n-count").text(result);  
                }       
                }
        });
        },2000);

    </script>
</body>
</html>