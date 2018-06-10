<?php
//header('Content-Type: application/json');
include '../db.php';

$sql = "SELECT viltnr, namn, aktiverad volt FROM vilt WHERE aktiverad = '1'";
$result = $mysqli->query($sql);
if ($result->num_rows > 0) {
    $echodata = array('vilt' => 'true');
    echo json_encode($echodata);
}
