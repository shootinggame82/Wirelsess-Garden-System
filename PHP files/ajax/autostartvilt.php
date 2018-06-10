<?php
include '../db.php';

header('Content-Type: application/json');

$temp = array();
$fukt = array();
$volt = array();
$datum = array();
$tid = array();
echo "[";
$sql = "SELECT * FROM vilt";
$result = $mysqli->query($sql);
$numResults = mysqli_num_rows($result);
$counter = 0;
    while ($row = $result->fetch_assoc()) {
        if (++$counter == $numResults) {
            echo '{"id": '.$row["id"].', "viltnr": '.$row["viltnr"].', "namn": "'.$row["namn"].'", "aktiverad": '.$row["aktiverad"].'}';
        } else {
            echo '{"id": '.$row["id"].', "viltnr": '.$row["viltnr"].', "namn": "'.$row["namn"].'", "aktiverad": '.$row["aktiverad"].'},';
        }
    }


echo "]";
