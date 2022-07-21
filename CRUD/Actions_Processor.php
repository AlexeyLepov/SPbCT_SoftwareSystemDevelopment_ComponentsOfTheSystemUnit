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
	$freq_base="";
	$freq_boost="";
	$cores="";
	$socket="";
	$tdp="";
	$price="";

	if(isset($_POST['add'])){
        $repeat=0;
		$model=trim($_POST['model']);
		$brend=trim($_POST['brend']);
		$freq_base=trim($_POST['freq_base']);
		$freq_boost=trim($_POST['freq_boost']);
		$cores=trim($_POST['cores']);
		$socket=trim($_POST['socket']);
		$tdp=trim($_POST['tdp']);
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
		
        $query="INSERT INTO Processor
			(Processor_model,
			Processor_brend,
			Processor_freqBase,
			Processor_freqBoost,
			Processor_cores,
			Processor_socket,
			Processor_tdp,
			Processor_price,
			Processor_imageName,
			Processor_imageType,
			Processor_imageData) 
		VALUES (?,?,?,?,?,?,?,?,?,?,?)";
		
		$query1="SELECT * FROM Processor WHERE Processor_model=?";
		$stmt=$conn->prepare($query1);
		$stmt->bind_param("s",$model);
		$stmt->execute();
		$result=$stmt->get_result();
		
		while ($row = $result->fetch_assoc()) {
		    if(trim($_POST['model'])==$row['Processor_model'])
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
						$stmt->bind_param("siiiiiidssb",$model,$brend,$freq_base,$freq_boost,$cores,$socket,$tdp,$price,$fileName,$fileType,$fileData);
						$stmt->execute();
						
						$query_image="UPDATE `Processor` SET `Processor_imageData` = '".chunk_split(base64_encode($fileData))."' WHERE `Processor`.`Processor_model` = '".$model."'";
						$stmt=$conn->prepare($query_image);
						$stmt->execute();
						
						header('location:CRUD_Processor.php');
						$_SESSION['response']="Добавление новой записи ".$model." в базу данных выполнено успешно.";
						$_SESSION['res_type']="info";
					}
					else
					{
						header('location:CRUD_Processor.php');
						$_SESSION['response']="Файл слишком большой.";
						$_SESSION['res_type']="danger";
					}
				}
				else
				{
					header('location:CRUD_Processor.php');
					$_SESSION['response']="Возникла ошибка при загрузке файла.";
					$_SESSION['res_type']="danger";
				}
			}
			else
			{
				header('location:CRUD_Processor.php');
				$_SESSION['response']="Добавьте изображение подходящего расширения: 'jpg', 'jpeg', 'png'.";
				$_SESSION['res_type']="danger";
			}
        }
		else 
		{    		
            header('location:CRUD_Processor.php');
    		$_SESSION['response']="Добавление не выполнено. Модель ".$model." уже есть в базе данных.";
    		$_SESSION['res_type']="danger";
        }
	}

	
	
	
	if(isset($_GET['delete'])){
		$id=$_GET['delete'];
		$query="SELECT Processor_model FROM Processor WHERE Processor_id=?";
		$stmt=$conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$result=$stmt->get_result();
		$row=$result->fetch_assoc();
		$model=$row['Processor_model'];

		$query1="DELETE FROM Processor WHERE Processor_id=?";
		$stmt=$conn->prepare($query1);
		$stmt->bind_param("i",$id);
		$stmt->execute();

		header('location:CRUD_Processor.php');
		$_SESSION['response']="Выбранная запись ".$model." успешно удалена из базы данных.";
		$_SESSION['res_type']="danger";
	}
	
	
	
	
	if(isset($_GET['edit'])){
		$id=$_GET['edit'];
		$query="SELECT * FROM Processor WHERE Processor_id=?";
		$stmt=$conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$result=$stmt->get_result();
		$row=$result->fetch_assoc();

		$id=$row['Processor_id'];
		$model=$row['Processor_model'];
		$brend=$row['Processor_brend'];
		$freq_base=$row['Processor_freqBase'];
		$freq_boost=$row['Processor_freqBoost'];
		$cores=$row['Processor_cores'];
		$socket=$row['Processor_socket'];
		$tdp=$row['Processor_tdp'];
		$price=$row['Processor_price'];
		
		$fileName      = $row['Processor_imageName'];;
		$fileType      = $row['Processor_imageType'];
		$fileData      = $row['Processor_imageData'];

	    $update=true;
		$_SESSION['response']="Данные записи ".$model." перенесены для редактирования в форму для обновления записей.";
		$_SESSION['res_type']="warning";
	}
	
	
	
	
	if(isset($_POST['update'])){
        $id=trim($_POST['id']);
		$model=trim($_POST['model']);
		$brend=trim($_POST['brend']);
		$freq_base=trim($_POST['freq_base']);
		$freq_boost=trim($_POST['freq_boost']);
		$cores=trim($_POST['cores']);
		$socket=trim($_POST['socket']);
		$tdp=trim($_POST['tdp']);
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

		$query="UPDATE Processor SET 
			Processor_model=?,
			Processor_brend=?,
			Processor_freqBase=?,
			Processor_freqBoost=?,
			Processor_cores=?,
			Processor_socket=?,
			Processor_tdp=?,
			Processor_price=?,
			Processor_imageName=?,
			Processor_imageType=?,
			Processor_imageData=?
		WHERE Processor_id=?";
		
		$query1="SELECT * FROM Processor WHERE Processor_model=?";
		$stmt=$conn->prepare($query1);
		$stmt->bind_param("s",$model);
		$stmt->execute();
		
		$result=$stmt->get_result();
		while ($row = $result->fetch_assoc()) {
		    if(trim($_POST['model'])==$row['Processor_model'])
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
						$stmt->bind_param("siiiiiidssbi",$model,$brend,$freq_base,$freq_boost,$cores,$socket,$tdp,$price,$fileName,$fileType,$fileData,$id);
						$stmt->execute();

						$query_image="UPDATE `Processor` SET `Processor_imageData` = '".chunk_split(base64_encode($fileData))."' WHERE `Processor`.`Processor_model` = '".$model."'";
						$stmt=$conn->prepare($query_image);
						$stmt->execute();
						
						$_SESSION['response']="Выбранная Вами запись ".$model." успешно обновлена.";
						$_SESSION['res_type']="info";
						header('location:CRUD_Processor.php');
					}
					else
					{
						header('location:CRUD_Processor.php');
						$_SESSION['response']="Файл слишком большой.";
						$_SESSION['res_type']="danger";
					}
				}
				else
				{
					header('location:CRUD_Processor.php');
					$_SESSION['response']="Возникла ошибка при загрузке файла.";
					$_SESSION['res_type']="danger";
				}
			}
			else
			{
				header('location:CRUD_Processor.php');
				$_SESSION['response']="Добавьте изображение подходящего расширения: 'jpg', 'jpeg', 'png'.";
				$_SESSION['res_type']="danger";
			}
        }
		else 
		{    		
            header('location:CRUD_Processor.php');
    		$_SESSION['response']="Добавление не выполнено. Модель ".$model." уже есть в базе данных.";
    		$_SESSION['res_type']="danger";
        }
	}
	
	if(isset($_POST['cancel'])){		
		$model=trim($_POST['model']);
	    $_SESSION['response']="Обновление записи ".$model." отменено.";
		$_SESSION['res_type']="danger";
		header('location:CRUD_Processor.php');
	}

mysqli_close($link);
?>