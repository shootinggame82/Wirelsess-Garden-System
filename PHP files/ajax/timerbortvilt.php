<?php
include '../db.php';
$pumpid = $_POST["id"];
$sql = "SELECT * FROM pumptimer WHERE id = '$pumpid'";
$result = $mysqli->query($sql);

if ($result->num_rows == 1) {
    // Räkna ihop antalet reserverade stolar
    while ($row = $result->fetch_assoc()) {
        $idnummer = $row["id"];
        $okej = 1;
    }
}

if ($okej == 1) {
    if ($insert_stmt = $mysqli->prepare("DELETE FROM pumptimer WHERE id = ?")) {
        $insert_stmt->bind_param('s', $idnummer);
        // Execute the prepared query.
        if (! $insert_stmt->execute()) {
            exit();
        }
        $echodata = array('error' => 'false', 'errorcode' => '0');
        echo json_encode($echodata);
    }
}
