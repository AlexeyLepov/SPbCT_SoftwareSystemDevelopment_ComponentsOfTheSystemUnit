<?php

include('../Connect_To_Database.php');
$conn = mysqli_connect($host, $user, $pass, $db) 
    or die("Ошибка " . mysqli_error($conn));
    mysqli_set_charset($conn, 'utf8');

if(isset($_POST["action"]))
{
				$query = "
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
					Videocard_imageName, 
					Videocard_imageType, Videocard_imageData     
				FROM Videocard AS V
					INNER JOIN Brend AS B      ON B.Brend_id = V.Videocard_brend
					INNER JOIN Video_FreqBase  ON Video_FreqBase.Video_FreqBase_id = V.Videocard_freqBase
					INNER JOIN Video_FreqBoost ON Video_FreqBoost.Video_FreqBoost_id = V.Videocard_freqBoost
					INNER JOIN Video_Cores     ON Video_Cores.Video_Cores_id = V.Videocard_cores
					INNER JOIN Video_RamType   ON Video_RamType.Video_RamType_id = V.Videocard_ramType
					INNER JOIN Video_RamSize   ON Video_RamSize.Video_RamSize_id = V.Videocard_ramSize
					INNER JOIN Video_TDP       ON Video_TDP.Video_TDP_id = V.Videocard_tdp
				";
 if(isset($_POST["minimum_price"], $_POST["maximum_price"]) && !empty($_POST["minimum_price"]) && !empty($_POST["maximum_price"]))
 {
  $query .= "AND Videocard_price BETWEEN '".$_POST["minimum_price"]."' AND '".$_POST["maximum_price"]."' ";
 }
 if(isset($_POST["brand"]))
 {
  $brand_filter = implode("','", $_POST["brand"]);
  $query .= "AND B.Brend_name IN('".$brand_filter."') ";
 }
 if(isset($_POST["ram_type"]))
 {
  $ram_type_filter = implode("','", $_POST["ram_type"]);
  $query .= "AND Video_RamType.Video_RamType_value IN('".$ram_type_filter."') ";
 }
 if(isset($_POST["ram_size"]))
 {
  $ram_size_filter = implode("','", $_POST["ram_size"]);
  $query .= "AND Video_RamSize.Video_RamSize_value IN('".$ram_size_filter."') ";
 }
 if(isset($_POST["tgp"]))
 {
  $tgp_filter = implode("','", $_POST["tgp"]);
  $query .= "AND Video_TDP.Video_TDP_value IN('".$tgp_filter."') ORDER BY Videocard_model;";
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
				<h2 class="text-center rounded" style="font-size:16px; background: #eeaa5d; height:50px; vertical-align:middle;">'.$row[1].'</h2>
				<embed class="card-img-top" style="max-height:120px;" src="data:'.$row[11].';base64,'.$row[12].'" />
				<div class="card-img-overlay">
				</div>
				<div class="card-body">
					<h2 class="card-title" style="color: #e98247;">Цена '.$row[9].' ₽</h4>
					<h5 style="color: black;">
						Производитель   - '.$row[2].'<br />
						Базовая частота - '.$row[3].'<br />
						Boost частота   - '.$row[4].'<br />
						Ядра            - '.$row[5].'<br />
						Тип памяти      - '.$row[6].'<br />
						Объем памяти    - '.$row[7].'<br />
						TGP	            - '.$row[8].'<br />
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