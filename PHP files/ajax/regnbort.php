<?php
include '../db.php';
$pumpid = $_POST["id"];
$sql = "SELECT * FROM regnsensor WHERE id = '$pumpid'";
$result = $mysqli->query($sql);

if ($result->num_rows == 1) {
    // Räkna ihop antalet reserverade stolar
    while ($row = $result->fetch_assoc()) {
        $idnummer = $row["id"];
        $sensornr = $row["regnid"];
        $okej = 1;
    }
}

if ($okej == 1) {
    if ($insert_stmt = $mysqli->prepare("DELETE FROM zonregn WHERE regn = ?")) {
        $insert_stmt->bind_param('s', $idnummer);
        // Execute the prepared query.
        if (! $insert_stmt->execute()) {
            exit();
        }

        if ($insert_stmt = $mysqli->prepare("DELETE FROM regndata WHERE regnnr = ?")) {
            $insert_stmt->bind_param('s', $sensornr);
            // Execute the prepared query.
            if (! $insert_stmt->execute()) {
                exit();
            }
            if ($insert_stmt = $mysqli->prepare("DELETE FROM regnsensor WHERE id = ?")) {
                $insert_stmt->bind_param('s', $idnummer);
                // Execute the prepared query.
                if (! $insert_stmt->execute()) {
                    exit();
                }
                $echodata = array('error' => 'false', 'errorcode' => '0');
                echo json_encode($echodata);
            }
        }
    }
}
