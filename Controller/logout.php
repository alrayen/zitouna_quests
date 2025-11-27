<?php
session_start();


$_SESSION = array();


session_destroy();


header("Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/login.php");
exit;
?>
