<?php
$data = $_SESSION;
unset($_SESSION["csn"]);
unset($_SESSION["un"]);
unset($_SESSION["login"]);
// Átirányítás a főoldalra
header("Location: index.html");
exit();
?>