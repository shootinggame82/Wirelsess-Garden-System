<?php
//header('Content-Type: application/json');
include '../db.php';

$sql = "SELECT pumpnr, namn, aktiverad FROM pumpar WHERE aktiverad = '1'";
$result = $mysqli->query($sql);
if ($result->num_rows > 0) {
    $echodata = array('pump' => 'true');
    echo json_encode($echodata);
}
