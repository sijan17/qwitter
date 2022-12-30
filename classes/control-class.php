<?php


class control_class
{	

// -------------------------Set Database Connection----------------------

	public function __construct()
	{ 
        date_default_timezone_set('Asia/Kathmandu');
        $host_name='localhost';
		$user_name='root';
		$password='';
		$db_name='social';

		try{
			$connection=new PDO("mysql:host={$host_name}; dbname={$db_name}", $user_name,  $password);
			$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->db = $connection; 
		} catch (PDOException $message ) {
			echo $message->getMessage();
		}
	}



   // ---------------------- sanitize_input_data ----------------------------
	
	public function sanitize_input_data($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data); 
		return $data;
	}



	// -------------------------Auth--------------------------------------------

	public function auth(){
		if(!isset($_SESSION['login'])){
			if(isset($_COOKIE['s_session'])){
				$session = $_COOKIE['s_session'];
				$sql = "SELECT * FROM users WHERE session=? ";
				$stmt = $this->db->prepare($sql);
				$stmt->execute([$session]);
				$userRow = $stmt->fetch();
				if($userRow['user_id']!=NULL){
                
				$_SESSION['login'] = true;
	            $_SESSION['user_id'] = $userRow['user_id'];
	            $_SESSION['user_fullname'] = $userRow['user_fullname'];
	            $_SESSION['user_email'] = $userRow['user_email'];
	            $_SESSION['user_username'] = $userRow['user_username'];
	            
				}else{
					header('Location: auth/login');
				}
			}else{
					header('Location: auth/login');
				}
		}
	}

    // ----------------------Register New user -------------------------------

	public function add_new_user($data){
		$user_name = $this->sanitize_input_data($data['fullname']);
		$user_email = $this->sanitize_input_data($data['email']);
		$password1 = md5($this->sanitize_input_data($data['password1']));
		$password2 = md5($this->sanitize_input_data($data['password2']));
		$username = $this->sanitize_input_data($data['username']);
        $username = strtolower($username);

		if($user_name != NULL && $user_email !=NULL && $password1 != NULL){
		if($password1 !== $password2){
			$msg = "Passwords don't match.";
		}else{
          $stmt = $this->db->prepare("SELECT * FROM users WHERE user_email=:email LIMIT 1");
          if($stmt->execute(array(':email'=>$user_email)));
          $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
          if($stmt->rowCount() > 0)
          { 	
          	$msg = "Email already taken.";

		}else{

			$stmt1 = $this->db->prepare("SELECT * FROM users WHERE user_username=:username LIMIT 1");
          if($stmt1->execute(array(':username'=>$username)));
          $userRow=$stmt1->fetch(PDO::FETCH_ASSOC);
          if($stmt1->rowCount() > 0)
          { 	
          	$msg = "Username is not available.";

		}else{
				$add_user = $this->db->prepare("INSERT INTO users (user_fullname, user_email, user_password,user_username) VALUES (:x, :y, :z, :a) ");
				$add_user->bindparam(':x', $user_name);
				$add_user->bindparam(':y', $user_email);
				$add_user->bindparam(':z', $password1);
				$add_user->bindparam(':a', $username);

				

				if($add_user->execute()){
                $user = $this->display_one("users","user_username",$username);
                $uid = $user['user_id'];
                $_SESSION['user_id_temp'] = $uid;
                $this->profile_info();
                $this->addnotification($uid, 0,10);
				setcookie('signupsuccess',true,time()+86400*2);
				header('Location: success.php');	
				}
				else{
					$msg = "Error while inserting your info ";
				}			


		}
	}
		
	}
	}else{
			$msg = "All fields are required";
		}
		return $msg;
	}

	


