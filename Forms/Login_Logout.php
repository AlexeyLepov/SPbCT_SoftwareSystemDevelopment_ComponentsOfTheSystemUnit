<?php
// Initialize the session (создаем новую сессию)
session_start();
 
// Unset all of the session variables (удаляем все переменные сессии)
$_SESSION = array();
 
// Destroy the session (завершаем сессию)
session_destroy();
 
// Redirect to login page (перенаправляем на страницу логин-пароля)
header("location: Login.php");
exit;
?>
