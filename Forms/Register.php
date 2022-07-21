<?php
session_start(); // начинаем новую сессию

// извлекаем переменные из сессии, если были установлены
$link = $_SESSION["link"];
$username = $_SESSION["username"];
$role_id = $_SESSION["role_id"];
$role_name = $_SESSION["role_name"];
$db = $_SESSION["db" ];
    
include('../Connect_To_Database.php');
include('Login_Config.php');

$conn = mysqli_connect($host, $user, $pass, $db) 
    or die("Ошибка " . mysqli_error( $conn));
    mysqli_set_charset($conn, 'utf8');

	$update=false;
	$id="";
	$name_first="";
	$name_middle="";
	$name_last="";
	$email="";
	$username=""; 
	$password="";
	$password_repeat="";

	if(isset($_POST['add']))
	{
        $repeat=0;
		$standart_role=1;
		$name_first=trim($_POST['name_first']);
		$name_middle=trim($_POST['name_middle']);
		$name_last=trim($_POST['name_last']);
		$email=trim($_POST['email']);
		$username=trim($_POST['username']);
		$password=trim($_POST['password']);
		$password_repeat=trim($_POST['password_repeat']);
		
        $query="
		INSERT INTO users
			(users_user_id,
			users_role_id,
			users_first_name,
			users_middle_name,
			users_last_name,
			users_email,
			users_username,
			users_password)
		VALUES(?,?,?,?,?,?,?,?)";
		
		$query1="SELECT * FROM users WHERE users_username=?";
		$stmt=$conn->prepare($query1);
		$stmt->bind_param("s",$username);
		$stmt->execute();
		$result=$stmt->get_result();
		while ($row = $result->fetch_assoc()) {
		    if(trim($_POST['username'])==$row['users_username'])
		    { $repeat += 1; }
		}
        if ($repeat==0)
        {
			if ($password == $password_repeat)
			{
				$stmt=$conn->prepare($query);
				$stmt->bind_param("iissssss",$id,$standart_role,$name_first,$name_middle,$name_last,$email,$username,$password);
				$stmt->execute();
				$_SESSION['response']="Вы зарегистрированы, требуется войти в систему.";
				$_SESSION['res_type']="info";
			}
			else
			{
				$_SESSION['response']="Пароли должны совпадать.";
				$_SESSION['res_type']="danger";
			}
        }
        else
        {
    		$_SESSION['response']="Пользователь ".$username." уже зарегистрирован в системе.";
    		$_SESSION['res_type']="info";
        }
	}
?>




<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Регистрация
  </title>
    <link rel="stylesheet" href="../css/CssTableUl.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/owl.carousel.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<!--МЕНЮ-->
<?php
    $CD_path = '../';
    $h_path = '../Load/';
    include($h_path.'menu_Login.php'); 
?>
<br>




<?php
// Initialize the session (создаем новую сессию)
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
// (проверяем, были ли установлены переменные входа в систему)

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["db"] === "881_28")
{
    // если да, перенаправляем на страницу приветствия
    header("location: Login_Welcome.php");
    exit;
}
 
// Include config file (подключаем файлы)
require_once "Login_Config.php";
require_once "Login_Role.php";
                        
// (очищаем переменные)
$username = $name_first = $name_middle = $name_last = $email = $password = $password_repeat= "";
$username_err = $email_err = $name_last_err = $name_first_err = $name_middle_err = $password_repeat_err = $password_err = "";
 
