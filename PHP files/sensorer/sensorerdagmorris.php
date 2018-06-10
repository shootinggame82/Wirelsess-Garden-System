<?php
include '../db.php';

$sensnr = $_GET["id"];

$temp = array();
$fukt = array();
$volt = array();
$datum = array();
$tid = array();
echo "[";
$sql = "SELECT jordtemp, fukt, volt, datum, tid FROM jordfukt WHERE DATE(datum) = DATE(NOW()) AND sensor = '$sensnr'";
$result = $mysqli->query($sql);
$numResults = mysqli_num_rows($result);
$counter = 0;
    while ($row = $result->fetch_assoc()) {
        $temp[] = $row["jordtemp"];
        $fukt[] = $row["fukt"];
        $volt[] = $row["volt"];
        $tid[] = $row["tid"];
        if (++$counter == $numResults) {
            echo '{"tid": "'.$row["tid"].'", "temp": '.$row["jordtemp"].', "fukt": '.$row["fukt"].'}';
        } else {
            echo '{"tid": "'.$row["tid"].'", "temp": '.$row["jordtemp"].', "fukt": '.$row["fukt"].'},';
        }
    }


echo "]";
