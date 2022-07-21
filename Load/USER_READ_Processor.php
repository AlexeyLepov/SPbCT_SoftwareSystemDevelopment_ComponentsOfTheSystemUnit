<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Процессоры
  </title>
    <script src="../js/jquery-1.10.2.min.js"></script>
    <script src="../js/jquery-ui.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <link rel="shortcut icon" href="https://icons.iconarchive.com/icons/fatcow/farm-fresh/32/cooler-icon.png" type="image/x-icon" />
    <link rel="stylesheet" href="../css/CssTableUl.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/owl.carousel.min.css">
    <link rel="stylesheet" href="../css/style-filter.css">
    <link rel="stylesheet" href="../css/jquery-ui.css">
    <link rel="stylesheet" href="../css/bootstrap.min.filter.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body style="background: #042630;">

<!--ПОДКЛЮЧЕНИЕ К БАЗЕ ДАННЫХ-->
<?php
include('../Connect_To_Database.php');
$conn = mysqli_connect($host, $user, $pass, $db) 
    or die("Ошибка " . mysqli_error($conn));
    mysqli_set_charset($conn, 'utf8');
?>

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

<div style=" margin-bottom: -13px;background: rgb(0,86,110); background: linear-gradient(90deg, rgb(240, 195, 132) 0%, rgb(3, 162, 211) 40%, rgb(0, 154, 201) 60%,  rgb(245, 189, 112) 100%);"><div class="container"><br><br><br></div></div>

<div style="background: rgb(0,86,110); background: linear-gradient(90deg, rgb(214, 152, 64) 0%, rgb(0, 106, 138) 40%, rgb(0, 106, 138) 60%,  rgb(214, 152, 64) 100%);"><div class="container-fluid" style="color:#fff;">

