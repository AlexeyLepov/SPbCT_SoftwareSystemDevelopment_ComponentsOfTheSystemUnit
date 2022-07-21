<?php
session_start();

$link = $_SESSION["link"];
$username = $_SESSION["username"];
$role_id = $_SESSION["role_id"];
$role_name = $_SESSION["role_name"];
$db = $_SESSION["db"];
    
include('../Connect_To_Database.php');
include'Actions_Processor.php';

$conn = mysqli_connect($host, $user, $pass, $db) 
    or die("Ошибка " . mysqli_error($conn));
    mysqli_set_charset($conn, 'utf8');
    
$query_ALL = "SELECT * FROM Processor;";
$res_ALL = mysqli_query($conn, $query_ALL) or die("Error" . mysqli_error($conn));

require_once "../Forms/Login_Role.php";
include('../Forms/Login_Config.php');
$thread_id = mysqli_thread_id($link);

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
{
    header("location: ../Forms/Login.php");
}

if($role_id == '0' || $role_id == '1')
{
    $_SESSION["crud_error"] = "Недостаточно прав для добавления данных!!!";
    echo '<h1>Недостаточно прав для добавления данных!<h1>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <title>Редактирование таблицы «Процессоры»</title>
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
        <h1 style="color: rgb(255, 255, 255);  text-align: center;">Редактирование таблицы «Процессоры»</h1>
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
          ORDER BY Processor_model';
    
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
              <th>Частота</th>
              <th>Boost</th>
              <th>Ядра</th>              
              <th>Сокет</th>
              <th>TDP</th>
              <th>Цена</th>
              <th>Действия</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr style="color:#fff;">
              <td style="background:#fff; text-align: center;"><?php echo "<embed src='data:".$row['Processor_imageType'].";base64,".$row['Processor_imageData']."' height='40' style='margin:-15px;' />"?></td>
              <td><?= $row['Processor_model']; ?></td>
              <td><?= $row['Proc_Brend_name']; ?></td>
              <td><?= $row['Proc_FreqBase_value']; ?></td>
              <td><?= $row['Proc_FreqBoost_value']; ?></td>
              <td><?= $row['Proc_Cores_value']; ?></td>
              <td><?= $row['Soсket_name']; ?></td>
              <td><?= $row['Proc_TDP_value']; ?></td>
              <td><?= $row['Processor_price']; ?></td>
              <td>
                <a href="CRUD_Processor.php?edit=<?= $row['Processor_id']; ?>" class="plan-button badge badge-success p-2">Обновить</a> 
                <a href="Actions_Processor.php?delete=<?= $row['Processor_id']; ?>" class="plan-button badge badge-danger p-2" onclick="return confirm('Вы уверены, что хотите удалить текущую запись?');">Удалить</a>
              </td>
            </tr>
            <?php }
            $query_brend = "SELECT * FROM Proc_Brend;"; $res_brend = mysqli_query($conn, $query_brend) or die("Error" . mysqli_error($conn));
            $query_freq_base = "SELECT * FROM Proc_FreqBase;"; $res_freq_base = mysqli_query($conn, $query_freq_base) or die("Error" . mysqli_error($conn));
            $query_freq_boost = "SELECT * FROM Proc_FreqBoost;"; $res_freq_boost = mysqli_query($conn, $query_freq_boost) or die("Error" . mysqli_error($conn));
            $query_cores = "SELECT * FROM Proc_Cores"; $res_cores = mysqli_query($conn, $query_cores) or die("Error" . mysqli_error($conn));
            $query_tdp = "SELECT * FROM Proc_TDP;"; $res_tdp = mysqli_query($conn, $query_tdp) or die("Error" . mysqli_error($conn));
            $query_socket = "SELECT * FROM Soсket"; $res_socket = mysqli_query($conn, $query_socket) or die("Error" . mysqli_error($conn));
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
        <form action="Actions_Processor.php" method="post" enctype="multipart/form-data">

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
                            <option disabled selected value> -- Выберите количество ядер / потоков -- </option>
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
            <select name="socket" class="form-control" value="<?= $socket; ?>" required>
                            <?php if ($update == false) { ?>
                            <option disabled selected value> -- Выберите сокет процессора -- </option>
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
            <select name="tdp" class="form-control" value="<?= $tdp; ?>" required>
                            <?php if ($update == false) { ?>
                            <option disabled selected value> -- Выберите TDP процессора -- </option>
                            <?php } ?>
                            <?php while($row1 = mysqli_fetch_array($res_tdp)):;?>
                            <option value="<?php echo $row1[0];?>" 
                                <?php if ($row1[0] == $tdp) { ?> 
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