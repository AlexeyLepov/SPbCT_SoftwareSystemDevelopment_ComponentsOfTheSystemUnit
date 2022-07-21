<?php
session_start(); // начинаем новую сессию
// извлекаем переменные из сессии, если были установлены
$link = $_SESSION["link"];
$username = $_SESSION["username"];
$role_id = $_SESSION["role_id"];
$role_name = $_SESSION["role_name"];
$db = $_SESSION["db"];
    
include('../Connect_To_Database.php');
include('Actions_Videocard.php');

$conn = mysqli_connect($host, $user, $pass, $db) 
    or die("Ошибка " . mysqli_error($conn));
    mysqli_set_charset($conn, 'utf8');
    
$query_ALL = "SELECT * FROM Videocard;";
$res_ALL = mysqli_query($conn, $query_ALL) or die("Error" . mysqli_error($conn));
// Include config file (подключаем файлы)
require_once "../Forms/Login_Role.php";
include('../Forms/Login_Config.php');
$thread_id = mysqli_thread_id($link);

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
{
    header("location: ../Forms/Login.php"); // перенаправляем на страницу входа
}
// Проверка роли: 0 - user, 1 - admin 
if($role_id == '0' || $role_id == '1') // если не админ (0 - user, 1 - admin, иначе guest)
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
  <title>Редактирование таблицы «Видеокарты»</title>
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
        <h1 style="color: rgb(255, 255, 255);  text-align: center;">Редактирование таблицы «Видеокарты»</h1>
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
			   Videocard_id,
			   Videocard_model,
			   B.Brend_name,
			   Video_FreqBase.Video_FreqBase_value,
			   Video_FreqBoost.Video_FreqBoost_value,
			   Video_Cores.Video_Cores_value,
			   Video_RamType.Video_RamType_value,
			   Video_RamSize.Video_RamSize_value,
			   Video_TDP.Video_TDP_value,
			   Videocard_price,
			   Videocard_imageName,Videocard_imageType,Videocard_imageData
		   FROM Videocard                  AS V
				INNER JOIN Brend AS B      ON B.Brend_id = V.Videocard_brend
				INNER JOIN Video_FreqBase  ON Video_FreqBase.Video_FreqBase_id = V.Videocard_freqBase
				INNER JOIN Video_FreqBoost ON Video_FreqBoost.Video_FreqBoost_id = V.Videocard_freqBoost
				INNER JOIN Video_Cores     ON Video_Cores.Video_Cores_id = V.Videocard_cores
				INNER JOIN Video_RamType   ON Video_RamType.Video_RamType_id = V.Videocard_ramType
				INNER JOIN Video_RamSize   ON Video_RamSize.Video_RamSize_id = V.Videocard_ramSize
				INNER JOIN Video_TDP       ON Video_TDP.Video_TDP_id = V.Videocard_tdp
          ORDER BY Videocard_model';

          $stmt = $conn->prepare($query);
          $stmt->execute();
          $result = $stmt->get_result();
        ?>
        
        <table class="table table-hover" id="data-table">
          <thead>
            <tr style="color:#ddd;">
              <th></th><th>Наименование модели</th>
              <th>Вендор</th>
              <th>Частота</th>
              <th>Boost</th>
              <th>Ядра</th>              
              <th>Тип памяти</th>
              <th>Размер</th>
              <th>TGP</th>
              <th>Цена</th>
              <th>Действия</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr style="color:#fff;">
              <td style="background:#fff; text-align: center;"><?php echo "<embed src='data:".$row['Videocard_imageType'].";base64,".$row['Videocard_imageData']."' height='40' style='margin:-15px;' />"?></td>
              <td><?= $row['Videocard_model']; ?></td>
              <td><?= $row['Brend_name']; ?></td>
              <td><?= $row['Video_FreqBase_value']; ?></td>
              <td><?= $row['Video_FreqBoost_value']; ?></td>
              <td><?= $row['Video_Cores_value']; ?></td>
              <td><?= $row['Video_RamType_value']; ?></td>
              <td><?= $row['Video_RamSize_value']; ?></td>
              <td><?= $row['Video_TDP_value']; ?></td>
              <td><?= $row['Videocard_price']; ?></td>
              <td>
                <a href="CRUD_Videocard.php?edit=<?= $row['Videocard_id']; ?>" class="plan-button badge badge-success p-2">Обновить</a> 
                <a href="Actions_Videocard.php?delete=<?= $row['Videocard_id']; ?>" class="plan-button badge badge-danger p-2" onclick="return confirm('Вы уверены, что хотите удалить текущую запись?');">Удалить</a></td>
            </tr>
            <?php }
            $query_brend = "SELECT * FROM Brend;"; $res_brend = mysqli_query($conn, $query_brend) or die("Error" . mysqli_error($conn));
            $query_freq_base = "SELECT * FROM Video_FreqBase;"; $res_freq_base = mysqli_query($conn, $query_freq_base) or die("Error" . mysqli_error($conn));
            $query_freq_boost = "SELECT * FROM Video_FreqBoost;"; $res_freq_boost = mysqli_query($conn, $query_freq_boost) or die("Error" . mysqli_error($conn));
            $query_cores = "SELECT * FROM Video_Cores"; $res_cores = mysqli_query($conn, $query_cores) or die("Error" . mysqli_error($conn));
            $query_ramType = "SELECT * FROM Video_RamType;"; $res_ramType = mysqli_query($conn, $query_ramType) or die("Error" . mysqli_error($conn));
            $query_ramSize = "SELECT * FROM Video_RamSize"; $res_ramSize = mysqli_query($conn, $query_ramSize) or die("Error" . mysqli_error($conn));
            $query_tgp = "SELECT * FROM Video_TDP"; $res_tgp = mysqli_query($conn, $query_tgp) or die("Error" . mysqli_error($conn));?>
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
        <form action="Actions_Videocard.php" method="post" enctype="multipart/form-data">
            
          <div class="form-group">
            <input type="hidden" name="id" value="<?= $id; ?>">
          </div>
          
          <div class="form-group">
            <input type="text" name="model" value="<?= $model; ?>" class="form-control" placeholder="Введите наименование модели" required>
          </div>
          
          <div class="form-group">
            <select name="brend" class="form-control" value="<?= $brend; ?>" required>
                            <?php if ($update == false) { ?>
							<option disabled selected value> -- Выберите производителя-вендора -- </option>
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
            <select name="freq_base" class="form-control" value="<?= $freq_base; ?>" required>
                            <?php if ($update == false) { ?>
                            <option disabled selected value> -- Выберите базовую частоту -- </option>
                            <?php } ?>
                            <?php while($row1 = mysqli_fetch_array($res_freq_base)):;?>
                            <option value="<?php echo $row1[0];?>" 
                                <?php if ($row1[0] == $freq_base) { ?> 
                                    selected
                                <?php } ?>>
                                <?php echo $row1[1];?></option>
                            <?php endwhile;?>
            </select>
            <span class="help-block"><?php echo $freq_base_err;?></span>
          </div>
          
          <div class="form-group">
            <select name="freq_boost" class="form-control" value="<?= $freq_boost; ?>" required>
                            <?php if ($update == false) { ?>
                            <option disabled selected value> -- Выберите частоту в режиме Boost -- </option>
                            <?php } ?>
                            <?php while($row1 = mysqli_fetch_array($res_freq_boost)):;?>
                            <option value="<?php echo $row1[0];?>" 
                                <?php if ($row1[0] == $freq_boost) { ?> 
                                    selected
                                <?php } ?>>
                                <?php echo $row1[1];?></option>
                            <?php endwhile;?>
            </select>
          </div>
          
          <div class="form-group">
            <select name="cores" class="form-control" value="<?= $cores; ?>" required> 
                            <?php if ($update == false) { ?>
                            <option disabled selected value> -- Выберите количество ядер -- </option>
                            <?php } ?>
                            <?php while($row1 = mysqli_fetch_array($res_cores)):;?>
                            <option value="<?php echo $row1[0];?>" 
                                <?php if ($row1[0] == $cores) { ?> 
                                    selected
                                <?php } ?>>
                                <?php echo $row1[1];?></option>
                            <?php endwhile;?>
            </select>
          </div>
          
          <div class="form-group">
            <select name="ram_type" class="form-control" value="<?= $ram_type; ?>" required>
                            <?php if ($update == false) { ?>
                            <option disabled selected value> -- Выберите тип памяти -- </option>
                            <?php } ?>
                            <?php while($row1 = mysqli_fetch_array($res_ramType)):;?>
                            <option value="<?php echo $row1[0];?>" 
                                <?php if ($row1[0] == $ram_type) { ?> 
                                    selected
                                <?php } ?>>
                                <?php echo $row1[1];?></option>
                            <?php endwhile;?>
            </select>
          </div>
          
        <div class="form-group">
            <select name="ram_size" class="form-control" value="<?= $ram_size; ?>" required>
                            <?php if ($update == false) { ?>
                            <option disabled selected value> -- Выберите размер памяти -- </option>
                            <?php } ?>
                            <?php while($row1 = mysqli_fetch_array($res_ramSize)):;?>
                            <option value="<?php echo $row1[0];?>" 
                                <?php if ($row1[0] == $ram_size) { ?> 
                                    selected
                                <?php } ?>>
                                <?php echo $row1[1];?></option>
                            <?php endwhile;?>
            </select>
          </div>
          
          <div class="form-group">
            <select name="tgp" class="form-control" value="<?= $tgp; ?>" required>
                            <?php if ($update == false) { ?>
                            <option disabled selected value> -- Выберите TGP видеокарты -- </option>
                            <?php } ?>
                            <?php while($row1 = mysqli_fetch_array($res_tgp)):;?>
                            <option value="<?php echo $row1[0];?>" 
                                <?php if ($row1[0] == $tgp) { ?> 
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
$(document).ready(function() {
    $('#data-table').DataTable({
      paging: true
    });
});
</script>
  
</body>
</html>