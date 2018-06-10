<?php
include '../db.php';
$pumpid = $_POST["id"];
$sql = "SELECT * FROM vilt WHERE id = '$pumpid'";
$result = $mysqli->query($sql);

if ($result->num_rows == 1) {
    // Räkna ihop antalet reserverade stolar
    while ($row = $result->fetch_assoc()) {
        $startapumpen = $row["aktiverad"];
        $viltnr = $row["viltnr"];
        $namn = $row["namn"];
    }
}

if ($startapumpen == "1") {
    $startapumpen = '0';
    if ($insert_stmt = $mysqli->prepare("UPDATE vilt SET aktiverad = ? WHERE id = ?")) {
        $insert_stmt->bind_param('ss', $startapumpen, $pumpid);
        // Execute the prepared query.
        if (! $insert_stmt->execute()) {
            exit();
        }
        $echodata = array('error' => 'false', 'errorcode' => '0', 'viltnamn' => $namn, 'viltnr' => $viltnr, 'status' => $startapumpen);
        echo json_encode($echodata);
    }
} else {
    $startapumpen = '1';
    if ($insert_stmt = $mysqli->prepare("UPDATE vilt SET aktiverad = ? WHERE id = ?")) {
        $insert_stmt->bind_param('ss', $startapumpen, $pumpid);
        // Execute the prepared query.
        if (! $insert_stmt->execute()) {
            exit();
        }
        $echodata = array('error' => 'false', 'errorcode' => '0', 'viltnamn' => $namn, 'viltnr' => $viltnr, 'status' => $startapumpen);
        echo json_encode($echodata);
    }
}
