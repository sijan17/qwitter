<?php 
            require('includes/header.php');
            require('auth.php');
            $info = $obj_admin->profile_info();
            $time_ago = $obj_admin->time_ago($info['join_date']);
            if(!isset($info['location'])){
                $location = 'unknown';
            }else{
                $location = $info['location'];
            }
            if(!isset($info['birthday'])){
                $birthday = 'unknown';
            }else{
                $birthday = $info['birthday'];
            }

            if(isset($_POST['submit-file'])){
            $change = $obj_admin->changeImage($_FILES['file']);
            die($change);
            }
            if (isset($_POST['submit-form'])) {
                $update = $obj_admin->updateProfile($_POST);
            }

            $name = $obj_admin->display_one("users" ,'user_id', $_SESSION['user_id']);
            $profile = $obj_admin->display_one("profile_info" ,'user_id', $_SESSION['user_id']);

            $time = strtotime($profile['birthday']);
             $bd = date("Y-m-d", $time);

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
 
</head>
<body class=" px-2" >
<div class='container p-3 max-w-md'>
<div class="sticky top-0 bg-white z-100 flex justify-between items-center pb-3">
        <a href="profile"><i class="fa fa-arrow-left text-2xl" aria-hidden="true"></i></a>
        <p class="font-semibold text-[18px] ">Edit Profile</p>
        <p></p>
    </div>
    

    <div class="head2 flex justify-between items-center ">
        <div class="left w-full">
        
        
        <label >
            <img id="imageid" class="cursor-pointer rounded-full border border-gray-100 shadow-sm h-20 w-20  opacity-60" src="img/users/<?php echo $info['picture'] ?>" alt="user image" />
            <i class="fa-solid fa-image relative -top-[55px] -right-[25px] text-[30px]  "></i>
            
        <form method="post" name="uploadImage" enctype="multipart/form-data">
            <!-- <span class="mt-2 text-base leading-normal">Select a file</span> -->
            <input id="form-img" type="file" name="file" class="hidden" onchange="uploadImg()">
        </label>
       <span style="display:none"><input type="submit" id="submit-form" name="submit-file" value="Change"></span>
       <p class="font-light relative -top-[20px] text-[12px]">Select a photo of ratio 1:1 (recommended) </p>
       <span class="mt-4 w-full text-white font-semibold bg-red-400 px-1 py-1  rounded-[7px]"><a onclick="return confirm('Are you sure you want to logout?')" href="logout.php" >Log Out</a></span>
    </form>


    <form class="mt-4" method="POST" name="info">
        <label class="block font-light">Name</label>
        <input type="text" name="name" class="w-full bg-[#F2F2F2] focus:outline-none px-2 py-3 rounded-[7px]" value="<?php echo $name['user_fullname']; ?>">

         <label class="block mt-3 font-light">Bio</label>
        <textarea row="4" class="w-full bg-[#F2F2F2] focus:outline-none px-2 py-3 rounded-[7px] resize-none" name="bio"><?php echo $profile['bio']; ?></textarea> 

        <label class="block font-light mt-3 ">Location</label>
        <input type="text" name="location" class="w-full bg-[#F2F2F2] focus:outline-none px-2 py-3 rounded-[7px]" value="<?php echo $profile['location']; ?>">

        <label class="block font-light mt-3">Birth Date</label>
        <input type="date" name="bday" class="w-full bg-[#F2F2F2] focus:outline-none px-2 py-3 rounded-[7px]" value="<?php echo $bd; ?>">

        <input type="submit" name="submit-form" value="Update" class="mt-4 w-full text-white font-semibold bg-[#006175] focus:outline-none px-2 py-3 rounded-[7px]">

         
    </form>
    </div>
<script type="text/javascript">
    function uploadImg(){
        file = document.getElementById('form-img').value;
        if(file != ""){
            document.getElementById('submit-form').click();

        }
    }


    $("#profile").addClass('active');
    </script>
    
</body>
</html>