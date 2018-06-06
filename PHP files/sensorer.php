<?php
//This is the file that is used then the mouisture sensor is registrate values
include 'db.php';
$idag = date("Y-m-d");
$nu = date("H:i:s");
$datum = date("Y-m-d", strtotime($idag));
$tid = date("H:i:s", strtotime($nu));
$jordtemp = $_GET['jordtemp'];
$jordfukt = $_GET['jordfukt'];
$sensor = $_GET['sensor'];
$volten = $_GET['volt'];
$sensorOk = 0;
$runanyway = 0;
$raining = 0;


$raknaut = $volten / 1000;
$volten = round($raknaut, 2);

$sql = "SELECT * FROM uppgifter WHERE id = '1'";
$result = $mysqli->query($sql);

if ($result->num_rows == 1) {
    while ($row = $result->fetch_assoc()) {
        $pumptid= $row["pumptid"];
        $fukttid= $row["fukttid"];
        $nattvatt= $row["nattvatt"];
        $starten= $row["starten"];
        $stoppen= $row["stoppen"];
    }
}

$sql = "SELECT * FROM sensorer WHERE sensornr = '$sensor'";
$result = $mysqli->query($sql);

if ($result->num_rows == 1) {
    while ($row = $result->fetch_assoc()) {
        $typ = $row["typ"];
        $pump = $row["pump"];
        $pfukt = $row["fuktighet"];
        $pauto = $row["autostart"];
        $paktiverad = $row["aktiverad"];
        $sensorid = $row["id"];
        if ($typ == 0) {
            $sensorOk = 1;
        } else {
            $sensorOk = 0;
        }
    }
} else {
    $sensorOk = 0;
}

if ($insert_stmt = $mysqli->prepare("UPDATE sensorer SET avlast = '1' WHERE id = ?")) {
    $insert_stmt->bind_param('s', $sensorid);
    if (! $insert_stmt->execute()) {
        exit();
    }
}

if ($nattvatt == 1) {
    //If night mode is on no pump is going to start
    if ($tid >= $starten && $tid <= $stoppen) {
        $runanyway = 1;
        if ($insert_stmt = $mysqli->prepare("UPDATE uppgifter SET nattlagepa = '1' WHERE id = '1'")) {
            if (! $insert_stmt->execute()) {
                exit();
            }
        }
    } else {
        $runanyway = 0;
        if ($insert_stmt = $mysqli->prepare("UPDATE uppgifter SET nattlagepa = '0' WHERE id = '1'")) {
            if (! $insert_stmt->execute()) {
                exit();
            }
        }
    }
} else {
    $runanyway = 0;
    if ($insert_stmt = $mysqli->prepare("UPDATE uppgifter SET nattlagepa = '0' WHERE id = '1'")) {
        if (! $insert_stmt->execute()) {
            exit();
        }
    }
}

//Check if the rain sensors har registrate raining, and if they have, dont auto start waterpump
$sql12 = "SELECT * FROM zonsensor WHERE sensor = '$sensorid'";
$result12 = $mysqli->query($sql12);
if ($result12->num_rows > 0) {
    while ($row12 = $result12->fetch_assoc()) {
        $zonnret= $row12["id"];

        $sql = "SELECT * FROM zoner WHERE id = '$zonnret'";
        $result = $mysqli->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $zonidet= $row["id"];
                $sql2 = "SELECT * FROM zonregn WHERE zon = '$zonidet'";
                $result2 = $mysqli->query($sql2);
                if ($result2->num_rows > 0) {
                    while ($row2 = $result2->fetch_assoc()) {
                        $rengnid= $row2["regn"];
                        $sql3 = "SELECT * FROM regnsensor WHERE id = '$rengnid'";
                        $result3 = $mysqli->query($sql3);
                        if ($result3->num_rows > 0) {
                            while ($row3 = $result3->fetch_assoc()) {
                                $rengnnr= $row3["regnid"];
                                $sql4 = "SELECT * FROM regndata WHERE regnnr = '$rengnnr' ORDER BY id DESC LIMIT 1";
                                $result4 = $mysqli->query($sql4);
                                if ($result4->num_rows > 0) {
                                    while ($row4 = $result4->fetch_assoc()) {
                                        $regnar= $row4["regnar"];
                                        if ($regnar == 0) {
                                            //It has not been raining, so we can water
                                            $raining = 0;
                                        } else {
                                            $raining = 1;
                                        }
                                    }
                                }
                            }
                        }
                    }
                } else {
                    //There is not any rain sensors connected
                    $raining = 0;
                }
            }
        }
    }
} else {
    //Not in any zones
    $raining = 0;
}

$sql = "SELECT * FROM pumpar WHERE id = '$pump'";
$result = $mysqli->query($sql);

if ($result->num_rows == 1) {
    while ($row = $result->fetch_assoc()) {
        $pumpnr = $row["pumpnr"];
        $status = $row["aktiverad"];
    }
}
if ($pauto == 1) {
    if ($jordfukt >= $pfukt) {
        $startapumpen = 0;
        if ($status == 1) {
            if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '0' WHERE pumpnr = ?")) {
                $insert_stmt->bind_param('s', $pumpnr);
                if (! $insert_stmt->execute()) {
                    exit();
                }
            }
        }
    } else {
        if ($raining == 1) {
            //It's raining don't start the pump
            $startapumpen = 0;
        } else {
            if ($runanyway == 1) {
                //Night mode is activated, don't start the pump
                $startapumpen = 0;
            } else {
                $sql2 = "SELECT * FROM pumpar WHERE id = '$pump' AND startad < (NOW() - INTERVAL ".$fukttid." MINUTE)";
                $result2 = $mysqli->query($sql2);

                if ($result2->num_rows > 0) {
                    while ($row2 = $result2->fetch_assoc()) {
                        $startapumpen = 1;
                        if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '1' WHERE pumpnr = ?")) {
                            $insert_stmt->bind_param('s', $pumpnr);
                            if (! $insert_stmt->execute()) {
                                exit();
                            }
                        }
                    }
                }
            }
        }
    } 
} else {
    if ($status == 1) {
        $startapumpen = 1;
    } else {
        $startapumpen = 0;
    }
}

if (isset($jordfukt)) {
    if ($sensorOk == 1) {
        if ($insert_stmt = $mysqli->prepare("INSERT INTO jordfukt (sensor, fukt, jordtemp, volt, datum, tid) VALUES (?, ?, ?, ?, ?, ?)")) {
            $insert_stmt->bind_param('ssssss', $sensor, $jordfukt, $jordtemp, $volten, $datum, $tid);
            if (! $insert_stmt->execute()) {
                exit();
            }
        }
    }
}
if (isset($volten)) {
    if ($sensorOk == 1) {
        if ($insert_stmt = $mysqli->prepare("UPDATE sensorer SET volt = ? WHERE sensornr = ?")) {
            $insert_stmt->bind_param('ss', $volten, $sensor);
            if (! $insert_stmt->execute()) {
                exit();
            }
        }
    }
}

echo '{"pump": '.$pumpnr.',"aktivera": '.$startapumpen.'}';
