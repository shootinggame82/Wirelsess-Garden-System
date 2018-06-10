<?php
include '../db.php';
$zonid = $_POST['idet'];
$dokument = $_POST['dokument'];
$rubrik = $_POST['rubrik'];


$idag = date("Y-m-d");
$nu = date("H:i:s");
$datum = date("Y-m-d", strtotime($idag));
$tid = date("H:i:s", strtotime($nu));

$existerar = 0;


if (isset($zonid, $rubrik)) {
    if ($existerar == 0) {
        if ($insert_stmt = $mysqli->prepare("INSERT INTO loggbok (rubrik, dokument, datum, tid, zon) VALUES (?, ?, ?, ?, ?)")) {
            $insert_stmt->bind_param('sssss', $rubrik, $dokument, $datum, $tid, $zonid);
            if (! $insert_stmt->execute()) {
                $echodata = array('error' => 'true', 'errorcode' => '1');
                echo json_encode($echodata);
                exit();
            }
            header("location: ../zonloggar.php?id=".$zonid);
        }
    }
} else {
    $echodata = array('error' => 'true', 'errorcode' => '2');
    echo json_encode($echodata);
}
