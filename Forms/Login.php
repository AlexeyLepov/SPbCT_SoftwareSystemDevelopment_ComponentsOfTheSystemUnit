<html lang="ru">

<head>
  <meta charset="UTF-8">
  <title>Вход
  </title>
    <link rel="shortcut icon" href="https://icons.iconarchive.com/icons/paomedia/small-n-flat/128/profile-icon.png" type="image/x-icon" />
    <link rel="stylesheet" href="../css/CssTableUl.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/owl.carousel.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<!--МЕНЮ-->
<?php
    $CD_path = '../';
    $h_path = '../Load/';
    include($h_path.'menu_Register.php'); 
?>

<br>

<?php
// Initialize the session (создаем новую сессию)
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
// (проверяем, были ли установлены переменные входа в систему)

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["db"] === "881_28")
{
    if($_SESSION["db"] === "881_28")
    {
        // если да, перенаправляем на страницу приветствия
        header("location: Login_Welcome.php");
        exit;
    }
}
 
// Include config file (подключаем файлы)
require_once "Login_Config.php";
require_once "Login_Role.php";
                        
// Define variables and initialize with empty values
// (очищаем переменные)
$username = $password = "";
$username_err = $password_err = "";
 
// Processing form data when form is submitted
// (выполняем обработку, когда пользователь отправил данные формы)
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Check if username is empty (проверка поля "username")
    if(empty(trim($_POST["username"])))
    {
        $username_err = "Пожалуйста, введите имя пользователя (Логин)";
    }
    else
    {
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty (проверка поля "пароль")
    if(empty(trim($_POST["password"])))
    {
        $password_err = "Пожалуйста, введите пароль";
    }
    else
    {
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials (проверка логин-пароля)
    if(empty($username_err) && empty($password_err))
    {
        // Prepare a select statement (формируем строку запроса пароля)
        $sql = "SELECT users_password FROM users WHERE users_username = '".$username."'";
        if($rows = mysqli_query($link, $sql)) // считываем строки ответа
        {
            if( $row = mysqli_fetch_row($rows)) // получаем 0ю строку ответа
            {   // 0й элемент 0й строки должен содержать пароль
                if($row[0] === $password) // если равен введенному, авторизуемся 
                {   // 1й строки быть не должно, т.к. другого пароля не полагается
                    if(empty(mysqli_fetch_row($rows)))
                    {
                        mysqli_free_result($rows); // очищаем результаты
                        //$thread_id = mysqli_thread_id($link);
                        //echo "login.php:".$thread_id. "<br/>";//лог потока БД
                        
                        // Store data in session variables (запоминаем переменные сессии)
                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $id;
                        $_SESSION["username"] = $username;
                        $_SESSION["link"] = $link;
                        $_SESSION["db"] = "881_28";
                        
                        // считываем роль: возвращаемого значения нет, если ОК
                        echo setrole_by_username($username, $link);
                        //echo "login.php:".$_SESSION["username"]. "<br/>";
                        //echo "login.php:".$_SESSION["role_id"]. "<br/>";
                        //echo "login.php:".$_SESSION["role_name"]. "<br/>";
                        // Password is correct, so start a new session (начинаем новую сессию)                        
                        session_start();
                        // Redirect user to welcome page (перенаправляем на страницу приветствия)
                        // строку можно временно закомментить, если раскомментировать echo!!!
                        header("location: Login_Welcome.php"); 
                    }
                    else
                    {
                        // заполняем переменную ошибки, если пароль неправильно считан
                        $password_err = "Ошибка считывания пароля из базы данных!";
                    }
                }
                else
                {   // переменная ошибки для отображения на форме
                    $password_err = "Введен неправильный пароль!";
                }
            }
            else
            {
                // переменная ошибки для отображения на форме
                $username_err = "Введено несуществующее имя пользователя";
            }
        }
        else
        {
            echo "Login.php: Запрос к базе данных не выполнен!!((";
        }
    }
    // Close connection (закрываем соединение к БД)
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>

<body style="background: #042630; background-color: #042630;">
    
        <style type="text/css">
        .wrapper {
            width: 500px;
            margin: 0 auto;
        }
    </style>

    <div style="background: rgb(0,86,110); background: linear-gradient(90deg, rgb(240, 195, 132) 0%, rgb(3, 162, 211) 40%, rgb(0, 154, 201) 60%,  rgb(245, 189, 112) 100%);">
        <div class="container">
            <br><br>
        </div>
    </div>

 <div style="background: rgb(0,86,110); background: linear-gradient(90deg, rgb(214, 152, 64) 0%, rgb(0, 106, 138) 40%, rgb(0, 106, 138) 60%,  rgb(214, 152, 64) 100%);">
        <div class="container">

            <div class="wrapper">
                <div class="page-header">
                <h1 style="color: rgb(255, 255, 255);  text-align: center;">Вход</h1>
                <h2 style="color: rgb(255, 255, 255);  text-align: center;">Заполните данные логин-пароля</h2>
                </div>

				<?php if (isset($_SESSION['response'])) { ?>
				<div class="alert alert-<?= $_SESSION['res_type']; ?> alert-dismissible text-center" style="color:#000;">
				  <b><?= $_SESSION['response']; ?></b>
				</div>
				<?php } unset($_SESSION['response']);
				?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                        <label style="color: rgb(222, 222, 222);">Имя пользователя (логин)</label>
                        <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                        <span class="help-block"><?php echo $username_err; ?></span>
                    </div>    
                    <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                        <label style="color: rgb(222, 222, 222);">Пароль</label>
                        <input type="password" name="password" class="form-control">
                        <span class="help-block"><?php echo $password_err; ?></span>
                    </div>
					<br>
                    <div class="form-group" style="text-align: center;">
                        <input type="submit" class="plan-button" value="Войти в систему">
                    </div>
                </form>
                <!--<form action="Login_AsGuest.php" method="POST">
                    <input style="text-align: center;" type="submit" class="plan-button"name="AsGuest" value="Войти в гостевом режиме">
                </form>-->
            </div>

        </div>
    </div>

    <div style="background: rgb(0,86,110); background: linear-gradient(90deg, rgb(240, 195, 132) 0%, rgb(3, 162, 211) 40%, rgb(0, 154, 201) 60%,  rgb(245, 189, 112) 100%);">
        <div class="container">
            <br>
        </div>
    </div>
    
<?php
    include($h_path.'footer.php'); 
?>
    
</body>
</html>