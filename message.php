<?php 
            require('includes/header.php');
            require('auth.php');
            $info = $obj_admin->profile_info();
            $user_id = $_SESSION['user_id'];
            $users = $obj_admin->display("users where NOT user_id = {$user_id}",'user_id')
 ?>
<title>Messages</title>
<body class="">
    <div class=" max-w-md">
        <div class="search sticky top-0 z-100 bg-white grid grid-cols-10 gap-3 px-4 my-3">
                  <input
                  type="search"
                  class="border-2 border-blue-300 px-2 py-1  rounded-[7px] col-span-9 items-center"
                  id="exampleSearch"
                  placeholder="Search Direct Messages"
                />
                <button class="col-span-1"><i class=" fas fa-search relative text-[20px]"></i></button>
      
       </div>

        <main class="users-list relative text-base px-4  mb-20">
            
<div class="messages mt-20 ">

   <div class="flex items-center justify-center space-x-2 animate-bounce">
        <div class="w-4 h-4 bg-blue-400 rounded-full"></div>
        <div class="w-4 h-4 bg-green-400 rounded-full"></div>
        <div class="w-4 h-4 bg-black rounded-full"></div>
    </div>
          
          </div>
               
            </div>
            
        </main>
        <?php require('includes/footer.php') ?>
     </div>
     <script type="text/javascript" src="js/message.js"></script> 
     <script type="text/javascript">
    	$("#message").attr("src","img/mycollection/messenger-active.png");
    </script>
    
</body>
</html>