<!--ФИЛЬТР-->
<div class="container"><br /><br />
            <h1 style="color: rgb(255, 255, 255);  text-align: center;">Процессоры</h1><hr>

    <div class="row">
	
		<div class="col-lg-9">
			<div class="text-center"><img src="../Res/loader.gif" id="loader" width="100" style="display:none;"></div>
				
            <div class="row" id="result">
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
				  ORDER BY Processor_model;';
				$result = mysqli_query($conn, $query) or die("Error" . mysqli_error($conn));
				if($result)
				{
					$rows=mysqli_num_rows($result);
					while($row=mysqli_fetch_row($result))
					{
				?>

				<div class="col-md-3 mb-2">
					<div class="card-deck">
						<div class="card border-secondary">
						
							<h2 class="text-center rounded" style=" background: #eeaa5d; height:50px; vertical-align:middle;"><?= $row[1];?></h2>
							<?php echo "<embed class='card-img-top' style='max-height:180px;' src='data:".$row[10].";base64,".$row[11]."' />"?>
							<div class="card-img-overlay">
							</div>
							<div class="card-body">
								<h2 class="card-title" style="color: #e98247;">Цена <?=$row[8];?> ₽</h2>
								<h5 style="color: black;">
								 Производитель   - <?= $row[2];?><br />
								 Базовая частота - <?= $row[3];?><br />
								 Boost частота   - <?= $row[4];?><br />
								 Ядра            - <?= $row[5];?><br />
								 Сокет           - <?= $row[6];?><br />
								 TDP             - <?= $row[7];?><br />
								</h5>
							</div>
						</div>
					</div>
				</div>

				<?php					
					}
				}
				?>
			</div>
        </div>

		<?php		
			$sql = "SELECT  MIN(Processor_price) FROM Processor";
			$result = $conn->query($sql);
			while($row = mysqli_fetch_array($result))
			{
				$lowestprice = $row['MIN(Processor_price)'];
			}
			$sql = "SELECT  MAX(Processor_price) FROM Processor";
			$result = $conn->query($sql);
			while($row = mysqli_fetch_array($result))
			{
				$highestprice = $row['MAX(Processor_price)'];
			}
		?>

        <div class="col-md-3">
		
            <h1 style="color: rgb(255, 255, 255);  text-align: center;">Фильтры</h1><hr>
			<div class="list-group">
				<h3>Цена</h3>
				<input type="hidden" id="hidden_minimum_price" value="<?php echo $lowestprice; ?>" />
                <input type="hidden" id="hidden_maximum_price" value="<?php echo $highestprice; ?>" />	
				<?php echo '<h2 id="price_show">'.$lowestprice.' - '.$highestprice.' ₽</h2>'; ?>
				<div id="price_range"></div>
            </div>	
			
            <div class="list-group">
				<h3>Производитель</h3>
				<?php          
					$query = '
					  SELECT DISTINCT
						Proc_Brend_id,
						Proc_Brend_name           
					  FROM Proc_Brend
					  ORDER BY Proc_Brend_id;';
					$res= mysqli_query($conn,$query);
					$result = mysqli_query($conn, $query) or die("Error" . mysqli_error($conn));
                    foreach($result as $row)
                    {
                ?>
                <div class="list-group-item checkbox">
                    <label style="color: #042630;">
					<input type="checkbox" id="brand" class="common_selector brand" value="<?php echo $row['Proc_Brend_name']; ?>"  > <?php echo $row['Proc_Brend_name']; ?>
					</label>
                </div>
                <?php
                    }
				?>	
            </div>
			
			<div class="list-group">
				<h3>Ядра/Потоки</h3>
                <?php          
					$query = '
					  SELECT DISTINCT
						Proc_Cores_id,
						Proc_Cores_value           
					  FROM   Proc_Cores
					  ORDER BY Proc_Cores_id;';
					$res= mysqli_query($conn,$query);
					$result = mysqli_query($conn, $query) or die("Error" . mysqli_error($conn));
                    foreach($result as $row)
                    {
                ?>
                <div class="list-group-item checkbox">
                    <label style="color: #042630;">
					<input type="checkbox" id="cores" class="common_selector cores" value="<?php echo $row['Proc_Cores_value']; ?>" > <?php echo $row['Proc_Cores_value']; ?>
					</label>
                </div>
                <?php    
                    }
				?>
            </div>
			
			<div class="list-group">
				<h3>TDP</h3>
				<?php          
					$query = '
					  SELECT DISTINCT
						Proc_TDP_id,
						Proc_TDP_value           
					  FROM   Proc_TDP
					  ORDER BY Proc_TDP_id;';
					$res= mysqli_query($conn,$query);
					$result = mysqli_query($conn, $query) or die("Error" . mysqli_error($conn));
                    foreach($result as $row)
                    {
                ?>
                <div class="list-group-item checkbox">
                    <label style="color: #042630;">
					<input type="checkbox" id="tdp" class="common_selector tdp" value="<?php echo $row['Proc_TDP_value']; ?>" > <?php echo $row['Proc_TDP_value']; ?>
					</label>
                </div>
                <?php
                    }
                ?>	
            </div>
			
			<div class="list-group">
				<h3>Сокет</h3>
				<?php          
					$query = '
					  SELECT DISTINCT
						Soсket_id,
						Soсket_name           
					  FROM   Soсket
					  ORDER BY Soсket_id;';
					$res= mysqli_query($conn,$query);
					$result = mysqli_query($conn, $query) or die("Error" . mysqli_error($conn));
                    foreach($result as $row)
                    {
                ?>
                <div class="list-group-item checkbox">
                    <label style="color: #042630;">
					<input type="checkbox" id="socket" class="common_selector socket" value="<?php echo $row['Soсket_name']; ?>"  > <?php echo $row['Soсket_name']; ?>
					</label>
                </div>
                <?php
                    }
                ?>	
            </div>
			
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	var min_price = '<?php echo $lowestprice;?>';
	var max_price = '<?php echo $highestprice;?>';
	var minprice = parseInt(min_price.match(/\d+/))
	var maxprice = parseInt(max_price.match(/\d+/))
    
	function filter_data()
    {
		$("#loader").show();
        var action = 'data';
        var minimum_price = $('#hidden_minimum_price').val();
        var maximum_price = $('#hidden_maximum_price').val();
        var brand = get_filter('brand');
        var cores = get_filter('cores');
        var tdp = get_filter('tdp');
        var socket = get_filter('socket');
		$.ajax({
            url:"fetch_data_processor.php",
            method:"POST",
            data:{action:action, minimum_price:minimum_price, maximum_price:maximum_price, brand:brand, cores:cores, tdp:tdp, socket:socket},
            success:function(response){
				$("#result").html(response);
				$("#loader").hide();
            }
        });
    }
	
    function get_filter(text_id)
    {
        var filterData = [];
        $('.'+text_id+':checked').each(function(){
            filterData.push($(this).val());
        });
        return filterData;
    }
	
    $('.common_selector').click(function(){
        filter_data();
    });
	
    $('#price_range').slider({
        range:true,
        min:minprice-1,
        max:maxprice+1,
        values:[minprice-1, maxprice+1],
        step:1,
        stop:function(event, ui)
        {
            $('#price_show').html(ui.values[0] + '.00 - ' + ui.values[1] + '.00 ₽');
            $('#hidden_minimum_price').val(ui.values[0]);
            $('#hidden_maximum_price').val(ui.values[1]);
            filter_data();
        }
    });
});
</script>

  </div>
  <br>
</div>
  
<div style="background: rgb(0,86,110); background: linear-gradient(90deg, rgb(240, 195, 132) 0%, rgb(3, 162, 211) 40%, rgb(0, 154, 201) 60%,  rgb(245, 189, 112) 100%);"><div class="container"><br></div></div>

<!--ПОДВАЛ-->
<?php
	if($role_id == 1) include($h_path.'footer_User.php'); 
	else if ($role_id >= 2) include($h_path.'footer_Admin.php');
	else include($h_path.'footer.php');
	mysqli_close($conn);
?>

</body>
</html>