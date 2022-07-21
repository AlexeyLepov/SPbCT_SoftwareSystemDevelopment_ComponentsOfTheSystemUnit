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
	$role="";
	$first="";
	$middle="";
	$last="";
	$email="";
	$login="";
	$password="";
	$created_at="";
	//$img_name="";
	//$img_type="";
	//$img_data="";

	if(isset($_POST['add'])){
        $repeat=0;
		$role=trim($_POST['role']);
		$first=trim($_POST['first']);
		$middle=trim($_POST['middle']);
		$last=trim($_POST['last']);
		$email=trim($_POST['email']);
		$login=trim($_POST['username']);
		$password=trim($_POST['password']);
		$created_at=trim($_POST['created_at']);
		
		//$img_name=$_FILES['image']['name'];
		//$img_type=$_FILES['image']['type'];
		//$img_data=file_get_contents($FILES['image']['tmp_name'];

        $query="INSERT INTO users(users_role_id,users_first_name,users_middle_name,
        users_last_name,users_email,users_username,users_password)VALUES(?,?,?,?,?,?,?)";
		
		$query1="SELECT * FROM users WHERE users_username=?";
		$stmt=$conn->prepare($query1);
		$stmt->bind_param("s",$login);
		$stmt->execute();
		$result=$stmt->get_result();
		
		while ($row = $result->fetch_assoc()) {
		    if(trim($_POST['username'])==$row['users_username'])
		    { $repeat += 1; }
		}
		
		if ($repeat==0)
        {               
    		$stmt=$conn->prepare($query);
    		$stmt->bind_param("issssss",$role,$first,$middle,$last,$email,$login,$password);
    		$stmt->execute();
    		header('location:CRUD_Users.php');
    		$_SESSION['response']="Добавление нового пользователя ".$login." в базу данных выполнено успешно.";
    		$_SESSION['res_type']="info";
        }
		else 
        {    		
            header('location:CRUD_Users.php');
    		$_SESSION['response']="Добавление не выполнено. Имя пользователя ".$login." уже занято.";
    		$_SESSION['res_type']="danger";
        }
	}
	
	
	
	
	if(isset($_GET['delete'])){
		$id=$_GET['delete'];

		$query="SELECT users_username FROM users WHERE users_user_id=?";
		$stmt=$conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$result=$stmt->get_result();
		$row=$result->fetch_assoc();
		$login=$row['users_username'];
		
		$query="DELETE FROM users WHERE users_user_id=?";
		$stmt=$conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();

		header('location:CRUD_Users.php');
		$_SESSION['response']="Выбранный пользователь ".$login." успешно удален из базы данных.";
		$_SESSION['res_type']="danger";
	}
	
	
	
	
	if(isset($_GET['edit'])){
		$id=$_GET['edit'];

		$query="SELECT * FROM users WHERE users_user_id=?";
		$stmt=$conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$result=$stmt->get_result();
		$row=$result->fetch_assoc();

		$id=$row['users_user_id'];
		$role=$row['users_role_id'];
		$first=$row['users_first_name'];
		$middle=$row['users_middle_name'];
		$last=$row['users_last_name'];
		$email=$row['users_email'];
		$login=$row['users_username'];
		$password=$row['users_password'];
		$created_at=$row['created_at'];

	    $update=true;
		$_SESSION['response']="Данные пользователя ".$login." перенесены для редактирования в форму для обновления.";
		$_SESSION['res_type']="warning";
	}
	
	if(isset($_POST['update'])){
        $id=$_GET['edit'];

		$query="SELECT * FROM users WHERE users_user_id=?";
		$stmt=$conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$result=$stmt->get_result();
		$row=$result->fetch_assoc();
		
		$id=$row['users_user_id'];
		$role=$row['users_role_id'];
		$first=$row['users_first_name'];
		$middle=$row['users_middle_name'];
		$last=$row['users_last_name'];
		$email=$row['users_email'];
		$login=$row['users_username'];
		$password=$row['users_password'];
		$created_at=$row['created_at'];
		/*
		$id=$row['Motherboard_id'];
		$model=$row['Motherboard_model'];
		$brend=$row['Motherboard_brend'];
		$sata=$row['Motherboard_sata'];
		$freq_min=$row['Motherboard_ram_freqMin'];
		$freq_max=$row['Motherboard_ram_freqMax'];
		$socket=$row['Motherboard_socket'];
		$chipset=$row['Motherboard_chipset'];
		$price=$row['Motherboard_price'];
		*/

	    $update=true;
	}
	
	if(isset($_POST['update'])){
	    
	    $id=trim($_POST['id']);
		$role=trim($_POST['role']);
		$first=trim($_POST['first']);
		$middle=trim($_POST['middle']);
		$last=trim($_POST['last']);
		$email=trim($_POST['email']);
		$login=trim($_POST['username']);
		$password=trim($_POST['password']);
		$created_at=trim($_POST['created_at']);
		
		$query="UPDATE users SET 
		users_role_id=?,
		users_first_name=?,
		users_middle_name=?,
		users_last_name=?,
		users_email=?,
		users_username=?,
		users_password=?,
		created_at=? 
		WHERE users_user_id=?";
		$stmt=$conn->prepare($query);
		$stmt->bind_param("isssssssi",$role,$first,$middle,$last,$email,$login,$password,$created_at,$id);
		$stmt->execute();

		$_SESSION['response']="Выбранный пользователь ".$login." успешно обновлен.";
		$_SESSION['res_type']="info";
		header('location:CRUD_Users.php');
	}
	
	
	
	
	if(isset($_POST['cancel'])){	
		$login=trim($_POST['username']);	
	    $_SESSION['response']="Обновление пользователя ".$login." отменено.";
		$_SESSION['res_type']="danger";
		header('location:CRUD_Users.php');
	}
	
    mysqli_close($link);
?>