<?php
include '../db.php';
$pumpid = $_POST["id"];
$sql = "SELECT * FROM zoner WHERE id = '$pumpid'";
$result = $mysqli->query($sql);

if ($result->num_rows == 1) {
    while ($row = $result->fetch_assoc()) {
        $idnummer = $row["id"];
        $okej = 1;
    }
}

if ($okej == 1) {
    if ($insert_stmt = $mysqli->prepare("DELETE FROM zonvilt WHERE zon = ?")) {
        $insert_stmt->bind_param('s', $idnummer);
        if (! $insert_stmt->execute()) {
            exit();
        }

        if ($insert_stmt = $mysqli->prepare("DELETE FROM zonsensor WHERE zon = ?")) {
            $insert_stmt->bind_param('s', $idnummer);
            if (! $insert_stmt->execute()) {
                exit();
            }
            if ($insert_stmt = $mysqli->prepare("DELETE FROM zonpump WHERE zon = ?")) {
                $insert_stmt->bind_param('s', $idnummer);
                if (! $insert_stmt->execute()) {
                    exit();
                }
                if ($insert_stmt = $mysqli->prepare("DELETE FROM zoner WHERE id = ?")) {
                    $insert_stmt->bind_param('s', $idnummer);
                    if (! $insert_stmt->execute()) {
                        exit();
                    }
                    $echodata = array('error' => 'false', 'errorcode' => '0');
                    echo json_encode($echodata);
                }
            }
        }
    }
}
