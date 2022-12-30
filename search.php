<?php 
            require('includes/header.php');
            require('auth.php');
            $info = $obj_admin->profile_info();
            $uid = $_SESSION['user_id'];
            $users = $obj_admin->display('users WHERE NOT user_id='.$uid,'user_id');
 ?>
 <title>Users-Search</title>
<body class="text-[14px] font-arial">
    <div class="container max-w-md">
    <div class="search sticky top-0 z-100 bg-white grid grid-cols-10 gap-3 px-4 my-3">
                  <input
                  type="search"
                  class="border-2 border-blue-300 px-2 py-1  rounded-[7px] col-span-9 items-center"
                  id="exampleSearch"
                  placeholder="Search Users"
                />
                <button class="col-span-1"><i class=" fas fa-search relative text-[20px]"></i></button>
      
       </div>
       
   
        <main class="relative text-base px-4">
            
            <div class="follow-list trends mb-20  ">
                <?php 
                // require('php/adduser.php'); 
                foreach($users as $row){
                $user_info = $obj_admin->display_one('profile_info','user_id',$row['user_id']);
                $is_following = $obj_admin->display_onee('follow','follows',"{$row['user_id']} AND user_id={$_SESSION['user_id']}");
                if($is_following['id'] != NULL){
                $iff = '<span id ="follow-'.$row['user_id'].'" class="float-right  px-2 py-0.5 border rounded-full border-black bg-white text-black font-[11px] cursor-pointer hover:text-black hover:bg-white">Following</span>';
                }else{
                $iff = '<span id ="follow-'.$row['user_id'].'" class="float-right  px-2 py-0.5 border rounded-full border-black bg-black text-white font-[11px] cursor-pointer hover:text-black hover:bg-white">Follow</span>';
                 }
        ?> <div class="bg-[#F2F2F2] user flex mb-2 bg-[#F2F2F2] px-3 py-4  rounded-[8px] justify-between" >
                    <div class="flex ">
                          
                            <img class="w-11 h-11 object-cover rounded-full" src="img/users/<?php echo $user_info['picture'];?>" alt="user image" />
                          
                          <div class="right ml-3">
                            <a href="./<?php echo $row['user_username']; ?>">
                              <div class="top flex-auto">
                              <span class="name hover:underline block cursor-pointer"><?php echo $row['user_fullname']; ?></span>
                              <span class="font-thin -mt-2 ">@<?php echo $row['user_username']; ?></span>
                        </div>
                        </a>
                          <div class="bottom">
                              <div class="font-light flex justify-between text-[14px]"><?php echo $user_info['bio']; ?></div>
                          </div>
                          </div>
                        </div>
                           <div class="float-right"><a onclick = "follow(<?php echo $row['user_id']; ?>)"><?php echo $iff ?></a></div>
                        </div>
    <?php } ?>


               
            </div>
           
            
        </main>
        <?php require('includes/footer.php') ?>
     </div>
     </div>
     <script src="js/follow.js"></script>
     <script type="text/javascript">
        $("#search").attr("src","img/mycollection/add-user-active.png");

        function follow(id){
            let follow = $("#follow-"+id).text();
            let uid = id;
            // alert(tid);
            $.ajax({
            type: "POST",
            url: 'php/follow.php',
            dataType: "text",
            data: {user : uid},
            success: function(result) {
                if(result == "followed"){
                    $("#follow-"+id).text("Following");
                    $("#follow-"+id).removeClass('text-white bg-black');
                    $("#follow-"+id).addClass('text-black bg-white');
                    
                }else{
                    $("#follow-"+id).text("Follow");
                    $("#follow-"+id).removeClass('text-black bg-white');
                    $("#follow-"+id).addClass('text-white bg-black');
                    
                }
                    
                }
        });
            }

    </script>
    
</body>
</html>