<?php 
$username = $_POST['username'];
if (!preg_match('#^[A-Za-z0-9_]{3,20}$#s', $username)){
  echo 'inv';
}else{

$host_name='localhost';
$user_name='root';
$password='';
$db_name='social';

		try{
			$connection=new PDO("mysql:host={$host_name}; dbname={$db_name}", $user_name,  $password);
			$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
		$stmt = $connection->prepare("SELECT * FROM users WHERE user_username=:username LIMIT 1");
          if($stmt->execute(array(':username'=>$username)));
          $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
          if($stmt->rowCount() > 0)
          { 	
          	echo false;

		}else{
			echo true;
		}


		} catch (PDOException $message ) {
			echo $message->getMessage();
		}	
}

?>
