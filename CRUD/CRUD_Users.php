<?php
session_start(); // начинаем новую сессию

// извлекаем переменные из сессии, если были установлены
$link = $_SESSION["link"];
$username = $_SESSION["username"];
$role_id = $_SESSION["role_id"];
$role_name = $_SESSION["role_name"];
$db = $_SESSION["db"];
    
include('../Connect_To_Database.php');
include('Actions_Users.php');

$conn = mysqli_connect($host, $user, $pass, $db) 
    or die("Ошибка " . mysqli_error($conn));
    mysqli_set_charset($conn, 'utf8');
    
$query_ALL = "SELECT * FROM users;";
$res_ALL = mysqli_query($conn, $query_ALL) or die("Error" . mysqli_error($conn));

require_once "../Forms/Login_Role.php";
include('../Forms/Login_Config.php');
$thread_id = mysqli_thread_id($link);

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
{
    header("location: ../Forms/Login.php");
}

if($role_id != '4')
{
    // запоминаем ошибку для отображения в файле CRUD_error.php
    $_SESSION["crud_error"] = "Недостаточно прав для добавления данных!!!";
    echo '<h1>Недостаточно прав для добавления данных!<h1>';
    exit;
}
  
?>

<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <title>Редактирование таблицы «Пользователи»</title>
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
        <h1 style="color: rgb(255, 255, 255);  text-align: center;">Редактирование таблицы «Пользователи»</h1>
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
			users_user_id,
            users_roles.user_roles_name,
            users_first_name,
            users_middle_name,
            users_last_name,
            users_email,
            users_username,
            users_password
       FROM users 
			INNER JOIN users_roles ON users_roles.user_roles_id = users.users_role_id
			ORDER BY users_role_id
          ';

          $stmt = $conn->prepare($query);
          $stmt->execute();
          $result = $stmt->get_result();
        ?>
        
        <table class="table table-hover" id="data-table">
          <thead>
            <tr style="color:#ddd;">
              <th>Роль</th>
              <th>Имя</th>
              <th>Отчество</th>
              <th>Фамилия</th>
              <th>Почта</th>              
              <th>Логин</th>
              <th>Пароль</th>
              <th>Действия</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr style="color:#fff;">
              <td><?= $row['user_roles_name']; ?></td>
              <td><?= $row['users_first_name']; ?></td>
              <td><?= $row['users_middle_name']; ?></td>
              <td><?= $row['users_last_name']; ?></td>
              <td><?= $row['users_email']; ?></td>
              <td><?= $row['users_username']; ?></td>
              <td><?= $row['users_password']; ?></td>
              <td>
                <a href="CRUD_Users.php?edit=<?= $row['users_user_id']; ?>" class="plan-button badge badge-success p-2">Обновить</a> 
                <a href="Actions_Users.php?delete=<?= $row['users_user_id']; ?>" class="plan-button badge badge-danger p-2" onclick="return confirm('Вы уверены, что хотите удалить текущую запись?');">Удалить</a>
              </td>
            </tr>
            <?php }
            $query_role = "SELECT * FROM users_roles;";
            $res_role = mysqli_query($conn, $query_role) or die("Error" . mysqli_error($conn));
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
        <form action="Actions_Users.php" method="post" enctype="multipart/form-data">
            
          <input type="hidden" name="id" value="<?= $id; ?>">
          
          <div class="form-group">
            <select name="role" class="form-control" value="<?= $role; ?>" required>
                            <?php if ($update == false) { ?>
                            <option disabled selected value> -- Выберите роль -- </option>
                            <?php } ?>
                            <?php while($row1 = mysqli_fetch_array($res_role)):;?>
                            <option value="<?php echo $row1[0];?>" 
                                <?php if ($row1[0] == $role) { ?> 
                                    selected
                                <?php } ?>>
                                <?php echo $row1[1];?></option>
                            <?php endwhile;?>
            </select>
            <span class="help-block"><?php echo $role_err;?></span>
          </div>
          
		  <div class="form-group">
            <input type="text" name="first" value="<?= $first; ?>" class="form-control" placeholder="Введите имя">
          </div>
		  
		  <div class="form-group">
            <input type="text" name="middle" value="<?= $middle; ?>" class="form-control" placeholder="Введите отчество">
          </div>
		  
		  <div class="form-group">
            <input type="text" name="last" value="<?= $last; ?>" class="form-control" placeholder="Введите фамилию">
          </div>
		  
		  <div class="form-group">
            <input type="text" name="email" value="<?= $email; ?>" class="form-control" placeholder="Введите электронную почту" required>
          </div>
		  
		  <div class="form-group">
            <input type="text" name="username" value="<?= $login; ?>" class="form-control" placeholder="Введите имя пользователя (логин)" required>
          </div>
		  
		  <div class="form-group">
            <input type="text" name="password" value="<?= $password; ?>" class="form-control" placeholder="Введите пароль" required>
          </div>
		  
		  <div class="form-group">
            <input  type="hidden" name="created_at" value="<?= $created_at; ?>" class="form-control" placeholder="">
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