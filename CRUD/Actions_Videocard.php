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
	$tgp="";
	$price="";

	if(isset($_POST['add'])){
        $repeat=0;
		$model=trim($_POST['model']);
		$brend=trim($_POST['brend']);
		$freq_base=trim($_POST['freq_base']);
		$freq_boost=trim($_POST['freq_boost']);
		$cores=trim($_POST['cores']);
		$ram_type=trim($_POST['ram_type']);
		$ram_size=trim($_POST['ram_size']);
		$tgp=trim($_POST['tgp']);
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

        $query="INSERT INTO Videocard
			(Videocard_model,
			Videocard_brend,
			Videocard_freqBase,
			Videocard_freqBoost,
			Videocard_cores,
			Videocard_ramType,
			Videocard_ramSize,
			Videocard_tdp,
			Videocard_price,
			Videocard_imageName,Videocard_imageType,Videocard_imageData) 
		VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
		
		$query1="SELECT * FROM Videocard WHERE Videocard_model=?";
		$stmt=$conn->prepare($query1);
		$stmt->bind_param("s",$model);
		$stmt->execute();
		$result=$stmt->get_result();
		
		while ($row = $result->fetch_assoc()) {
		    if(trim($_POST['model'])==$row['Videocard_model'])
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
						$stmt->bind_param("siiiiiiidssb",$model,$brend,$freq_base,$freq_boost,$cores,$ram_type,$ram_size,$tgp,$price,$fileName,$fileType,$fileData);
						$stmt->execute();
						
						$query_image="UPDATE `Videocard` SET `Videocard_imageData` = '".chunk_split(base64_encode($fileData))."' WHERE `Videocard`.`Videocard_model` = '".$model."'";
						$stmt=$conn->prepare($query_image);
						$stmt->execute();
						
						header('location:CRUD_Videocard.php');
						$_SESSION['response']="Добавление новой записи ".$model." в базу данных выполнено успешно.";
						$_SESSION['res_type']="info";
					}
					else
					{
						header('location:CRUD_Videocard.php');
						$_SESSION['response']="Файл слишком большой.";
						$_SESSION['res_type']="danger";
					}
				}
				else
				{
					header('location:CRUD_Videocard.php');
					$_SESSION['response']="Возникла ошибка при загрузке файла.";
					$_SESSION['res_type']="danger";
				}
			}
			else
			{
				header('location:CRUD_Videocard.php');
				$_SESSION['response']="Добавьте изображение подходящего расширения: 'jpg', 'jpeg', 'png'.";
				$_SESSION['res_type']="danger";
			}
        }
		else 
		{    		
            header('location:CRUD_Videocard.php');
    		$_SESSION['response']="Добавление не выполнено. Модель ".$model." уже есть в базе данных.";
    		$_SESSION['res_type']="danger";
        }
	}
	
	
	
	
	if(isset($_GET['delete'])){
		$id=$_GET['delete'];
		$query="SELECT Videocard_model FROM Videocard WHERE Videocard_id=?";
		$stmt=$conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$result=$stmt->get_result();
		$row=$result->fetch_assoc();
		$model=$row['Videocard_model'];
		
		$query="DELETE FROM Videocard WHERE Videocard_id=?";
		$stmt=$conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();

		header('location:CRUD_Videocard.php');
		$_SESSION['response']="Выбранная Вами запись ".$model." успешно удалена из текущей таблицы.";
		$_SESSION['res_type']="danger";
	}
	
	
	
	
	if(isset($_GET['edit'])){
		$id=$_GET['edit'];
		$query="SELECT * FROM Videocard WHERE Videocard_id=?";
		$stmt=$conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$result=$stmt->get_result();
		$row=$result->fetch_assoc();

		$id=$row['Videocard_id'];
		$model=$row['Videocard_model'];
		$brend=$row['Videocard_brend'];
		$freq_base=$row['Videocard_freqBase'];
		$freq_boost=$row['Videocard_freqBoost'];
		$cores=$row['Videocard_cores'];
		$ram_type=$row['Videocard_ramType'];
		$ram_size=$row['Videocard_ramSize'];
		$tgp=$row['Videocard_tdp'];
		$price=$row['Videocard_price'];
		$fileName      = $row['Videocard_imageName'];;
		$fileType      = $row['Videocard_imageType'];
		$fileData      = $row['Videocard_imageData'];
		
	    $update=true;
		$_SESSION['response']="Данные записи ".$model." перенесены для редактирования в форму для обновления.";
		$_SESSION['res_type']="warning";
	}
	
	
	
	
	if(isset($_POST['update'])){
	    $id=trim($_POST['id']);
		$model=trim($_POST['model']);
		$brend=trim($_POST['brend']);
		$freq_base=trim($_POST['freq_base']);
		$freq_boost=trim($_POST['freq_boost']);
		$cores=trim($_POST['cores']);
		$ram_type=trim($_POST['ram_type']);
		$ram_size=trim($_POST['ram_size']);
		$tgp=trim($_POST['tgp']);
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
		
		$query="UPDATE Videocard SET 
			Videocard_model=?,
			Videocard_brend=?,
			Videocard_freqBase=?,
			Videocard_freqBoost=?,
			Videocard_cores=?,
			Videocard_ramType=?,
			Videocard_ramSize=?,
			Videocard_tdp=?,
			Videocard_price=?,
			Videocard_imageName=?, Videocard_imageType=?, Videocard_imageData=?
		WHERE Videocard_id=?";
		
		$query1="SELECT * FROM Videocard WHERE Videocard_model=?";
		$stmt=$conn->prepare($query1);
		$stmt->bind_param("s",$model);
		$stmt->execute();
		
		$result=$stmt->get_result();
		while ($row = $result->fetch_assoc()) {
		    if(trim($_POST['model'])==$row['Videocard_model'])
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
						$stmt->bind_param("siiiiiiidssbi",$model,$brend,$freq_base,$freq_boost,$cores,$ram_type,$ram_size,$tgp,$price,$fileName,$fileType,$fileData,$id);
						$stmt->execute();

						$query_image="UPDATE `Videocard` SET `Videocard_imageData` = '".chunk_split(base64_encode($fileData))."' WHERE `Videocard`.`Videocard_model` = '".$model."'";
						$stmt=$conn->prepare($query_image);
						$stmt->execute();
						
						$_SESSION['response']="Выбранная Вами запись ".$model." успешно обновлена.";
						$_SESSION['res_type']="info";
						header('location:CRUD_Videocard.php');
					}
					else
					{
						header('location:CRUD_Videocard.php');
						$_SESSION['response']="Файл слишком большой.";
						$_SESSION['res_type']="danger";
					}
				}
				else
				{
					header('location:CRUD_Videocard.php');
					$_SESSION['response']="Возникла ошибка при загрузке файла.";
					$_SESSION['res_type']="danger";
				}
			}
			else
			{
				header('location:CRUD_Videocard.php');
				$_SESSION['response']="Добавьте изображение подходящего расширения: 'jpg', 'jpeg', 'png'.";
				$_SESSION['res_type']="danger";
			}
        }
		else 
		{    		
            header('location:CRUD_Videocard.php');
    		$_SESSION['response']="Добавление не выполнено. Модель ".$model." уже есть в базе данных.";
    		$_SESSION['res_type']="danger";
        }
	}
	
	if(isset($_POST['cancel'])){	
		$model=trim($_POST['model']);	
	    $_SESSION['response']="Обновление записи ".$model." отменено.";
		$_SESSION['res_type']="danger";
		header('location:CRUD_Videocard.php');
	}
	
mysqli_close($link);
?>