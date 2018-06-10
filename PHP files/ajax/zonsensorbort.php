<?php
include '../db.php';
$pumpid = $_POST["id"];
$zonid = $_POST["zon"];
$sql = "SELECT * FROM zonsensor WHERE id = '$pumpid'";
$result = $mysqli->query($sql);

if ($result->num_rows == 1) {
    while ($row = $result->fetch_assoc()) {
        $idnummer = $row["id"];
        $okej = 1;
    }
}

if ($okej == 1) {
    if ($insert_stmt = $mysqli->prepare("DELETE FROM zonsensor WHERE id = ?")) {
        $insert_stmt->bind_param('s', $idnummer);
        if (! $insert_stmt->execute()) {
            exit();
        }
        $echodata = array('error' => 'false', 'errorcode' => '0');
        echo json_encode($echodata);
    }
}
