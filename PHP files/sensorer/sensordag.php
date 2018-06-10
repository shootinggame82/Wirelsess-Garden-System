<?php
include '../db.php';

$sensnr = $_GET["id"];

$temp = array();
$fukt = array();
$volt = array();
$datum = array();
$tid = array();

$sql = "SELECT jordtemp, fukt, volt, datum, tid FROM jordfukt WHERE DATE(datum) = DATE(NOW()) AND sensor = '$sensnr'";
$result = $mysqli->query($sql);


    while ($row = $result->fetch_assoc()) {
        $temp[] = $row["jordtemp"];
        $fukt[] = $row["fukt"];
        $volt[] = $row["volt"];
        $tid[] = $row["tid"];
    }




    $json = json_encode($temp, JSON_NUMERIC_CHECK);
    $string = str_replace(array('[', ']'), '', $json);
    $json2 = json_encode($fukt, JSON_NUMERIC_CHECK);
    $string2 = str_replace(array('[', ']'), '', $json2);
    $json3 = json_encode($volt, JSON_NUMERIC_CHECK);
    $string3 = str_replace(array('[', ']'), '', $json3);
    $json4 = json_encode($tid, JSON_NUMERIC_CHECK);
    $string4 = str_replace(array('[', ']'), '', $json4);




    echo '{ "temp": ' .$json. ', "fukt": ' .$json2.', "tid": ' .$json4.'}';

 ?>
