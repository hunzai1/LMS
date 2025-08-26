<?php

session_start();


session_unset();
session_destroy();


header("Location: teachers_login.php");
exit;

?>