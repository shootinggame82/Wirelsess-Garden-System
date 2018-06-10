<?php
include '../db.php';
$pumpid = $_POST["id"];
$okej = 0;
$sql = "SELECT * FROM users WHERE uid = '$pumpid'";
$result = $mysqli->query($sql);

if ($result->num_rows == 1) {
    while ($row = $result->fetch_assoc()) {
        $idnummer = $row["uid"];
        $okej = 1;
    }
}

if ($okej == 1) {
    if ($insert_stmt = $mysqli->prepare("DELETE FROM users WHERE uid = ?")) {
        $insert_stmt->bind_param('s', $idnummer);
        if (! $insert_stmt->execute()) {
            $echodata = array('error' => 'true', 'errorcode' => '1');
            echo json_encode($echodata);
            exit();
        }
        $echodata = array('error' => 'false', 'errorcode' => '0');
        echo json_encode($echodata);
    }
} else {
    $echodata = array('error' => 'true', 'errorcode' => '2');
    echo json_encode($echodata);
}
