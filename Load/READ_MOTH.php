<html lang="ru">

<head>
  <meta charset="UTF-8">
  <title>Материнские платы
  </title>
    <link rel="shortcut icon" href="https://icons.iconarchive.com/icons/fatcow/farm-fresh/32/cooler-icon.png" type="image/x-icon" />
    <link rel="stylesheet" href="../css/CssTableUl.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/owl.carousel.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<!--МЕНЮ-->
<?php
	session_start();
	$username = $_SESSION["username"];
	$role_id = $_SESSION["role_id"];
	$role_name = $_SESSION["role_name"];
    $CD_path = '../';
    $h_path = '../Load/';
	if(isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] == true)
	{
		if($role_id >= 2) include($h_path.'menu_Admin.php');
		else if($role_id == 1) include($h_path.'menu.php');
		else include($h_path.'menu.php');
	}
	else include($h_path.'menu_Login.php');
?>

<?php
include('../Connect_To_Database.php');
$conn = mysqli_connect($host, $user, $pass, $db) 
    or die("Ошибка " . mysqli_error($conn));
    mysqli_set_charset($conn, 'utf8');
    
		$query='
		SELECT 
           Motherboard_id,
		   Motherboard_model,
		   Brend.Brend_name,
		   Moth_SATA.Moth_SATA_value,
		   Moth_RamFreqMin.Moth_RamFreqMin_value,
		   Moth_RamFreqMax.Moth_RamFreqMax_value,
		   Soсket.Soсket_name,
		   Chipset.Chipset_name,
		   Motherboard_price,
		   Motherboard_imageName,
           Motherboard_imageType,                   
           Motherboard_imageData   
		FROM   Motherboard 
		   INNER JOIN Brend           ON Brend.Brend_id                     = Motherboard.Motherboard_brend
		   INNER JOIN Soсket          ON Soсket.Soсket_id                   = Motherboard.Motherboard_socket
		   INNER JOIN Chipset         ON Chipset.Chipset_id                 = Motherboard.Motherboard_chipset
		   INNER JOIN Moth_SATA       ON Moth_SATA.Moth_SATA_id             = Motherboard.Motherboard_sata
		   INNER JOIN Moth_RamFreqMin ON Moth_RamFreqMin.Moth_RamFreqMin_id = Motherboard.Motherboard_ram_freqMin
		   INNER JOIN Moth_RamFreqMax ON Moth_RamFreqMax.Moth_RamFreqMax_id = Motherboard.Motherboard_ram_freqMax
		ORDER BY Motherboard_model;';

$res= mysqli_query($conn,$query);
$result = mysqli_query($conn, $query) or die("Error" . mysqli_error($conn));
?>



<?php
if($result)
{
    $rows=mysqli_num_rows($result);

echo "<div style='background: rgb(0,86,110); background: linear-gradient(90deg, rgb(240, 195, 132) 0%, rgb(3, 162, 211) 40%, rgb(0, 154, 201) 60%,  rgb(245, 189, 112) 100%);'><div class='container'><br><br></div></div>

<div style='background: rgb(0,86,110); background: linear-gradient(90deg, rgb(214, 152, 64) 0%, rgb(0, 106, 138) 40%, rgb(0, 106, 138) 60%,  rgb(214, 152, 64) 100%);'><div class='container'>

<div class='plans'>";

while($row=mysqli_fetch_row($result))
{   
    echo "<div class='plan'> <h2 style='color:red;' class='plan-title'>".$row[1]."</h2>";
    
    echo "<embed src='data:".$row[10]
    .";base64,".$row[11]."' height=180 />
    <ul class='plan-features'><hr>";
 
 echo "<li style='color:green;'>Бренд           - <b style='color:black;'>{$row[1]}</b> </li>";
 echo "<li style='color:green;'>Разъемы Sata    - <b style='color:black;'>{$row[2]}</b> </li>";
 echo "<li style='color:green;'>Минимальная поддерживаемая частота ОЗУ  - <b style='color:black;'>{$row[3]}</b> </li>";
 echo "<li style='color:green;'>Максимальная поддерживаемая частота ОЗУ - <b style='color:black;'>{$row[4]}</b> </li>";
 echo "<li style='color:green;'>Сокет           - <b style='color:black;'>{$row[5]}</b> </li>";
 echo "<li style='color:green;'>Чипсет          - <b style='color:black;'>{$row[6]}</b> </li>";
 echo "<li style='color:green;'>Цена            - <b style='color:black;'>{$row[7]} ₽</b></li>";
 
    echo "</ul>";
    /*echo "
    <div class='container'>
    <a href='#' class='plan-button' style='margin:5px; padding:-5px;'> В корзину </a><br>
    <a href='#' style='font-size:12px;'>Добавить к сравнению</a><br>
    <a href='#' style='font-size:12px;'>Нашли ошибку?</a>
    </div>";   */
    echo "</div>";
    }
 echo "</div></div></div>"; 
}

else {echo "Подключение к базе данных не выполнено!";}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<body>  
<div style="background: rgb(0,86,110); background: linear-gradient(90deg, rgb(240, 195, 132) 0%, rgb(3, 162, 211) 40%, rgb(0, 154, 201) 60%,  rgb(245, 189, 112) 100%);"><div class="container"><br></div></div>
    

<!--ПОДВАЛ-->
<?php
	if($role_id == 1) include($h_path.'footer_User.php'); 
	else if ($role_id >= 2) include($h_path.'footer_Admin.php');
	else include($h_path.'footer.php');
?>

<script src="../js/JsRedirect.js"></script>
<script src="../js/JsRedirector.js"></script>
    
</body>
</html>