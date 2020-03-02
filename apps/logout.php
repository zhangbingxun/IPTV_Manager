<?php
session_start();
session_unset(); //free all session variable
session_destroy(); //销毁一个会话中的全部数据
setcookie("psw", null, time()-1, "/");
setcookie("user", null, time()-1, "/");
setcookie("secret_key", null, time()-1, "/");
setcookie("rememberpass", "1", time()-1, "/");
setcookie("remembersecret_key", "1", time()-1, "/");
header("location:../index.php");

?>