<?php
include '../db.php';
$idnr = $_POST['idnr'];
$namn = $_POST['namn'];
$vilt = $_POST['pump'];


$existerar = 0;


if (isset($idnr, $namn)) {
    $sql = "SELECT * FROM vilt WHERE id = '$idnr'";
    $result = $mysqli->query($sql);

    if ($result->num_rows == 1) {
        $existerar = 1;
    } else {
        $existerar = 0;

        $echodata = array('error' => 'true', 'errorcode' => '0');
        echo json_encode($echodata);
    }

    if ($existerar == 1) {
        if ($insert_stmt = $mysqli->prepare("UPDATE vilt SET namn = ?, viltsiren = ? WHERE id = ?")) {
            $insert_stmt->bind_param('sss', $namn, $vilt, $idnr);
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
