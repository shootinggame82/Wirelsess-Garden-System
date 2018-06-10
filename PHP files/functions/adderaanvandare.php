<?php
include '../db.php';
$losen = $_POST['losen'];
$namn = $_POST['namn'];


$losen = md5($losen);


$existerar = 0;


if (isset($namn)) {
    $sql = "SELECT * FROM users WHERE username = '$namn'";
    $result = $mysqli->query($sql);

    if ($result->num_rows == 1) {
        $existerar = 1;
    } else {
        $existerar = 0;
    }

    if ($existerar == 1) {
        $echodata = array('error' => 'true', 'errorcode' => '0');
        echo json_encode($echodata);
        exit();
    } else {
        if ($insert_stmt = $mysqli->prepare("INSERT INTO users (username, password) VALUES (?, ?)")) {
            $insert_stmt->bind_param('ss', $namn, $losen);
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
