<?php
include '../db.php';
$pumpid = $_POST["id"];
$timestamp = date("Y-m-d H:i:s");
$sql = "SELECT * FROM pumpar WHERE id = '$pumpid'";
$result = $mysqli->query($sql);

if ($result->num_rows == 1) {
    // Räkna ihop antalet reserverade stolar
    while ($row = $result->fetch_assoc()) {
        $startapumpen = $row["aktiverad"];
        $pumpnr = $row["pumpnr"];
        $namn = $row["namn"];
    }
}

$sql = "SELECT * FROM uppgifter WHERE id = '1'";
$result = $mysqli->query($sql);

if ($result->num_rows == 1) {
    // Räkna ihop antalet reserverade stolar
    while ($row = $result->fetch_assoc()) {
        $pumptid= $row["pumptid"];
    }
}

if ($startapumpen == '1') {
    $startapumpen = '0';
    if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = ? WHERE id = ?")) {
        $insert_stmt->bind_param('ss', $startapumpen, $pumpid);
        // Execute the prepared query.
        if (! $insert_stmt->execute()) {
            exit();
        }
        $echodata = array('error' => 'false', 'errorcode' => '0', 'pumpnamn' => $namn, 'pumpnr' => $pumpnr, 'status' => $startapumpen);
        echo json_encode($echodata);
    }
} elseif ($startapumpen == '0') {
    $startapumpen = '1';
    if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = ?, startad = ? WHERE id = ?")) {
        $insert_stmt->bind_param('sss', $startapumpen, $timestamp, $pumpid);
        // Execute the prepared query.
        if (! $insert_stmt->execute()) {
            exit();
        }
        $echodata = array('error' => 'false', 'errorcode' => '0', 'pumpnamn' => $namn, 'pumpnr' => $pumpnr, 'status' => $startapumpen);
        echo json_encode($echodata);
    }
}
