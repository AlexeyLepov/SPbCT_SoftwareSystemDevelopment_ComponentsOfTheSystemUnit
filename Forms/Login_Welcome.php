<head>
  <meta charset="UTF-8">
  <title>Добро пожаловать
  </title>
  <link rel="shortcut icon" href="https://icons.iconarchive.com/icons/paomedia/small-n-flat/128/profile-icon.png" type="image/x-icon" />
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/owl.carousel.min.css">
  <link rel="stylesheet" href="../css/style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.css" />
  <script type="text/javascript" src="JsTablePagination.js"></script>
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/style.css">
</head>

 <?php
// Initialize the session (начинаем новую сессию)
session_start();

// Check if the user is logged in, if not then redirect him to login page
// проверяем, был ли выполнен вход; если нет, отправляем на страницу входа
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
{
    header("location: Login.php"); // перенаправляем на страницу входа
    exit;
}

// извлекаем переменные сессии имя пользователя, Id роли и имя роли
$username = $_SESSION["username"];
$role_id = $_SESSION["role_id"];
$role_name = $_SESSION["role_name"];
//echo "login_welcome.php: ".$_SESSION["username"]. "<br/>";
//echo "login_welcome.php: ".$_SESSION["role_id"]. "<br/>";
//echo "login_welcome.php: ".$_SESSION["role_name"]. "<br/>";
?>

    <?php
    //$_SESSION['response']="Добро пожаловать на сайт, <u>".$_SESSION["username"]."</u>! Ваша роль: <u>".$_SESSION["role_name"]."</u>.";
	//$_SESSION['res_type']="info";
    ?>


<!--МЕНЮ-->
<?php
    $CD_path = '../';
    $h_path = '../Load/';
	if($role_id >= 2)
		include($h_path.'menu_Admin.php');
	else
		include($h_path.'menu.php');
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>


<body style="background: #042630; background-color: #042630;">
    
    
    <div style="background: rgb(0,86,110); background: linear-gradient(90deg, rgb(240, 195, 132) 0%, rgb(3, 162, 211) 40%, rgb(0, 154, 201) 60%,  rgb(245, 189, 112) 100%);">
        <div class="container">
            <br><br><br>
        </div>
    </div>


    <div style="background: rgb(0,86,110); background: linear-gradient(90deg, rgb(214, 152, 64) 0%, rgb(0, 106, 138) 40%, rgb(0, 106, 138) 60%,  rgb(214, 152, 64) 100%);">
    <div class="container">
    
    <div class="page-header">
        <h1 style="color: rgb(255, 255, 255);">Личный профиль</h1>
    </div>
    
        <?php if (isset($_SESSION['response'])) { ?>
        <div class="alert alert-<?= $_SESSION['res_type']; ?> alert-dismissible text-center" style="color:#000;">
          <b><?= $_SESSION['response']; ?></b>
        </div>
        <?php } unset($_SESSION['response']);
        ?>
        
    <div class="page-header">
        <h1 style="color: rgb(255, 255, 255);">Здравствуйте, <b><?php echo htmlspecialchars($_SESSION["username"]); ?>
        </b>(<?php echo htmlspecialchars($_SESSION["role_name"]); ?>). <br>Добро пожаловать на сайт!</h1>
    </div>
    
    <p>
        <a href="Login_Logout.php" class="btn btn-danger">Выйти из аккаунта</a>
    </p><br>
    
    </div>
    </div>
    
    
    <div style="background: rgb(0,86,110); background: linear-gradient(90deg, rgb(240, 195, 132) 0%, rgb(3, 162, 211) 40%, rgb(0, 154, 201) 60%,  rgb(245, 189, 112) 100%);">
        <div class="container">
            <br>
        </div>
    </div>        
    
    

<!--ПОДВАЛ-->
<?php
	if($role_id == 1) include($h_path.'footer_User.php'); 
	else if ($role_id >= 2) include($h_path.'footer_Admin.php');
	else include($h_path.'footer.php');
?>

</body>
</html>