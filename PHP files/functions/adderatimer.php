<?php
include '../db.php';
$pump = $_POST['pump'];
$namn = $_POST['namn'];
$start = $_POST['start'];
$slut = $_POST['slut'];
$mon = $_POST['mon'];
$tis = $_POST['tis'];
$ons = $_POST['ons'];
$tors = $_POST['tors'];
$fre = $_POST['fre'];
$lor = $_POST['lor'];
$son = $_POST['son'];
$uppdatera = $_POST['uppdatera'];
$idnumret = $_POST['idnumret'];

if (isset($mon)) {
    $mon = 1;
} else {
    $mon = 0;
}
if (isset($tis)) {
    $tis = 1;
} else {
    $tis = 0;
}
if (isset($ons)) {
    $ons = 1;
} else {
    $ons = 0;
}
if (isset($tors)) {
    $tors = 1;
} else {
    $tors = 0;
}
if (isset($fre)) {
    $fre = 1;
} else {
    $fre = 0;
}
if (isset($lor)) {
    $lor = 1;
} else {
    $lor = 0;
}
if (isset($son)) {
    $son = 1;
} else {
    $son = 0;
}



$existerar = 0;


if ($uppdatera == 1) {
    if (isset($idnumret, $namn)) {
        $sql = "SELECT * FROM pumptimer WHERE id = '$idnumret'";
        $result = $mysqli->query($sql);

        if ($result->num_rows == 1) {
            $existerar = 1;
        } else {
            $existerar = 0;

            $echodata = array('error' => 'true', 'errorcode' => '0');
            echo json_encode($echodata);
        }

        if ($existerar == 1) {
            if ($insert_stmt = $mysqli->prepare("UPDATE pumptimer SET namn = ?, start = ?, slut = ?, mon = ?, tis = ?, ons = ?, tor = ?, fre = ?, lor = ?, son = ?, pump = ? WHERE id = ?")) {
                $insert_stmt->bind_param('ssssssssssss', $namn, $start, $slut, $mon, $tis, $ons, $tors, $fre, $lor, $son, $pump, $idnumret);
                if (! $insert_stmt->execute()) {
                    $echodata = array('error' => 'true', 'errorcode' => '1');
                    echo json_encode($echodata);
                    exit();
                }
                $echodata = array('error' => 'false', 'errorcode' => '7');
                echo json_encode($echodata);
            }
        }
    } else {
        $echodata = array('error' => 'true', 'errorcode' => '2');
        echo json_encode($echodata);
    }
} else {
    if (isset($pump, $namn)) {
        $sql = "SELECT * FROM pumpar WHERE id = '$pump'";
        $result = $mysqli->query($sql);

        if ($result->num_rows == 1) {
            $existerar = 1;
        } else {
            $existerar = 0;
            $echodata = array('error' => 'true', 'errorcode' => '0');
            echo json_encode($echodata);
        }

        if ($existerar == 1) {
            if ($insert_stmt = $mysqli->prepare("INSERT INTO pumptimer (namn, start, slut, mon, tis, ons, tor, fre, lor, son, pump) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
                $insert_stmt->bind_param('sssssssssss', $namn, $start, $slut, $mon, $tis, $ons, $tors, $fre, $lor, $son, $pump);
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
}
