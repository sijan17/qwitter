<?php
session_start();
setcookie('s_session', null, -1, '/'); 
session_destroy();
header('Location: /qwitter/auth/login');


?>