// --------------------------Login Validation-------------------------

	public function validate_user($data) {
      $user_password = $this->sanitize_input_data(md5($data['password1']));
		$user_email = $this->sanitize_input_data($data['email']);
		
        try
       {
          $stmt = $this->db->prepare("SELECT * FROM users WHERE user_email=:email AND user_password=:password LIMIT 1");
          $stmt->execute(array(':email'=>$user_email, ':password'=>$user_password));
          $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
          if($stmt->rowCount() > 0)
          {
          		session_start();
          		$sess = bin2hex(random_bytes(20));
          			setcookie('s_session',$sess,time()+86400*30, "/","", 0);
          			$sql = "UPDATE users SET session=? WHERE user_id=?";
						$stmt = $this->db->prepare($sql);
						$stmt->execute([$sess, $userRow['user_id']]);
          		$_SESSION['login'] = true;
	            $_SESSION['user_id'] = $userRow['user_id'];
	            $_SESSION['user_fullname'] = $userRow['user_fullname'];
	            $_SESSION['user_email'] = $userRow['user_email'];
	            $_SESSION['user_username'] = $userRow['user_username'];

	            setcookie('signupsuccess',false,time()+10); 
				header('Location: ../');
		}else{
			$msg = "Invalid email or password.";
		}
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }
       return $msg;	
		

    }

    //------------Magic Login---------
    public function magiclogin($data){
        $key = md5($this->sanitize_input_data($data));
        try
       {
          $stmt = $this->db->prepare("SELECT * FROM users WHERE user_password=:key AND magiclogin=1 LIMIT 1");
          $stmt->execute(array('key'=>$key));
          $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
          if($stmt->rowCount() > 0 ){

          		session_start();
          		$sess = bin2hex(random_bytes(20));
          			setcookie('s_session',$sess,time()+86400*30, "/","", 0);
          			$sql = "UPDATE users SET session=? WHERE user_id=?";
						$stmt = $this->db->prepare($sql);
						$stmt->execute([$sess, $userRow['user_id']]);
          		$_SESSION['login'] = true;
	            $_SESSION['user_id'] = $userRow['user_id'];
	            $_SESSION['user_fullname'] = $userRow['user_fullname'];
	            $_SESSION['user_email'] = $userRow['user_email'];
	            $_SESSION['user_username'] = $userRow['user_username'];

	            setcookie('signupsuccess',false,time()+10); 
				header('Location: ../');
		}else{
			$msg = "That's not a correct key.";
		}
       }
       catch(PDOException $e)
       {
           $msg = $e->getMessage();
       }
       return $msg;

    }


    // ----------------Profile info ---------------------

    public function profile_info(){
        if(isset($_SESSION['user_id'])){
    	$user_id =  $_SESSION['user_id'];
        }else{
            $user_id =  $_SESSION['user_id_temp'];
        }

        date_default_timezone_set('Asia/Kathmandu');
    	$time = date("Y-m-d H:i:s");
    	$stmt = $this->db->prepare("SELECT * FROM profile_info WHERE user_id=:user_id  LIMIT 1");
      $stmt->execute(array(':user_id'=>$user_id));
      $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
      if($userRow == ""){
      		$add_user = $this->db->prepare("INSERT INTO profile_info (user_id, join_date) VALUES (:x,:y) ");
				$add_user->bindparam(':x', $user_id);
				$add_user->bindparam(':y', $time);
				$add_user->execute();
    	$stmt = $this->db->prepare("SELECT * FROM profile_info WHERE user_id=:user_id  LIMIT 1");
      $stmt->execute(array(':user_id'=>$user_id));
      $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
      }
      return $userRow;
    }

// ---------------------Time Ago----------------------------


public function time_ago($timestamp){

	date_default_timezone_set('Asia/Kathmandu');
    $time_ago = strtotime($timestamp);
    $current_time = time();
    $time_difference = $current_time - $time_ago;
    $seconds = $time_difference;
   
    
    $minutes = round($seconds/60);
    $hours = round($seconds/3600);
    $days = round($seconds/86400);  
    $weeks = round($seconds/604800);
    $months = round($seconds/2629400);

    if($seconds<=60){
        return "Just now";
        
    }
    else if($minutes <= 60){
        return $minutes."m ago";
    }
    else if($hours<=24){
        return $hours."h ago";

    }
    else if($days<=7){
        return $days."D ago";
    }
    else if($weeks<=5){
        return $weeks."W ago";
    }
    else if($months<=12){
        return $months."Mo ago";
    }
}


// --------------Tweet------------------------------

public function tweet($data){
	$tweet = nl2br($this->sanitize_input_data($data['content']));
	// die($tweet);
	$user_id = $_SESSION['user_id'];
	$time = date("Y-m-d H:i:s");
	if ($tweet != "") {
		$add_tweet = $this->db->prepare("INSERT INTO tweets (tweet_by, tweet_time, tweet_content) VALUES (:x,:y,:z) ");
		$add_tweet->bindparam(':x', $user_id);
		$add_tweet->bindparam(':y', $time);
		$add_tweet->bindparam(':z', $tweet);
		if($add_tweet->execute()){
			$msg = '<p style="color:green">Your tweet was sent.</p>';
		}else{
			$msg = "<p style='color:red'>Internal server error</p>";
		}
	}else{
		$msg = "<p style='color:red'>Tweet can not be empty.</p>";
	}
	return $msg;

}

