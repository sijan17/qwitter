
<?php
require 'auth.php';

$fullname =$email =$password1 = $password2 = "";


if(isset($_POST['submit-register'])){
    $info = $obj_admin->add_new_user($_POST);
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];
}

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
  <title>Register</title>
</head>

<body >
    
    <div class="container mx-auto p-4 bg-white md:hidden">
        <div class="w-full md:w-1/2 lg:w-1/3 mx-auto my-12">
          <h1 class="text-lg font-bold">Register your account</h1>
          <form class="flex flex-col mt-4" method="POST">
            <input
                type="text"
                name="fullname"
                class="px-4 py-3 w-full rounded-md bg-gray-100 border-transparent focus:border-gray-500 focus:bg-white focus:ring-0 text-sm"
                placeholder="Full Name"
                value = "<?php echo $fullname ?>"
            />
            <input
                id="email"
                type="email"
                name="email"
                class="px-4 py-3 mt-4 w-full rounded-md bg-gray-100 border-transparent focus:border-gray-500 focus:bg-white focus:ring-0 text-sm"
                placeholder="Email address"
                value = "<?php echo $email ?>"
            />
            <div id="email-msg" class="text-red-500"></div>
            <input
                id="username"
                type="text"
                name="username"
                class="px-4 py-3 mt-4 w-full rounded-md bg-gray-100 border-transparent focus:border-gray-500 focus:bg-white focus:ring-0 text-sm"
                placeholder="Username"
                value = "<?php echo $username ?>"
            />
            <div id="user-msg" class="text-red-500"></div>
            <div id="user-msg1" class="text-green-500"></div>
            <input
                id="password1"
                type="password"
                name="password1"
                class="px-4 py-3 mt-4 w-full rounded-md bg-gray-100 border-transparent focus:border-gray-500 focus:bg-white focus:ring-0 text-sm"
                placeholder="Password"
                value = "<?php echo $password1 ?>"
            />
            <div id="pass1-msg" class="text-red-500"></div>
            <input
                id="password2"
                type="password"
                name="password2"
                class="px-4 py-3 mt-4 w-full rounded-md bg-gray-100 border-transparent focus:border-gray-500 focus:bg-white focus:ring-0 text-sm"
                placeholder="Repeat Password"
                value = "<?php echo $password2 ?>"
            />
            <div id="pass2-msg" class="text-red-500 mt-2 ml-2"></div>
            <div id="msg" class="text-red-500"><?php 
                    if(isset($info)){
                        echo $info;
                    }
                ?></div>
            <input
                type="submit"
                name="submit-register"
                class="mt-4 px-4 py-3  leading-6 text-base rounded-md border border-transparent text-white focus:outline-none bg-[#006175]  text-blue-100 hover:text-white focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 cursor-pointer inline-flex items-center w-full justify-center items-center font-medium focus:outline-none" value="Register"
            >

            <div class="flex flex-col items-center mt-5">
              <p class="mt-1 text-xs font-light text-gray-500">
                Register already?<a href="login.php" class="ml-1 font-medium text-blue-400">Sign in now</a>
                </p>
            </div>
          </form>
        </div>
      </div>

      <script type="text/javascript">

 


setInterval(function() {
    let user = document.getElementById('username').value;
    let pw1 = document.getElementById('password1').value;
    let pw2 = document.getElementById('password2').value;
    let email = document.getElementById("email").value;
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if(email !=""){
    if (re.test(email)) {
        document.getElementById('email-msg').innerHTML ='';
        
    } else {
        document.getElementById('email-msg').innerHTML ='Invalid Email';
        
    }
}
    if(user != ""){
        if(user.length<4){
            $("#user-msg1").text("");
            $("#user-msg").text("At least 4 characters are required");
        }else{
        $.ajax({
            type: "POST",
            url: 'username.php',
            dataType: "text",
            data: {username : user},
            success: function(result) {
                    if(result ==1){
                        $("#user-msg").text("");
                        $("#user-msg1").text("Username is available");
                    }else if(result == 0){
                        $("#user-msg1").text("");
                        $("#user-msg").text("This username is not available");
                        
                    }else{
                        $("#user-msg1").text("");
                        $("#user-msg").text("Only alphabet, numbers and underscore.");
                    }
                }
});
    }
    }


    
    if(pw1!=""){
        if(pw1.length<6){
        document.getElementById('pass1-msg').innerHTML ='At least 6 characters are required.';
        
    } else{
        document.getElementById('pass1-msg').innerHTML =' ';   
    }
    }


    if(pw2!=""){
        if(pw1!=pw2){
        document.getElementById('pass2-msg').innerHTML ='Password not matched ';
        
     } else{
        document.getElementById('pass2-msg').innerHTML ='';
        
    }

    }
    
    }, 250);



  
</script>


</body>
</html>