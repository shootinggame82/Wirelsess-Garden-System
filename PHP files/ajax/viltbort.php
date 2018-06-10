<?php
include '../db.php';
$pumpid = $_POST["id"];
$sql = "SELECT * FROM vilt WHERE id = '$pumpid'";
$result = $mysqli->query($sql);

if ($result->num_rows == 1) {
    // Räkna ihop antalet reserverade stolar
    while ($row = $result->fetch_assoc()) {
        $idnummer = $row["id"];
        $sensornr = $row["viltnr"];
        $okej = 1;
    }
}

if ($okej == 1) {
    if ($insert_stmt = $mysqli->prepare("DELETE FROM zonvilt WHERE vilt = ?")) {
        $insert_stmt->bind_param('s', $idnummer);
        // Execute the prepared query.
        if (! $insert_stmt->execute()) {
            exit();
        }

        if ($insert_stmt = $mysqli->prepare("DELETE FROM viltstatus WHERE viltnr = ?")) {
            $insert_stmt->bind_param('s', $sensornr);
            // Execute the prepared query.
            if (! $insert_stmt->execute()) {
                exit();
            }
            if ($insert_stmt = $mysqli->prepare("DELETE FROM vilt WHERE id = ?")) {
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
