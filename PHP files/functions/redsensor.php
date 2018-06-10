<?php
include '../db.php';
$idnr = $_POST['idnr'];
$namn = $_POST['namn'];
$pump = $_POST['pump'];
$fukt = $_POST['fukt'];
$auto = $_POST['auto'];

if (isset($auto)) {
    $auto = 1;
} else {
    $auto = 0;
}

$existerar = 0;


if (isset($idnr, $namn)) {
    $sql = "SELECT * FROM sensorer WHERE id = '$idnr'";
    $result = $mysqli->query($sql);

    if ($result->num_rows == 1) {
        $existerar = 1;
    } else {
        $existerar = 0;

        $echodata = array('error' => 'true', 'errorcode' => '0');
        echo json_encode($echodata);
    }

    if ($existerar == 1) {
        if ($insert_stmt = $mysqli->prepare("UPDATE sensorer SET namn = ?, pump = ?, fuktighet = ?, autostart = ? WHERE id = ?")) {
            $insert_stmt->bind_param('sssss', $namn, $pump, $fukt, $auto, $idnr);
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
