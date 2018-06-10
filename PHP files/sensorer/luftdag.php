<?php
include '../db.php';

//$sensnr = $_GET["id"];

$temp = array();
$fukt = array();
$volt = array();
$datum = array();
$tid = array();
echo "[";
$sql = "SELECT temp, fukt, heat, datum, tid FROM luftfukt WHERE DATE(datum) = DATE(NOW())";
$result = $mysqli->query($sql);
$numResults = mysqli_num_rows($result);
$counter = 0;
    while ($row = $result->fetch_assoc()) {
        $temp[] = $row["temp"];
        $fukt[] = $row["fukt"];
        $volt[] = $row["heat"];
        $tid[] = $row["tid"];
        if (++$counter == $numResults) {
            echo '{"tid": "'.$row["tid"].'", "temp": '.$row["temp"].', "fukt": '.$row["fukt"].', "heat": '.$row["heat"].'}';
        } else {
            echo '{"tid": "'.$row["tid"].'", "temp": '.$row["temp"].', "fukt": '.$row["fukt"].', "heat": '.$row["heat"].'},';
        }
    }


echo "]";
