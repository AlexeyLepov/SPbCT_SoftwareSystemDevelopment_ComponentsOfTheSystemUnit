<?php
$host= "sutct.org";
$user= "881_28";
$pass= "3bVcDO&g@maI";
$db=   "881_28";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die('Невозможно соединится с базой данных, проверьте подключение к Интернету.' . $conn->connect_error);
}
?>