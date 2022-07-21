<?php
// Define constants (определяем константы)
define('DB_SERVER', 'sutct.org');
define('DB_USERNAME', '881_28');
define('DB_PASSWORD', '3bVcDO&g@maI');
define('DB_NAME', '881_28');

$thread_id = mysqli_thread_id($link);
if(empty($thread_id)) // коннектим к БД, если не было установлено соединение
{
    $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    $thread_id = mysqli_thread_id($link);
    //echo "Login_Config.php: Выполнено соединение с БД!!! thread_id=".$thread_id;
}
else
{
    //echo "Login_Config.php: Соединение с БД уже было установлено!! thread_id=".$thread_id;
}

if(empty($thread_id)) // проверяем соединение
{
    die("Login_Config.php: Ошибка: не удается установить коннект с БД!" . mysqli_connect_error());
}
?>
