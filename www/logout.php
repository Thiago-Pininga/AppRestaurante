<?php
session_start();
session_destroy();

// Apaga o cookie
setcookie("usuario", "", time() - 3600, "/");

header("Location: login.php");
exit;
