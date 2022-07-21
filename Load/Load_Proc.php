<html lang="ru">

<head>
  <meta charset="UTF-8">
  <title>Процессоры
  </title>
  <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Open+Sans'>
  <link rel="stylesheet" href="Style/CssScroll.css">
  <script type="text/javascript" src="Script/JsRedirector.js"></script>
  <script type="text/javascript" src="Script/JsRedirect.js"></script>
</head>

<?php

include('../Connect_To_Database.php');

$conn = mysqli_connect($host, $user, $pass, $db) 
    or die("Ошибка " . mysqli_error($conn));
    mysqli_set_charset($conn, 'utf8');
$query="SELECT Processor_model                    AS 'Модель',
       Proc_Brend.Proc_Brend_name         AS 'Бренд',
       Proc_FreqBase.Proc_FreqBase_value  AS 'Базовая частота',
       Proc_FreqBoost.Proc_FreqBoost_value AS 'Частота в режиме Boost',
       Proc_Cores.Proc_Cores_value        AS 'Ядра',
       Soсket.Soсket_name                 AS 'Сокет',
       Proc_TDP.Proc_TDP_value            AS 'TDP',
       Processor_price                    AS 'Цена'
       FROM   Processor
INNER JOIN Proc_Brend     ON Proc_Brend.Proc_Brend_id         = Processor.Processor_brend
INNER JOIN Soсket         ON Soсket.Soсket_id                 = Processor.Processor_socket
INNER JOIN Proc_FreqBase  ON Proc_FreqBase.Proc_FreqBase_id   = Processor.Processor_freqBase
INNER JOIN Proc_FreqBoost ON Proc_FreqBoost.Proc_FreqBoost_id = Processor.Processor_freqBoost
INNER JOIN Proc_Cores     ON Proc_Cores.Proc_Cores_id         = Processor.Processor_cores
INNER JOIN Proc_TDP       ON Proc_TDP.Proc_TDP_id             = Processor.Processor_tdp
ORDER BY Processor_model;";

$res= mysqli_query($conn,$query);
$result = mysqli_query($conn, $query) or die("Error" . mysqli_error($conn));
if($result)
{
    $rows=mysqli_num_rows($result);

    echo "<table><tr>
    <th>  </th>
    <th> Модель </th>
    <th> Производитель </th>
    <th> Базовая частота </th>
    <th> Частота Boost </th>
    <th> Ядра </th>
    <th> Сокет </th>
    <th> TDP </th>
    <th> Цена </th>
    </tr>";

while($row=mysqli_fetch_row($result))
{   
    echo "<tr>";
    echo "<td> <img src='../Res/Processors/{$row[0]}.jpg' height=100> </td>";
 
    for($i=0;$i<count($row);$i++) {
        echo "<td> {$row[$i]} </td>";
    }
    echo "</tr>";
}

echo "</table>";
    
}

else
{
    echo "подключение к базе данных не выполнено!!";}

mysqli_close($conn);

?>
