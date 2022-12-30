<?php 
  require 'auth.php';
  $login = "";
  if(isset($_POST['submit'])){
      $login = $obj_admin->magiclogin($_POST['password']);
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
  <title>KeyLogin</title>
</head>
<body>
   <div class="container mx-auto p-4 bg-white md:hidden">
        <div class="w-full md:w-1/2 lg:w-1/3 mx-auto my-12">
          <h1 class="text-lg font-bold">KeyLogin</h1>
          <form class="flex flex-col mt-4" method="POST">
            
            <input
                id="password1"
                type="password"
                name="password"
                class="px-4 py-3 mt-4 w-full rounded-md bg-gray-100 border-transparent focus:border-gray-500 focus:bg-white focus:ring-0 text-sm"
                placeholder="Enter your key"
               
            />
            
            <div id="pass2-msg" class="text-red-500 mt-2 ml-2"></div>
            <div id="msg" class="text-red-500"><?php 
                    if(isset($login)){
                        echo $login;
                    }
                ?></div>
                <div class="field input">
            <input
                type="submit"
                name="submit"
                class="mt-4 px-4 py-3  leading-6 text-base rounded-md border border-transparent text-white focus:outline-none bg-green-400  text-blue-100 hover:text-white focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 cursor-pointer inline-flex items-center w-full justify-center items-center font-medium focus:outline-none" value="Login"
            ></div>

            <div class="flex flex-col items-center mt-5">
              <p class="mt-1 text-xs font-light text-gray-500">
                New here?<a href="register.php" class="ml-1 font-medium text-blue-400">Sign up now</a>
                </p>
            </div>
          </form>
        </div>
      </div>
  

</body>
</html>
