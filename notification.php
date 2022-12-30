<?php 
            require('includes/header.php');
            require('auth.php');


            $notification = $obj_admin->display("notification where n_to= {$_SESSION['user_id']}","n_id");       
            $stat = $obj_admin->update("notification", "n_status=1","n_to" ,$_SESSION['user_id']);
 ?>
 <title>Notification</title>
 <body>
 <div class='container p-3 max-w-md'>
    <div class="sticky top-0 bg-white z-100 flex justify-between items-center pb-3">
        <p></p>
        <p class="font-semibold text-[18px] ">Notifications</p>
        <p></p>
    </div>

    <div class="notification-list trends mb-20  ">

<?php 
  foreach ($notification as $n ) {


    $user = $obj_admin->display_one("users","user_id",$n['n_by']);
    $user_info = $obj_admin->display_one("profile_info", "user_id",$n['n_by']);

    $name = $user['user_fullname'];
    if($n['n_by']==34){
        $name = "Welcome!";
    }

    if ($n['n_status'] == 0) {
      $status = "bg-[#F2F2F2]";
    }else{
      $status = "";
    }

  if ($n['n_type'] == 0) {
      $msg = "followed you.";
      $link = "/qwitter/".$user['user_username'];
    }elseif ($n['n_type'] == 1) {
      $msg = "liked one of your tweet.";
    }elseif ($n['n_type'] == 10) {
      $msg = "<br>We are glad to see you here.";
    }else{
      $msg = "invalid notification";
    }
    $time_ago = $obj_admin->time_ago($n['n_time']);
  ?>
    <a href="<?php echo $link ?>">
    <div class=" mb-2 <?php echo $status ?> py-4 px-2 rounded-[8px] justify-between" >
    <div class="flex ">
                          
                            <img class="w-11 h-11 object-cover rounded-full" src="img/users/<?php echo $user_info['picture'] ?>" alt="user image" />
                          
                          <div class="right ml-3">
                            
                              <div class="top flex-auto">
                              <span class="name hover:underline block cursor-pointer"><b> <?php echo $name; ?></b> <?php echo $msg; ?></span>
                              <span class="font-thin text-[14px] "><?php echo $time_ago; ?></span>
                        </div>
                          </div>
                     </div>                   
</div>
</a>

<?php } ?>


</div>
</div>


    <?php require('includes/footer.php') ?>
     <script type="text/javascript">
         $("#notification").attr("src","img/mycollection/more-active.png");

        setInterval(function(){
          $.ajax({
            type: "POST",
            url: 'php/notificationcount.php',
            dataType: "text",
            data: {},
            success: function(result) {
                $("#ntf").text(result);         
                }
        });
        },2000);


    </script>
 </body>
