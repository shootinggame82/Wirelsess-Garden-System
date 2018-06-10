<?php
include '../db.php';

header('Content-Type: application/json');

$temp = array();
$fukt = array();
$volt = array();
$datum = array();
$tid = array();
echo "[";
$sql = "SELECT * FROM sensorer";
$result = $mysqli->query($sql);
$numResults = mysqli_num_rows($result);
$counter = 0;
    while ($row = $result->fetch_assoc()) {
        if (++$counter == $numResults) {
            echo '{"id": '.$row["id"].', "sensornr": '.$row["sensornr"].', "namn": "'.$row["namn"].'", "avlast": '.$row["avlast"].'}';
            if ($insert_stmt = $mysqli->prepare("UPDATE sensorer SET avlast = '0' WHERE id = ?")) {
                $insert_stmt->bind_param('s', $row["id"]);
                if (! $insert_stmt->execute()) {
                    exit();
                }
            }
        } else {
            echo '{"id": '.$row["id"].', "sensornr": '.$row["sensornr"].', "namn": "'.$row["namn"].'", "avlast": '.$row["avlast"].'},';
            if ($insert_stmt = $mysqli->prepare("UPDATE sensorer SET avlast = '0' WHERE id = ?")) {
                $insert_stmt->bind_param('s', $row["id"]);
                if (! $insert_stmt->execute()) {
                    exit();
                }
            }
        }
    }


echo "]";
