<?php
            require('auth.php');
            $uid = $_GET['u'];
            $user_info = $obj_admin->display_one('users','user_id',$uid);
            $outgoing_id = $_SESSION['user_id'];
            $incoming_id = $obj_admin->sanitize_input_data($uid);
            $data = $obj_admin->display("messages LEFT JOIN users ON users.user_id = messages.outgoing_msg_id
                WHERE (outgoing_msg_id = {$outgoing_id} AND incoming_msg_id = {$incoming_id})
                OR (outgoing_msg_id = {$incoming_id} AND incoming_msg_id = {$outgoing_id})", "msg_id") ;
            // var_dump($data);

 ?>
 <!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="../css/style.css">
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
  <title>Text - <?php echo $user_info['user_fullname'] ?></title>
</head>
<body class="px-2 bg-[#F7F9F9]">
<div class="container max-w-md">
<div class="header flex justify-between sticky top-0 z-10 bg-white py-2 px-3">
    <div class="bg-white h-10 flex justify-between items-center" >
        <a href="/qwitter/message"><i class="fa fa-arrow-left text-2xl" aria-hidden="true"></i></a>
        <div class="ml-6">
            <p class="font-bold"><?php echo $user_info['user_fullname']; ?> </p>
            <p class="font-light text-[12px]">@<?php echo $user_info['user_username']; ?></p>
        </div>
    </div>
    <i class="fa-solid fa-circle-info text-2xl"></i>
</div>


<main class="chat-box relative text-base mb-[70px] mx-2 mt-2" id="body">

<div class="flex items-center justify-center space-x-2 animate-bounce">
        <div class="w-4 h-4 bg-blue-400 rounded-full"></div>
        <div class="w-4 h-4 bg-green-400 rounded-full"></div>
        <div class="w-4 h-4 bg-black rounded-full"></div>
    </div>


</main>

<footer class=" pt-2 pb-4 z-10 max-w-md bg-white fixed w-screen inset-x-0 bottom-0 px-5 font-bold text-2xl">
    <!-- <div> -->
    <form action="#" class="typing-area">
    <div class="flex justify-between items-center">
    <span onclick="lockk()"><i class="fa-solid fa-lock" id="lock"></i></span>
    <span class="mx-3">
      <input type="text" class="incoming_id" name="incoming_id" value="<?php echo $uid; ?>" hidden>
        <textarea
                rows =1
                id="message"
                  type="text"
                  name="message"
                  class="input-field
                  w-full
                  resize-none
                    form-control
                    px-3
                    py-1.5
                    text-base
                    font-normal
                    text-gray-700
                    bg-white bg-clip-padding
                    transition
                    ease-in-out
                    mx-2
                    border-b border-solid border-black
                    focus:outline-none
                  "
                  autocomplete="false"
                  onclick="scroll()" 
                  placeholder="Start a message"
                ></textarea>
    </span>
    <button id="send"><i class="fa-brands fa-telegram"></i></button>
    </div>
  </form>
    </footer>

    </div>
    <script type="text/javascript" src="/qwitter/js/text.js"></script>
    <script type="text/javascript">
        let lock=0;
        function lockk(){
            if(lock == 0){
                lock =1;
                document.getElementById("lock").style.color='red';
                // alert("locked");
            }else{
                lock = 0;
                document.getElementById("lock").style.color='black';
                // alert("unlocked");
            }

        }


            // function scroll(){
            //     $("html, body").animate({
            //         scrollTop: $(
            //           'html, body').get(0).scrollHeight
            //     }, 2000);
            // }

            scroll();



    </script>
</body>
</html>