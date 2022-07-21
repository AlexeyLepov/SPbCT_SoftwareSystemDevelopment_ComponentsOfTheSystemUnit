<html lang="ru">

<head>
  <meta charset="UTF-8">
  <title>Процессоры
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
    
          $query = '
          SELECT 
            Processor_id,
            Processor_model,               
            Proc_Brend.Proc_Brend_name,       
            Proc_FreqBase.Proc_FreqBase_value,  
            Proc_FreqBoost.Proc_FreqBoost_value, 
            Proc_Cores.Proc_Cores_value,        
            Soсket.Soсket_name,                
            Proc_TDP.Proc_TDP_value,            
            Processor_price,
			Processor_imageName,
            Processor_imageType,                   
            Processor_imageData             
          FROM   Processor
            INNER JOIN Proc_Brend     ON Proc_Brend.Proc_Brend_id         = Processor.Processor_brend
            INNER JOIN Soсket         ON Soсket.Soсket_id                 = Processor.Processor_socket
            INNER JOIN Proc_FreqBase  ON Proc_FreqBase.Proc_FreqBase_id   = Processor.Processor_freqBase
            INNER JOIN Proc_FreqBoost ON Proc_FreqBoost.Proc_FreqBoost_id = Processor.Processor_freqBoost
            INNER JOIN Proc_Cores     ON Proc_Cores.Proc_Cores_id         = Processor.Processor_cores
            INNER JOIN Proc_TDP       ON Proc_TDP.Proc_TDP_id             = Processor.Processor_tdp
          ORDER BY Processor_model;';

$result = mysqli_query($conn, $query) or die("Error" . mysqli_error($conn));
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
    .";base64,".$row[11]."' height=200 />
    <ul class='plan-features'><hr>";
 
 echo "<li style='color:green;'>Производитель   - <b style='color:black;'>{$row[2]}</b> </li>";
 echo "<li style='color:green;'>Базовая частота - <b style='color:black;'>{$row[3]}</b> </li>";
 echo "<li style='color:green;'>Boost частота   - <b style='color:black;'>{$row[4]}</b> </li>";
 echo "<li style='color:green;'>Ядра            - <b style='color:black;'>{$row[5]}</b> </li>";
 echo "<li style='color:green;'>Сокет           - <b style='color:black;'>{$row[6]}</b> </li>";
 echo "<li style='color:green;'>TDP             - <b style='color:black;'>{$row[7]}</b> </li>";
 echo "<li style='color:green;'>Цена            - <b style='color:black;'>{$row[8]} ₽</b></li>";
 
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