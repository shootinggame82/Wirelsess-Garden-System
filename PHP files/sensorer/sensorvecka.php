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
$sondag = date('Y-m-d', strtotime("next sunday"));

echo "[";
$sql = "SELECT jordtemp, fukt, volt, datum, tid FROM jordfuktdag WHERE datum >= '$mandag' AND datum <= '$sondag' AND sensor = '$sensnr'";
$result = $mysqli->query($sql);
$numResults = mysqli_num_rows($result);
$counter = 0;
    while ($row = $result->fetch_assoc()) {
        $temp[] = $row["jordtemp"];
        $fukt[] = $row["fukt"];
        $volt[] = $row["volt"];
        $tid[] =
        $row["tid"];
        $datumet = date($row["datum"]);
        $weekday =  strftime("%A", strtotime($datumet));
        //$weekday = date('l', strtotime($row["datum"]));
        $tid[] = $weekday;
        if (++$counter == $numResults) {
            echo '{"dag": "'.$weekday.'", "temp": '.$row["jordtemp"].', "fukt": '.$row["fukt"].'}';
        } else {
            echo '{"dag": "'.$weekday.'", "temp": '.$row["jordtemp"].', "fukt": '.$row["fukt"].'},';
        }
    }


echo "]";
