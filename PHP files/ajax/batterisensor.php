<?php
//header('Content-Type: application/json');
include '../db.php';

$sql = "SELECT volt FROM sensorer WHERE volt <= '6.50'";
$result = $mysqli->query($sql);
if ($result->num_rows > 0) {
    $echodata = array('sensbat' => 'true');
    echo json_encode($echodata);
}
