<?php
session_start(); // начинаем новую сессию
// извлекаем переменные из сессии, если были установлены
$link = $_SESSION["link"];
$username = $_SESSION["username"];
$role_id = $_SESSION["role_id"];
$role_name = $_SESSION["role_name"];
$db = $_SESSION["db"];
    
include('../Connect_To_Database.php');
include('Actions_Motherboard.php');

$conn = mysqli_connect($host, $user, $pass, $db) 
    or die("Ошибка " . mysqli_error($conn));
    mysqli_set_charset($conn, 'utf8');
    
$query_ALL = "SELECT * FROM Motherboard;";
$res_ALL = mysqli_query($conn, $query_ALL) or die("Error" . mysqli_error($conn));

require_once "../Forms/Login_Role.php";
include('../Forms/Login_Config.php');
$thread_id = mysqli_thread_id($link);

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
{
    header("location: ../Forms/Login.php");
}

if($role_id == '0' || $role_id == '1')
{	// запоминаем ошибку для отображения в файле CRUD_error.php
    $_SESSION["crud_error"] = "Недостаточно прав для добавления данных!!!";
    echo '<h1>Недостаточно прав для добавления данных!<h1>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <title>Редактирование таблицы «Материнские платы»</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.css" />
  <script type="text/javascript" src="JsTablePagination.js"></script>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<!--МЕНЮ-->
<?php
    $CD_path = '../';
    $h_path = '../Load/';
	if($role_id >= 2)
		include($h_path.'menu_Admin.php');
	else
		include($h_path.'menu.php');
?>

<body style="background: #042630;">

<div style=" margin-bottom: -5px;background: rgb(0,86,110); background: linear-gradient(90deg, rgb(240, 195, 132) 0%, rgb(3, 162, 211) 40%, rgb(0, 154, 201) 60%,  rgb(245, 189, 112) 100%);"><div class="container"><br><br></div></div>
  
<div style="background: rgb(0,86,110); background: linear-gradient(90deg, rgb(214, 152, 64) 0%, rgb(0, 106, 138) 40%, rgb(0, 106, 138) 60%,  rgb(214, 152, 64) 100%);"><div class="container-fluid" style="color:#fff;"><br>
      
    <div class="row justify-content-center" style="color:#fff;">
      <div class="col-md-10" style="color:#fff;">
        <h1 style="color: rgb(255, 255, 255);  text-align: center;">Редактирование таблицы «Материнские платы»</h1>
        <hr color="#fff" style="color:#fff;">
        <?php if (isset($_SESSION['response'])) { ?>
        <div class="alert alert-<?= $_SESSION['res_type']; ?> alert-dismissible text-center" style="color:#000;">
          <button type="button" class="close" data-dismiss="alert" style="color:#000;">&times;</button>
          <b><?= $_SESSION['response']; ?></b>
        </div>
        <?php } unset($_SESSION['response']); ?>
      </div>
    </div>
    
    <div class="row" style="color:#fff;">
      <div class="col-md-9" style="color:#fff;">
        <?php          
        $query = '
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
       ORDER BY Motherboard_model';

          $stmt = $conn->prepare($query);
          $stmt->execute();
          $result = $stmt->get_result();
        ?>
        
        <table class="table table-hover" id="data-table">
          <thead>
            <tr style="color:#ddd;">
              <th></th>
              <th>Наименование модели</th>
              <th>Бренд</th>
              <th>Разъемы SATA</th>
              <th>Min частота ОЗУ</th>
              <th>Max частота ОЗУ</th>              
              <th>Сокет</th>
              <th>Чипсет</th>
              <th>Цена</th>
              <th>Действия</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr style="color:#fff;">
              <td style="background:#fff; text-align: center;"><?php echo "<embed src='data:".$row['Motherboard_imageType'].";base64,".$row['Motherboard_imageData']."' height='40' style='margin:-15px;' />"?></td>
              <td><?= $row['Motherboard_model']; ?></td>
              <td><?= $row['Brend_name']; ?></td>
              <td><?= $row['Moth_SATA_value']; ?></td>
              <td><?= $row['Moth_RamFreqMin_value']; ?></td>
              <td><?= $row['Moth_RamFreqMax_value']; ?></td>
              <td><?= $row['Soсket_name']; ?></td>
              <td><?= $row['Chipset_name']; ?></td>
              <td><?= $row['Motherboard_price']; ?></td>
              <td>
                <a href="CRUD_Motherboard.php?edit=<?= $row['Motherboard_id']; ?>" class="plan-button badge badge-success p-2">Обновить</a> 
                <a href="Actions_Motherboard.php?delete=<?= $row['Motherboard_id']; ?>" class="plan-button badge badge-danger p-2" onclick="return confirm('Вы уверены, что хотите удалить текущую запись?');">Удалить</a>
              </td>
            </tr>
            <?php }
            $query_brend = "SELECT * FROM Brend;"; $res_brend = mysqli_query($conn, $query_brend) or die("Error" . mysqli_error($conn));
            $query_sata = "SELECT * FROM Moth_SATA;"; $res_sata = mysqli_query($conn, $query_sata) or die("Error" . mysqli_error($conn));
            $query_freq_min = "SELECT * FROM Moth_RamFreqMin;"; $res_freq_min = mysqli_query($conn, $query_freq_min) or die("Error" . mysqli_error($conn));
            $query_freq_max = "SELECT * FROM Moth_RamFreqMax"; $res_freq_max = mysqli_query($conn, $query_freq_max) or die("Error" . mysqli_error($conn));
            $query_socket = "SELECT * FROM Soсket"; $res_socket = mysqli_query($conn, $query_socket) or die("Error" . mysqli_error($conn));
            $query_chipset = "SELECT * FROM Chipset;"; $res_chipset = mysqli_query($conn, $query_chipset) or die("Error" . mysqli_error($conn));
            ?>
          </tbody>
        </table>
      </div>     
      
      <div class="col-md-3">
          <br>
            <?php if ($update == true) { ?>
            <h3 class="text-center">Обновление выбранной записи</h3><hr>
            <?php } else { ?>
            <h3 class="text-center">Добавление новой записи</h3><hr>
            <?php } ?>
        <form action="Actions_Motherboard.php" method="post" enctype="multipart/form-data">
            
		  <div class="form-group">
			<input type="hidden" name="id" value="<?= $id; ?>">
          </div>
			 
          <div class="form-group">
            <input type="text" name="model" value="<?= $model; ?>" class="form-control" placeholder="Введите наименование модели" required>
          </div>
          
          <div class="form-group">
            <select name="brend" class="form-control" value="<?= $brend; ?>" required>
                            <?php if ($update == false) { ?>
                            <option disabled selected value> -- Выберите производителя -- </option>
                            <?php } ?>
                            <?php while($row1 = mysqli_fetch_array($res_brend)):;?>
                            <option value="<?php echo $row1[0];?>" 
                                <?php if ($row1[0] == $brend) { ?> 
                                    selected
                                <?php } ?>>
                                <?php echo $row1[1];?>
							</option>
                            <?php endwhile;?>
            </select>
            <span class="help-block"><?php echo $brend_err;?></span>
          </div>
          
          <div class="form-group">
            <select name="sata" class="form-control" value="<?= $sata; ?>" required>
                            <?php if ($update == false) { ?>
                            <option disabled selected value> -- Выберите разъемы SATA -- </option>
                            <?php } ?>
                            <?php while($row1 = mysqli_fetch_array($res_sata)):;?>
                            <option value="<?php echo $row1[0];?>" 
                                <?php if ($row1[0] == $sata) { ?> 
                                    selected
                                <?php } ?>>
                                <?php echo $row1[1];?></option>
                            <?php endwhile;?>
            </select>
            <span class="help-block"><?php echo $freq_base_err;?></span>
          </div>
          
          <div class="form-group">
            <select name="freq_min" class="form-control" value="<?= $freq_min; ?>" required>
                            <?php if ($update == false) { ?>
                            <option disabled selected value> -- Минимальная поддерживаемая частота ОЗУ -- </option>
                            <?php } ?>
                            <?php while($row1 = mysqli_fetch_array($res_freq_min)):;?>
                            <option value="<?php echo $row1[0];?>" 
                                <?php if ($row1[0] == $freq_min) { ?> 
                                    selected
                                <?php } ?>>
                                <?php echo $row1[1];?></option>
                            <?php endwhile;?>
            </select>
          </div>
          
          <div class="form-group">
            <select name="freq_max" class="form-control" value="<?= $freq_max; ?>" required> 
                            <?php if ($update == false) { ?>
                            <option disabled selected value> -- Максимальная поддерживаемая частота ОЗУ -- </option>
                            <?php } ?>
                            <?php while($row1 = mysqli_fetch_array($res_freq_max)):;?>
                            <option value="<?php echo $row1[0];?>" 
                                <?php if ($row1[0] == $freq_max) { ?> 
                                    selected
                                <?php } ?>>
                                <?php echo $row1[1];?></option>
                            <?php endwhile;?>
            </select>
          </div>
          
          <div class="form-group">
            <select name="socket" class="form-control" value="<?= $socket; ?>" required>
                            <?php if ($update == false) { ?>
                            <option disabled selected value> -- Выберите сокет -- </option>
                            <?php } ?>
                            <?php while($row1 = mysqli_fetch_array($res_socket)):;?>
                            <option value="<?php echo $row1[0];?>" 
                                <?php if ($row1[0] == $socket) { ?> 
                                    selected
                                <?php } ?>>
                                <?php echo $row1[1];?></option>
                            <?php endwhile;?>
            </select>
          </div>
          
          <div class="form-group">
            <select name="chipset" class="form-control" value="<?= $chipset; ?>" required>
                            <?php if ($update == false) { ?>
                            <option disabled selected value> -- Выберите чипсет -- </option>
                            <?php } ?>
                            <?php while($row1 = mysqli_fetch_array($res_chipset)):;?>
                            <option value="<?php echo $row1[0];?>" 
                                <?php if ($row1[0] == $chipset) { ?> 
                                    selected
                                <?php } ?>>
                                <?php echo $row1[1];?></option>
                            <?php endwhile;?>
            </select>
          </div>
          
          <div class="form-group">
            <input type="number" name="price" value="<?= $price; ?>" class="form-control" placeholder="Введите цену" required>
          </div>
          
          <div class="form-group">
            <input style="color:#fff;" type="file" name="image" class="file" >
          </div>
          
          <div class="form-group">
            <?php if ($update == true) { ?><hr>
            <input type="submit" name="update" class="btn btn-success btn-block" value="Обновить запись">
            <input type="submit" name="cancel" class="btn btn-danger btn-block" value="Отмена">
            <?php } else { ?><hr>
            <input type="submit" name="add" class="btn btn-primary btn-block" value="Добавить запись">
            <?php } ?>
          </div>
          
        </form>
      </div>
      
    </div>
  </div>
  <br>
</div>

<div style="background: rgb(0,86,110); background: linear-gradient(90deg, rgb(240, 195, 132) 0%, rgb(3, 162, 211) 40%, rgb(0, 154, 201) 60%,  rgb(245, 189, 112) 100%);"><div class="container"><br></div></div>
    

<!--ПОДВАЛ-->
<?php
	if($role_id == 1) include($h_path.'footer_User.php'); 
	else if ($role_id >= 2) include($h_path.'footer_Admin.php');
	else include($h_path.'footer.php');
?>
  
<script type="text/javascript">
  $(document).ready(function(){
    $('#data-table').DataTable({
      paging: true
    });
});
</script>
  
</body>
</html>