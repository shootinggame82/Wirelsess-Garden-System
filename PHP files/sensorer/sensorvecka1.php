<?php
include '../db.php';
$sensnr = $_GET["id"];
setlocale(LC_ALL, 'sv_SE', 'sv_SE.UTF-8');
date_default_timezone_set('Europe/Stockholm');
setlocale(LC_TIME, 'sv_SE', 'sv_SE.UTF-8');

//YEARWEEK(datum)=YEARWEEK(NOW())

$temp = array();
$fukt = array();
$volt = array();
$datum = array();
$tid = array();

$mandag = date("Y-m-d", strtotime('monday this week'));
$sondag = date('Y-m-d',strtotime("next sunday"));

$sql = "SELECT jordtemp, fukt, volt, datum, tid FROM jordfuktdag WHERE datum >= '$mandag' AND datum <= '$sondag' AND sensor = '$sensnr'";
$result = $mysqli->query($sql);


    while ($row = $result->fetch_assoc()) {
        $temp[] = $row["jordtemp"];
        $fukt[] = $row["fukt"];
        $volt[] = $row["volt"];
        $datumet = date($row["datum"]);
        $weekday =  strftime("%A", strtotime($datumet));
                          //$weekday = date('l', strtotime($row["datum"]));
                            $tid[] = $weekday;
    }






    $json = json_encode($temp, JSON_NUMERIC_CHECK);
    $string = str_replace(array('[', ']'), '', $json);
    $json2 = json_encode($fukt, JSON_NUMERIC_CHECK);
    $string2 = str_replace(array('[', ']'), '', $json2);
    $json3 = json_encode($volt, JSON_NUMERIC_CHECK);
    $string3 = str_replace(array('[', ']'), '', $json3);
    $json4 = json_encode($tid, JSON_NUMERIC_CHECK);
    $string4 = str_replace(array('[', ']'), '', $json4);




    echo '{ "temp": ' .$json. ', "fukt": ' .$json2.', "dagar": ' .$json4. '}';


 ?>
