<?php
header('Content-Type: application/json');
include_once '../db.php';
$orgid=$_GET['id'];
$data = array();

$sql = "SELECT id, namn, start, slut, mon, tis, ons, tor, fre, lor, son, pump FROM pumptimer WHERE id='$orgid'";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    $data[] = json_encode($row);
}

echo json_encode($data);
