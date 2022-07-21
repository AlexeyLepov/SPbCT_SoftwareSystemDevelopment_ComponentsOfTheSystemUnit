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
	$model="";
	$brend="";
	$sata="";
	$freq_min="";
	$freq_max="";
	$socket="";
	$chipset="";
	$price="";

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
		
		$fileName      = $_FILES['image']['name'];
		$fileTmpName   = $_FILES['image']['tmp_name'];
		$fileSize      = $_FILES['image']['size'];
		$fileError     = $_FILES['image']['error'];
		$fileType      = $_FILES['image']['type'];
		$fileExt       = explode('.', $fileName);
		$fileActualExt = strtolower(end($fileExt));
		$allowed       = array('jpg', 'jpeg', 'png');
		$fileData      = file_get_contents($fileTmpName);

        $query="INSERT INTO Motherboard
			(Motherboard_model,
			Motherboard_brend,
			Motherboard_sata,
			Motherboard_ram_freqMin,
			Motherboard_ram_freqMax,
			Motherboard_socket,
			Motherboard_chipset,
			Motherboard_price,
			Motherboard_imageName,
			Motherboard_imageType,
			Motherboard_imageData) 
		VALUES (?,?,?,?,?,?,?,?,?,?,?)";
		
		$query1="SELECT * FROM Motherboard WHERE Motherboard_model=?";
		$stmt=$conn->prepare($query1);
		$stmt->bind_param("s",$model);
		$stmt->execute();
		$result=$stmt->get_result();
		
		while ($row = $result->fetch_assoc()) {
		    if(trim($_POST['model'])==$row['Motherboard_model'])
		    { $repeat += 1; }
		}
		
		if ($repeat==0) 
		{
			if (in_array($fileActualExt, $allowed))
			{
				if ($fileError === 0)
				{
					if ($fileSize < 524288)
					{
						$stmt=$conn->prepare($query);
						$stmt->bind_param("siiiiiidssb",$model,$brend,$sata,$freq_min,$freq_max,$socket,$chipset,$price,$fileName,$fileType,$fileData);
						$stmt->execute();
						
						$query_image="UPDATE `Motherboard` SET `Motherboard_imageData` = '".chunk_split(base64_encode($fileData))."' WHERE `Motherboard`.`Motherboard_model` = '".$model."'";
						$stmt=$conn->prepare($query_image);
						$stmt->execute();
						
						header('location:CRUD_Motherboard.php');
						$_SESSION['response']="Добавление новой записи ".$model." в базу данных выполнено успешно.";
						$_SESSION['res_type']="info";
					}
					else
					{
						header('location:CRUD_Motherboard.php');
						$_SESSION['response']="Файл слишком большой.";
						$_SESSION['res_type']="danger";
					}
				}
				else
				{
					header('location:CRUD_Motherboard.php');
					$_SESSION['response']="Возникла ошибка при загрузке файла.";
					$_SESSION['res_type']="danger";
				}
			}
			else
			{
				header('location:CRUD_Motherboard.php');
				$_SESSION['response']="Добавьте изображение подходящего расширения: 'jpg', 'jpeg', 'png'.";
				$_SESSION['res_type']="danger";
			}
        }
		else 
		{    		
			header('location:CRUD_Motherboard.php');
    		$_SESSION['response']="Добавление не выполнено. Модель ".$model." уже есть в базе данных.";
    		$_SESSION['res_type']="danger";
        }
	}
	
	
	
	
	if(isset($_GET['delete'])){
		$id=$_GET['delete'];
		$query="SELECT Motherboard_model FROM Motherboard WHERE Motherboard_id=?";
		$stmt=$conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$result=$stmt->get_result();
		$row=$result->fetch_assoc();
		$model=$row['Motherboard_model'];
		
		$query="DELETE FROM Motherboard WHERE Motherboard_id=?";
		$stmt=$conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();

		header('location:CRUD_Motherboard.php');
		$_SESSION['response']="Выбранная Вами запись ".$model." успешно удалена из базы данных.";
		$_SESSION['res_type']="danger";
	}
	
	
	
	
	if(isset($_GET['edit'])){
		$id=$_GET['edit'];
		$query="SELECT * FROM Motherboard WHERE Motherboard_id=?";
		$stmt=$conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$result=$stmt->get_result();
		$row=$result->fetch_assoc();

		$id=$row['Motherboard_id'];
		$model=$row['Motherboard_model'];
		$brend=$row['Motherboard_brend'];
		$sata=$row['Motherboard_sata'];
		$freq_min=$row['Motherboard_ram_freqMin'];
		$freq_max=$row['Motherboard_ram_freqMax'];
		$socket=$row['Motherboard_socket'];
		$chipset=$row['Motherboard_chipset'];
		$price=$row['Motherboard_price'];

		$fileName      = $row['Motherboard_imageName'];;
		$fileType      = $row['Motherboard_imageType'];
		$fileData      = $row['Motherboard_imageData'];

	    $update=true;
		$_SESSION['response']="Данные записи ".$model." перенесены для редактирования в форму для обновления.";
		$_SESSION['res_type']="warning";
	}
	
	
	
	
	if(isset($_POST['update'])){
	    $id=trim($_POST['id']);
		$model=trim($_POST['model']);
		$brend=trim($_POST['brend']);
		$sata=trim($_POST['sata']);
		$freq_min=trim($_POST['freq_min']);
		$freq_max=trim($_POST['freq_max']);
		$socket=trim($_POST['socket']);
		$chipset=trim($_POST['chipset']);
		$price=trim($_POST['price']);
		
		$fileName      = $_FILES['image']['name'];
		$fileTmpName   = $_FILES['image']['tmp_name'];
		$fileSize      = $_FILES['image']['size'];
		$fileError     = $_FILES['image']['error'];
		$fileType      = $_FILES['image']['type'];
		$fileExt       = explode('.', $fileName);
		$fileActualExt = strtolower(end($fileExt));
		$allowed       = array('jpg', 'jpeg', 'png');
		$fileData      = file_get_contents($fileTmpName);
		
		$query="UPDATE Motherboard SET 
		Motherboard_model=?,
		Motherboard_brend=?,
		Motherboard_sata=?,
		Motherboard_ram_freqMin=?,
		Motherboard_ram_freqMax=?,
		Motherboard_socket=?,
		Motherboard_chipset=?,
		Motherboard_price=?,
		Motherboard_imageName=?,
		Motherboard_imageType=?,
		Motherboard_imageData=?
		WHERE Motherboard_id=?";
		
		$query1="SELECT * FROM Motherboard WHERE Motherboard_model=?";
		$stmt=$conn->prepare($query1);
		$stmt->bind_param("s",$model);
		$stmt->execute();
		
		$result=$stmt->get_result();
		while ($row = $result->fetch_assoc()) {
		    if(trim($_POST['model'])==$row['Motherboard_model'])
		    { $repeat += 1; }
		}
		
		if ($repeat==1) 
		{
			if (in_array($fileActualExt, $allowed))
			{
				if ($fileError === 0)
				{
					if ($fileSize < 524288)
					{			
						$stmt=$conn->prepare($query);
						$stmt->bind_param("siiiiiidssbi",$model,$brend,$sata,$freq_min,$freq_max,$socket,$chipset,$price,$fileName,$fileType,$fileData,$id);
						$stmt->execute();
		
						$query_image="UPDATE `Motherboard` SET `Motherboard_imageData` = '".chunk_split(base64_encode($fileData))."' WHERE `Motherboard`.`Motherboard_model` = '".$model."'";
						$stmt=$conn->prepare($query_image);
						$stmt->execute();
						
						$_SESSION['response']="Выбранная Вами запись ".$model." успешно обновлена.";
						$_SESSION['res_type']="info";
						header('location:CRUD_Motherboard.php');
					}
					else
					{
						header('location:CRUD_Motherboard.php');
						$_SESSION['response']="Файл слишком большой.";
						$_SESSION['res_type']="danger";
					}
				}
				else
				{
					header('location:CRUD_Motherboard.php');
					$_SESSION['response']="Возникла ошибка при загрузке файла.";
					$_SESSION['res_type']="danger";
				}
			}
			else
			{
				header('location:CRUD_Motherboard.php');
				$_SESSION['response']="Добавьте изображение подходящего расширения: 'jpg', 'jpeg', 'png'.";
				$_SESSION['res_type']="danger";
			}
        }
		else 
		{    		
            header('location:CRUD_Motherboard.php');
    		$_SESSION['response']="Добавление не выполнено. Модель ".$model." уже есть в базе данных.";
    		$_SESSION['res_type']="danger";
        }
	}
	
	if(isset($_POST['cancel'])){	
		$model=trim($_POST['model']);	
	    $_SESSION['response']="Обновление записи ".$model." отменено.";
		$_SESSION['res_type']="danger";
		header('location:CRUD_Motherboard.php');
	}
	
mysqli_close($link);
?>