// (выполняем обработку, когда пользователь отправил данные формы)
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Check if username is empty (проверка поля "username")
    if(empty(trim($_POST["email"])))
    {
        $email_err = "Пожалуйста введите электронную почту";
    }
    else
    {
        $email = trim($_POST["email"]);
    }
    if(empty(trim($_POST["name_last"])))
    {
        $name_last_err = "Пожалуйста введите Фамилию";
    }
    else
    {
        $name_last = trim($_POST["name_last"]);
    }
    if(empty(trim($_POST["name_first"])))
    {
        $name_first_err = "Пожалуйста введите Имя";
    }
    else
    {
        $name_first = trim($_POST["name_first"]);
    }
    if(empty(trim($_POST["name_middle"])))
    {
        $name_middle_err = "Пожалуйста введите Отчество";
    }
    else
    {
        $name_middle = trim($_POST["name_middle"]);
    }
    if(empty(trim($_POST["username"])))
    {
        $username_err = "Пожалуйста введите имя пользователя (логин)";
    }
    else
    {
        $username = trim($_POST["username"]);
    }  
    if(empty(trim($_POST["password"])))
    {
        $password_err = "Пожалуйста введите пароль";
    }
    else
    {
        $password = trim($_POST["password"]);
    }    
	if(empty(trim($_POST["password_repeat"])))
    {
        $password_repeat_err = "Пожалуйста повторите пароль";
    }
    else
    {
        $password_repeat = trim($_POST["password_repeat"]);
    }
    
    // Validate credentials (проверка логин-пароля)

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
	body { font: 14px sans-serif; }
	.wrapper { width: 350px; padding: 20px; }
</style>
</head>
<body style="background: #042630;">
<style type="text/css">
    .wrapper { width: 500px; margin: 0 auto; }
</style>

<div style="background: rgb(0,86,110); background: linear-gradient(90deg, rgb(240, 195, 132) 0%, rgb(3, 162, 211) 40%, rgb(0, 154, 201) 60%,  rgb(245, 189, 112) 100%);">
<div class="container"><br><br></div></div>

<div style="background: rgb(0,86,110); background: linear-gradient(90deg, rgb(214, 152, 64) 0%, rgb(0, 106, 138) 40%, rgb(0, 106, 138) 60%,  rgb(214, 152, 64) 100%);">
        <div class="container">

            <div class="wrapper">
                <div class="page-header">
					<h1 style="color: rgb(255, 255, 255);  text-align: center;">Регистрация</h1>
                </div>
				
				<?php if (isset($_SESSION['response'])) { ?>
				<div class="alert alert-<?= $_SESSION['res_type']; ?> alert-dismissible text-center" style="color:#000;">
				  
				  <b><?= $_SESSION['response']; ?></b>
				</div>
				<?php } unset($_SESSION['response']);
				?>
				
                <form action="Register.php" method="post">
					<div class="form-group">
						<input type="hidden" name="id" value="<?= $id; ?>">
					</div>
					<div class="form-group">
						<input type="text" name="name_last" value="<?= $name_last; ?>" class="form-control" placeholder="Фамилия" >
					</div>
                    <div class="form-group">
						<input type="text" name="name_first" value="<?= $name_first; ?>" class="form-control" placeholder="Имя" >
					</div>
					<div class="form-group">
						<input type="text" name="name_middle" value="<?= $name_middle; ?>" class="form-control" placeholder="Отчество" >
					</div>
<br>				
					<div class="form-group">
						<input type="text" name="email" value="<?= $email; ?>" class="form-control" placeholder="Электронная почта" required>
					</div>
					<div class="form-group">
						<input type="text" name="username" value="<?= $username; ?>" class="form-control" placeholder="Имя пользователя (Логин)" required>
					</div>
<br>
					<div class="form-group">
						<input type="password" name="password" value="<?= $password; ?>" class="form-control" placeholder="Пароль" required>
					</div>
					<div class="form-group">
						<input type="password" name="password_repeat" value="<?= $password_repeat; ?>" class="form-control" placeholder="Подтверждение пароля" required>
					</div>
<br>
                    <div style="text-align: center;" class="form-group">
                        <input type="submit" name="add" class="plan-button" value="Зарегистрироваться">
                    </div>
                </form>
            </div>
        </div>
</div>

<div style="background: rgb(0,86,110); background: linear-gradient(90deg, rgb(240, 195, 132) 0%, rgb(3, 162, 211) 40%, rgb(0, 154, 201) 60%,  rgb(245, 189, 112) 100%);">
<div class="container"><br></div></div>
  
<?php
    include($h_path.'footer.php'); 
?>
    
</body>
</html>