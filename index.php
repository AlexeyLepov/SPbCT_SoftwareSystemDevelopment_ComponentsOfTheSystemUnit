<!doctype html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <title>Комплектующие системного блока
    </title>
    <link rel="shortcut icon" href="https://icons.iconarchive.com/icons/fatcow/farm-fresh/32/cooler-icon.png" type="image/x-icon" />
    <link rel="stylesheet" href="css/CssTableUl.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<!--МЕНЮ-->
<?php
	session_start();
	$username = $_SESSION["username"];
	$role_id = $_SESSION["role_id"];
	$role_name = $_SESSION["role_name"];
    $CD_path = '';
    $h_path = 'Load/';
	if(isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] == true)
	{
		if($role_id >= 2) include($h_path.'menu_Admin.php');
		else if($role_id == 1) include($h_path.'menu.php');
		else include($h_path.'menu.php');
	}
	else include($h_path.'menu_Login.php');
?>

<body style="background: #042630;">

<?php
	if($role_id >= 1) include($h_path.'slider_User.php');
	else include($h_path.'slider_Guest.php');
?>
    
<!--ПОДВАЛ-->
<?php
	if($role_id == 1) include($h_path.'footer_User.php'); 
	else if ($role_id >= 2) include($h_path.'footer_Admin.php');
	else include($h_path.'footer.php');
	mysqli_close($conn);
?>

    <script src="js/jquery.js"></script>
    <script src="js/jquery.slicknav.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/owl.carousel.min.js"></script>

</body>
</html>