//  --------------Display Everything-------------

	public function display($p , $q){
		try{
			$sql = "SELECT * FROM $p ORDER BY $q DESC";
			$stmt = $this->db->prepare($sql);
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$result = $stmt->fetchAll();		
		} catch (PDOException $exception) {
			$result = "<p style='color:red'>Something went wrong. </p>";
			
		  }
		  return $result;
	}

	// -------------Display one----------------
	public function display_one($p , $q, $r){
		try{
			$sql = "SELECT * FROM $p WHERE $q=?";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([$r]);
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$result = $stmt->fetch();		
		} catch (PDOException $exception) {
			$result = "<p style='color:red'>Something went wrong. </p>";
			
		  }
		  return $result;
	}

// -------------Display one----------------
	public function display_onee($p , $q, $r){
		try{
			$sql = "SELECT * FROM $p WHERE $q=$r";
			$stmt = $this->db->prepare($sql);
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$result = $stmt->fetch();		
		} catch (PDOException $exception) {
			$result = "<p style='color:red'>Something went wrong. </p>";
			
		  }
		  return $result;
	}

// --------------Change Image---------------

public function changeImage($file){
		$tempname = $file["tmp_name"];	
        $file_size =$file['size'];
        $file_ext=strtolower(end(explode('.',$file['name'])));
        $extensions= array("jpeg","jpg","png");
        if(in_array($file_ext,$extensions)=== true){
        if($file_size < 1048576){
		if($file['name'] != NULL){
		$hash = bin2hex(random_bytes(8));
		$filename = $hash.$this->sanitize_input_data($file["name"]);
    	$folder = "img/users/".$filename;
		$FileType = strtolower(pathinfo($folder,PATHINFO_EXTENSION));
		if (file_exists($folder)) {
			$message = "<p style='color:red;'>Please try again.</p>";
		  }
		else{
		try{
			$sql1 = "SELECT picture FROM profile_info where user_id=?";
			$stmt1 = $this->db->prepare($sql1);
			$stmt1->execute([$_SESSION['user_id']]);
			$res=$stmt1->fetch();
			if($res['picture']!='user-default.png'){
			$todelete = "img/users/".$res['picture'];
			unlink($todelete);
		}
		}
		catch(PDOException $exception){
			$message .= $exception->getMessage();
		}
    	// move_uploaded_file($tempname, $folder);
          $compressedImage = $this->compressImage($tempname, $folder, 50);
		try{
			$sql = "UPDATE profile_info  SET picture=? WHERE user_id=?";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([$filename, $_SESSION['user_id']]);
			setcookie('message','Profile Updated.',time()+5, "/","", 0);
			header('Location: edit-profile.php');
		}
		catch(PDOException $exception){
			$message = $exception->getMessage();
		}

	}
} else{
	$message = "<p style='color:red;'>Please select a file.</p>";
}

}else{
    $message = "<p style='color:red;'>File size should be less than 1MB :(</p>";

}
}else{
     $message = "<p style='color:red;'>Only png, jpeg and jpg are allowed.</p>";

}		
echo $message;

}

// ----------------Update Profile-----------------------

public function updateProfile($data){
	$name = $this->sanitize_input_data($data['name']);
	$bio = $this->sanitize_input_data($data['bio']);
	$location= $this->sanitize_input_data($data['location']);
	$birthday = $this->sanitize_input_data($data['bday']);
	if ($birthday == "") {
		$bd = NULL;
	}else{
	// $time = strtotime(birthday);
	// $bd = date("Y-m-d H:i:s", $time);
	// echo $bd;
		$bd = $birthday;	
	}

	// var_dump($data);

	try{
			$sql = "UPDATE profile_info  SET bio=? , location=? , birthday=? WHERE user_id=?";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([$bio, $location, $bd, $_SESSION['user_id']]);

			$sql1 = "UPDATE users  SET user_fullname=?  WHERE user_id=?";
			$stmt1 = $this->db->prepare($sql1);
			$stmt1->execute([$name, $_SESSION['user_id']]);

			header('Location: profile');
}catch(PDOException $exception){
			$message = $exception->getMessage();
			echo $message;
		}

}

