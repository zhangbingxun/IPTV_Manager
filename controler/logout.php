<?php
session_start();
session_unset();
session_destroy();
setcookie("username", null, time() -1, "/");
setcookie("password", null, time() -1, "/");
setcookie("secret_key", null, time() -1, "/");
setcookie("rememberpass", null, time() -1, "/");
header("location:/index.php");

?>