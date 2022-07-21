<?php
session_start(); // начинаем новую сессию

// извлекаем переменные из сессии, если были установлены
$link = $_SESSION["link"];
$username = $_SESSION["username"];
$role_id = $_SESSION["role_id"];
$role_name = $_SESSION["role_name"];
$db = $_SESSION["db"];
    
include('../Connect_To_Database.php');
include('../Forms/Login_Config.php');

$conn = mysqli_connect($host, $user, $pass, $db) 
    or die("Ошибка " . mysqli_error($conn));
    mysqli_set_charset($conn, 'utf8');

	$update=false;
	$id="";
	$surname="";
	$name="";
	$midname="";
	$email="";
	$username="";
	$socket="";
	$password="";
	//$img_name="";
	//$img_type="";
	//$img_data="";

	if(isset($_POST['add'])){
        $repeat=0;
		$model=trim($_POST['model']);
		$brend=trim($_POST['brend']);
		$sata=trim($_POST['sata']);
		$freq_min=trim($_POST['freq_min']);
		$freq_max=trim($_POST['freq_max']);
		$socket=trim($_POST['socket']);
		$chipset=trim($_POST['chipset']);
		$price=trim($_POST['price']);
		
		//$img_name=$_FILES['image']['name'];
		//$img_type=$_FILES['image']['type'];
		//$img_data=file_get_contents($FILES['image']['tmp_name'];

        $query="INSERT INTO users(users_role_id,users_first_name,users_middle_name,
        users_last_name,users_email,users_username,users_password) VALUES(?,?,?,?,?,?,?)";
		
		$query1="SELECT * FROM users WHERE users_username=?";
		$stmt=$conn->prepare($query1);
		$stmt->bind_param("s",$model);
		$stmt->execute();
		$result=$stmt->get_result();
		
		while ($row = $result->fetch_assoc()) {
		    if(trim($_POST['model'])==$row['users_username'])
		    { $repeat += 1; }
		}
		
		if ($repeat==0)
        {               
    		$stmt=$conn->prepare($query);
    		$stmt->bind_param("siiiiiid",$model,$brend,$sata,$freq_min,$freq_max,$socket,$chipset,$price);
    		$stmt->execute();
    		header('location:Login.php');
    		$_SESSION['response']="Добавление новой записи ".$model." в базу данных выполнено успешно.";
    		$_SESSION['res_type']="info";
        }
		else 
        {    		
            header('location:Login.php');
    		$_SESSION['response']="Добавление не выполнено. Модель ".$model." уже есть в базе данных.";
    		$_SESSION['res_type']="danger";
        }
	}
?>