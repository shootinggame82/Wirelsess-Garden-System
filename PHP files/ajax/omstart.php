<?php
include '../db.php';
$pumpid = $_POST["id"];

if ($insert_stmt = $mysqli->prepare("UPDATE uppgifter SET omstart = '1' WHERE id = ?")) {
    $insert_stmt->bind_param('s', $pumpid);
    if (! $insert_stmt->execute()) {
        exit();
    }
    $echodata = array('error' => 'false', 'errorcode' => '0');
    echo json_encode($echodata);
}
