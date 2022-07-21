<head>
  <meta charset="UTF-8">
  <title>Добро пожаловать
  </title>
    <link rel="shortcut icon" href="https://icons.iconarchive.com/icons/paomedia/small-n-flat/128/profile-icon.png" type="image/x-icon" />
    <link rel="stylesheet" href="../Style/CssTable.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/owl.carousel.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<!--МЕНЮ-->
<?php
    $CD_path = '../';
    $h_path = '../Load/';
    include($h_path.'menu.php'); 
?>

    


 <?php
// Initialize the session (начинаем новую сессию)
session_start();

// извлекаем переменные сессии имя пользователя, Id роли и имя роли
$_SESSION["username"] = "guest";
$_SESSION["role_id"] = "0";
$_SESSION["role_name"] = "Guest";
$_SESSION["loggedin"] = true;
$_SESSION["password"] = "LZSJDuQw";
$_SESSION["db"] = "881_28";
//echo "login_welcome.php: ".$_SESSION["username"]. "<br/>";
//echo "login_welcome.php: ".$_SESSION["role_id"]. "<br/>";
//echo "login_welcome.php: ".$_SESSION["role_name"]. "<br/>";
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
          <button type="button" class="close" data-dismiss="alert" style="color:#000;">&times;</button>
          <b><?= $_SESSION['response']; ?></b>
        </div>
        <?php } unset($_SESSION['response']);
        ?>
	
    <div class="page-header">
        <h1 style="color: rgb(255, 255, 255);">Здравствуйте, <b><?php echo htmlspecialchars($_SESSION["username"]); ?>
        </b>(<?php echo htmlspecialchars($_SESSION["role_name"]); ?>).<br> Добро пожаловать на сайт!</h1>
    </div>
    
    <p>
        <a href="Login_Logout.php" class="btn btn-danger">Выйти из аккаунта</a>
    </p>
    
    
       
            </div>
    </div>

    <div style="background: rgb(0,86,110); background: linear-gradient(90deg, rgb(240, 195, 132) 0%, rgb(3, 162, 211) 40%, rgb(0, 154, 201) 60%,  rgb(245, 189, 112) 100%);">A
        <div class="container">
            <br><br>
        </div>
    </div>        
    
<?php
    include($h_path.'footer.php'); 
?>

    <script src="js/vendor/modernizr-3.5.0.min.js"></script>
    <script src="js/vendor/jquery-1.12.4.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/isotope.pkgd.min.js"></script>
    <script src="js/ajax-form.js"></script>
    <script src="js/waypoints.min.js"></script>
    <script src="js/jquery.counterup.min.js"></script>
    <script src="js/imagesloaded.pkgd.min.js"></script>
    <script src="js/scrollIt.js"></script>
    <script src="js/jquery.scrollUp.min.js"></script>
    <script src="js/wow.min.js"></script>
    <script src="js/gijgo.min.js"></script>
    <script src="js/nice-select.min.js"></script>
    <script src="js/jquery.slicknav.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>
    
</body>
</html>
