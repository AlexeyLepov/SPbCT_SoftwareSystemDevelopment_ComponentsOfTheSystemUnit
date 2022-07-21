<?php
function setrole_by_username($username, $link)
{
    //include_once "Login_Config.php";
    $ferror = ""; // очищаем переменную ошибок
    $role_id = ""; // очищаем переменную роли
    $sql = "SELECT users_role_id FROM users WHERE users_username = '".$username."'";
$thread_id = mysqli_thread_id($link);
if(empty($thread_id)) // коннектим к БД, если не было установлено соединение
    {
        include('Login_Config.php');
        echo "Login_Role.php: вызван файл Login_Config.php!!";
    }
    else
    {
        echo "Login_Role.php: соединение с БД было установлено thread_id=".$thread_id;
    }

    if($frows = mysqli_query($link, $sql))
    {
        //echo "login_role.php: query executed!!!";
        if( $frow = mysqli_fetch_row($frows))
        {
            //echo "login_role.php: found row!!!";
            $role_id = $frow[0];
            if($role_id === "1") // 1 - пользователь
            {
                if(empty(mysqli_fetch_row($frows)))
                {   // запоминаем в текущей сессии имя роли
                    $_SESSION["role_name"] = "User";
                }
                else
                {
                    $ferror = "Ошибка чтения роли: считано более 1й строки!";
                }
            }
            else if($role_id === "2") // 2 - админ
            {// запоминаем в текущей сессии имя роли
                $_SESSION["role_name"] = "Admin";
            }            
            else if($role_id === "3") // 3 - админ
            {// запоминаем в текущей сессии имя роли
                $_SESSION["role_name"] = "TechExpert";
            }
            else if($role_id === "4") // 4 - админ
            {// запоминаем в текущей сессии имя роли
                $_SESSION["role_name"] = "SoftDev";
            }
            else
            {// запоминаем в текущей сессии имя роли
                $_SESSION["role_name"] = "Guest";
            }
            //echo "role_id=".$role_id;
            // запоминаем в текущей сессии идентификатор роли
            $_SESSION["role_id"] = $role_id;
            mysqli_free_result($frows); // очищаем результаты запроса
        }
        else
        {
            $ferror = "Ошибка чтения роли: считано 0 строк!(";
        }
    }
    else
    {
        $ferror =  "Ошибка чтения роли: запрос не выполнен!(".$sql;   
    }
    mysqli_close($link); // закрываем запрос
    return $ferror; // возвращаем ошибку, если произошла 
}
?>
