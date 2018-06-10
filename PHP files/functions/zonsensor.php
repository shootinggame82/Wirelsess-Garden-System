<?php
include '../db.php';
$sensor = $_POST['sensor'];
$zonid = $_POST['zonid'];

$existerar = 1;


if (isset($zonid, $sensor)) {
    $sql = "SELECT * FROM zonsensor WHERE zon = '$zonid' AND sensor = '$sensor'";
    $result = $mysqli->query($sql);

    if ($result->num_rows == 1) {
        $existerar = 1;
        $echodata = array('error' => 'true', 'errorcode' => '0');
        echo json_encode($echodata);
    } else {
        $existerar = 0;
    }

    if ($existerar == 0) {
        if ($insert_stmt = $mysqli->prepare("INSERT INTO zonsensor (zon, sensor) VALUES (?, ?)")) {
            $insert_stmt->bind_param('ss', $zonid, $sensor);
            if (! $insert_stmt->execute()) {
                $echodata = array('error' => 'true', 'errorcode' => '1');
                echo json_encode($echodata);
                exit();
            }
            $echodata = array('error' => 'false', 'errorcode' => '0');
            echo json_encode($echodata);
        }
    }
} else {
    $echodata = array('error' => 'true', 'errorcode' => '2');
    echo json_encode($echodata);
}
