<?php
include '../db.php';
$besk = $_POST['besk'];
$namn = $_POST['namn'];

$existerar = 1;


if (isset($besk, $namn)) {
    $sql = "SELECT * FROM zoner WHERE namn = '$namn'";
    $result = $mysqli->query($sql);

    if ($result->num_rows == 1) {
        $existerar = 1;
        $echodata = array('error' => 'true', 'errorcode' => '0');
        echo json_encode($echodata);
    } else {
        $existerar = 0;
    }

    if ($existerar == 0) {
        if ($insert_stmt = $mysqli->prepare("INSERT INTO zoner (namn, besk) VALUES (?, ?)")) {
            $insert_stmt->bind_param('ss', $namn, $besk);
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
