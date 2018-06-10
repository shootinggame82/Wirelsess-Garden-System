<?php
include '../db.php';
$idnr = $_POST['idnr'];
$torr = $_POST['torr'];
$blot = $_POST['blot'];
$namn = $_POST['namn'];
$pumptid = $_POST['pumptid'];
$fukttid = $_POST['autotid'];
$stod = $_POST['stod'];
$hogtemp = $_POST['hogtemp'];
$sirentid = $_POST['sirentid'];
$torr1 = $_POST['torr1'];
$blot1 = $_POST['blot1'];
$nattvatt = $_POST['nattvatt'];
$starten = $_POST['start'];
$stoppen = $_POST['slut'];

if (isset($nattvatt)) {
    $nattvatt = 1;
} else {
    $nattvatt = 0;
}

$existerar = 0;


if (isset($idnr, $torr)) {
    $sql = "SELECT * FROM uppgifter WHERE id = '$idnr'";
    $result = $mysqli->query($sql);

    if ($result->num_rows == 1) {
        $existerar = 1;
    } else {
        $existerar = 0;

        $echodata = array('error' => 'true', 'errorcode' => '0');
        echo json_encode($echodata);
    }

    if ($existerar == 1) {
        if ($insert_stmt = $mysqli->prepare("UPDATE uppgifter SET torrjord = ?, blotjord = ?, namn = ?, pumptid = ?, fukttid = ?, hogvarme = ?, hogtemp = ?, sirentid = ?, torrregn = ?, blotregn = ?, nattvatt = ?, starten = ?, stoppen = ? WHERE id = ?")) {
            $insert_stmt->bind_param('ssssssssssssss', $torr, $blot, $namn, $pumptid, $fukttid, $stod, $hogtemp, $sirentid, $torr1, $blot1, $nattvatt, $starten, $stoppen, $idnr);
            if (! $insert_stmt->execute()) {
                $echodata = array('error' => 'true', 'errorcode' => '1');
                echo json_encode($echodata);
                exit();
            }
            $echodata = array('error' => 'false', 'errorcode' => '0');
            echo json_encode($echodata);
        }
    }
} else {
    $echodata = array('error' => 'true', 'errorcode' => '2');
    echo json_encode($echodata);
}
