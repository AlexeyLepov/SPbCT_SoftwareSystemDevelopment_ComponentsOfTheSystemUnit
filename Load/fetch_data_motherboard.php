<?php

include('../Connect_To_Database.php');
$conn = mysqli_connect($host, $user, $pass, $db) 
    or die("Ошибка " . mysqli_error($conn));
    mysqli_set_charset($conn, 'utf8');

if(isset($_POST["action"]))
{
 $query = "
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
 ";
 if(isset($_POST["minimum_price"], $_POST["maximum_price"]) && !empty($_POST["minimum_price"]) && !empty($_POST["maximum_price"]))
 {
  $query .= "AND Motherboard_price BETWEEN '".$_POST["minimum_price"]."' AND '".$_POST["maximum_price"]."' ";
 }
 if(isset($_POST["brand"]))
 {
  $brand_filter = implode("','", $_POST["brand"]);
  $query .= "AND Brend.Brend_name IN('".$brand_filter."') ";
 }
 if(isset($_POST["socket"]))
 {
  $socket_filter = implode("','", $_POST["socket"]);
  $query .= "AND Soсket.Soсket_name IN('".$socket_filter."') ";
 }
 if(isset($_POST["chipset"]))
 {
  $chipset_filter = implode("','", $_POST["chipset"]);
  $query .= "AND Chipset.Chipset_name IN('".$chipset_filter."') ORDER BY Motherboard_model;";
 }

$result = mysqli_query($conn, $query) or die("Error" . mysqli_error($conn));
if($result)
{
	$rows=mysqli_num_rows($result);
	while($row=mysqli_fetch_row($result))
	{  
   $output .= '
	<div class="col-md-3 mb-2">
		<div class="card-deck">
			<div class="card border-secondary ">
					<h2 class="text-center rounded" style=" background: #eeaa5d; height:50px; vertical-align:middle;">'.$row[1].'</h2>
				<embed class="card-img-top" style="max-height:150px;" src="data:'.$row[10].';base64,'.$row[11].'" />
				<div class="card-img-overlay">
				</div>
				<div class="card-body">
					<h2 class="card-title" style="color: #e98247;">Цена '.$row[8].' ₽</h4>
					<h5 style="color: black;">
						Бренд - '.$row[2].'<br />
						SATA  - '.$row[3].'<br />
						Минимальная частота ОЗУ - '.$row[4].'<br />
						Максимальная частота ОЗУ - '.$row[5].'<br />
						Сокет  - '.$row[6].'<br />
						Чипсет - '.$row[7].'<br />
					</h5>
				</div>
			</div>
		</div>
	</div>
   ';
  }
 }
 else
 {
  $output = '<div class="col-md-3 mb-2"><h3>Ничего не найдено...</h3></div>';
 }
 echo $output;
}

?>