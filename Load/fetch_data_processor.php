<?php

include('../Connect_To_Database.php');
$conn = mysqli_connect($host, $user, $pass, $db) 
    or die("Ошибка " . mysqli_error($conn));
    mysqli_set_charset($conn, 'utf8');

if(isset($_POST["action"]))
{
 $query = "
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
  FROM Processor
	INNER JOIN Proc_Brend     ON Proc_Brend.Proc_Brend_id         = Processor.Processor_brend
	INNER JOIN Proc_FreqBase  ON Proc_FreqBase.Proc_FreqBase_id   = Processor.Processor_freqBase
	INNER JOIN Proc_FreqBoost ON Proc_FreqBoost.Proc_FreqBoost_id = Processor.Processor_freqBoost
	INNER JOIN Proc_Cores     ON Proc_Cores.Proc_Cores_id         = Processor.Processor_cores
	INNER JOIN Soсket         ON Soсket.Soсket_id                 = Processor.Processor_socket
	INNER JOIN Proc_TDP       ON Proc_TDP.Proc_TDP_id             = Processor.Processor_tdp 
 ";
 if(isset($_POST["minimum_price"], $_POST["maximum_price"]) && !empty($_POST["minimum_price"]) && !empty($_POST["maximum_price"]))
 {
  $query .= "AND Processor_price BETWEEN '".$_POST["minimum_price"]."' AND '".$_POST["maximum_price"]."' ";
 }
 if(isset($_POST["brand"]))
 {
  $brand_filter = implode("','", $_POST["brand"]);
  $query .= "AND Proc_Brend.Proc_Brend_name IN('".$brand_filter."') ";
 }
 if(isset($_POST["cores"]))
 {
  $cores_filter = implode("','", $_POST["cores"]);
  $query .= "AND Proc_Cores.Proc_Cores_value IN('".$cores_filter."') ";
 }
 if(isset($_POST["tdp"]))
 {
  $tdp_filter = implode("','", $_POST["tdp"]);
  $query .= "AND Proc_TDP.Proc_TDP_value IN('".$tdp_filter."') ";
 }
 if(isset($_POST["socket"]))
 {
  $socket_filter = implode("','", $_POST["socket"]);
  $query .= "AND Soсket.Soсket_name IN('".$socket_filter."') ORDER BY Processor_model;";
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
				<embed class="card-img-top" style="max-height:180px;" src="data:'.$row[10].';base64,'.$row[11].'" />
				<div class="card-img-overlay">
				</div>
				<div class="card-body">
					<h2 class="card-title" style="color: #e98247;">Цена '.$row[8].' ₽</h4>
					<h5 style="color: black;">
						Производитель   - '.$row[2].'<br />
						Базовая частота - '.$row[3].'<br />
						Boost частота   - '.$row[4].'<br />
						Ядра            - '.$row[5].'<br />
						Сокет           - '.$row[6].'<br />
						TDP             - '.$row[7].'<br />
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