// ------------------Follow--------------------
function follow($uid){
	$uid = $this->sanitize_input_data($uid);
	$sql = "SELECT * from follow where follows= ? AND user_id=? ";
	$stmt = $this->db->prepare($sql);
	$stmt->execute([$uid, $_SESSION['user_id']]);
	$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
    if($stmt->rowCount() > 0){
		$sql1 = "DELETE from follow where follows= ? AND user_id=? ";
		$stmt1 = $this->db->prepare($sql1);
		$stmt1->execute([$uid, $_SESSION['user_id']]);
		$msg = "unfollowed";
	}else{
		$sql1 = "INSERT INTO  follow (user_id, follows) VALUES(?,?) ";
		$stmt1 = $this->db->prepare($sql1);
		$stmt1->execute([$_SESSION['user_id'],$uid ]);
		$msg = "followed";
	}
	return $msg;
}

// ----------------Count--------------------
public function count($a, $b, $c){
	try
	  {
		 $stmt = $this->db->prepare("SELECT * FROM $a WHERE $b=$c");
		 $stmt->execute();
		 $userRow=$stmt->rowCount();
		 return $userRow;
} catch(PDOException $e){
   echo "Sorry, Internal Server Error.";
}
}

// ------------------Like--------------------
function like($tid){
	$uid = $this->sanitize_input_data($tid);
	$sql = "SELECT * from likes where tweet_id= ? AND liked_by=? ";
	$stmt = $this->db->prepare($sql);
	$stmt->execute([$tid, $_SESSION['user_id']]);
	$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
    if($stmt->rowCount() > 0){
		$sql1 = "DELETE from likes where tweet_id= ? AND liked_by=? ";
		$stmt1 = $this->db->prepare($sql1);
		$stmt1->execute([$tid, $_SESSION['user_id']]);
		$msg = "disliked";
	}else{
		$sql1 = "INSERT INTO  likes(tweet_id, liked_by) VALUES(?,?) ";
		$stmt1 = $this->db->prepare($sql1);
		$stmt1->execute([$tid, $_SESSION['user_id'] ]);
		$msg = "liked";
	}
	return $msg;
}

// public function compressImage($source, $destination, $quality) {

//   $info = getimagesize($source);

//   if ($info['mime'] == 'image/jpeg') 
//     $image = imagecreatefromjpeg($source);

//   elseif ($info['mime'] == 'image/gif') 
//     $image = imagecreatefromgif($source);

//   elseif ($info['mime'] == 'image/png') 
//     $image = imagecreatefrompng($source);

//   imagejpeg($image, $destination, $quality);

// }

// --------------Update-------------------

public function update($a, $data, $c, $d){

	try{
			$sql = "UPDATE $a  SET $data WHERE $c=?";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([$d]);
			setcookie('message','Updated',time()+5, "/","", 0);
			
		} catch (PDOException $exception) {
			echo $exception->getMessage();
		  }

}

// ------------------Add Notification--------------
public function addnotification($to,  $link, $type){
	$user_id = $_SESSION['user_id'];
    if($user_id==""){
        $user_id = 34;
    }
	$time = date("Y-m-d H:i:s");

    try{
    $sql = "SELECT * from notification where n_to= ? AND n_by=? AND n_link=? and n_type =? ";
	$stmt = $this->db->prepare($sql);
	$stmt->execute([$to, $user_id, $link, $type]);
    if($stmt->rowCount() > 0){
		$sql1 = "DELETE from notification where n_to= ? AND n_by=? AND n_link=? and n_type =?";
		$stmt1 = $this->db->prepare($sql1);
		$stmt1->execute([$to, $user_id, $link, $type]);
	}
    }catch (PDOException $exception) {
			echo $exception->getMessage();
    }

	try{
		$sql2 = "INSERT INTO  notification(n_to, n_by, n_link, n_type, n_time) VALUES(?,?,?,?,?) ";
		$stmt2 = $this->db->prepare($sql2);
		$stmt2->execute([$to, $user_id, $link, $type, $time ]);

	}catch (PDOException $exception) {
			echo $exception->getMessage();
		  }

}


public function convert_filesize($bytes, $decimals = 2) {
    $size = array('B','KB','MB','GB','TB','PB','EB','ZB','YB');
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
}


public function compressImage($source, $destination, $quality) {
    // Get image info
    $imgInfo = getimagesize($source);
    $mime = $imgInfo['mime'];

    // Create a new image from file
    switch($mime){
        case 'image/jpeg':
            $image = imagecreatefromjpeg($source);
            break;
        case 'image/png':
            $image = imagecreatefrompng($source);
            break;
        case 'image/gif':
            $image = imagecreatefromgif($source);
            break;
        default:
            $image = imagecreatefromjpeg($source);
    }
    // Save image
    imagejpeg($image, $destination, $quality);
    // Return compressed image
    return $destination;
}




}

?>
