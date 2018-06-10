<?php
include '../db.php';
$pumpid = $_POST["id"];
$sql = "SELECT * FROM vilt WHERE id = '$pumpid'";
$result = $mysqli->query($sql);

if ($result->num_rows == 1) {
    while ($row = $result->fetch_assoc()) {
        $startapumpen = $row["manuellt"];
    }
}


    if ($insert_stmt = $mysqli->prepare("UPDATE vilt SET manuellt = '1' WHERE id = ?")) {
        $insert_stmt->bind_param('s', $pumpid);
        if (! $insert_stmt->execute()) {
            exit();
        }
        $echodata = array('error' => 'false', 'errorcode' => '0', 'status' => $pumpid);
        echo json_encode($echodata);
    }
