<?php
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
    // Räkna ihop antalet reserverade stolar
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
    // Räkna ihop antalet reserverade stolar
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
    // Execute the prepared query.
    if (! $insert_stmt->execute()) {
        exit();
    }
}

if ($nattvatt == 1) {
    //Nattvakten är aktiverad så vi ska inte starta sensorns pump om det är inom tidsperioden.
    if ($tid >= $starten && $tid <= $stoppen) {
        //Vi är inom tidsperioden nu så här skall inte pumpen gå igång.
        $runanyway = 1;
        if ($insert_stmt = $mysqli->prepare("UPDATE uppgifter SET nattlagepa = '1' WHERE id = '1'")) {
            // Execute the prepared query.
            if (! $insert_stmt->execute()) {
                exit();
            }
            //Slutförd
        }
    } else {
        $runanyway = 0;
        if ($insert_stmt = $mysqli->prepare("UPDATE uppgifter SET nattlagepa = '0' WHERE id = '1'")) {
            // Execute the prepared query.
            if (! $insert_stmt->execute()) {
                exit();
            }
            //Slutförd
        }
    }
} else {
    $runanyway = 0;
    if ($insert_stmt = $mysqli->prepare("UPDATE uppgifter SET nattlagepa = '0' WHERE id = '1'")) {
        // Execute the prepared query.
        if (! $insert_stmt->execute()) {
            exit();
        }
        //Slutförd
    }
}

//zonregn för att kolla om det har regnat så skall den skippa autostart
//Börja med att kolla i vilka zoner sensorn finns $sensorid
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
                //Nu kollar vi om det finns någon regnsensor kopplad till zonen
                $sql2 = "SELECT * FROM zonregn WHERE zon = '$zonidet'";
                $result2 = $mysqli->query($sql2);
                if ($result2->num_rows > 0) {
                    while ($row2 = $result2->fetch_assoc()) {
                        //Nu hämtar vi uppgifter från Regnsensorn
                        $rengnid= $row2["regn"];
                        $sql3 = "SELECT * FROM regnsensor WHERE id = '$rengnid'";
                        $result3 = $mysqli->query($sql3);
                        if ($result3->num_rows > 0) {
                            while ($row3 = $result3->fetch_assoc()) {
                                //Nu hämtar senaste värdet
                                $rengnnr= $row3["regnid"];
                                $sql4 = "SELECT * FROM regndata WHERE regnnr = '$rengnnr' ORDER BY id DESC LIMIT 1";
                                $result4 = $mysqli->query($sql4);
                                if ($result4->num_rows > 0) {
                                    while ($row4 = $result4->fetch_assoc()) {
                                        //Vi ska nu kolla om det har regnat nyligen om så fallet så ska inga automatiska bevattningar startas! Även timers!
                                        $regnar= $row4["regnar"];
                                        if ($regnar == 0) {
                                            //Det har inte regnat inom den senaste 30 minutrarna så bevattning kan köras
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
                    //Det finns ingen regnsensor så vi skippar detta steget
                    $raining = 0;
                }
            }
        }
    }
} else {
    //Inte med i någon zon så vi skippar detta steget
    $raining = 0;
}

$sql = "SELECT * FROM pumpar WHERE id = '$pump'";
$result = $mysqli->query($sql);

if ($result->num_rows == 1) {
    // Räkna ihop antalet reserverade stolar
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
                // Execute the prepared query.
                if (! $insert_stmt->execute()) {
                    exit();
                }
            }
        }
    } else {
        if ($raining == 1) {
            //Det regnar, skippa start
            $startapumpen = 0;
        } else {
            if ($runanyway == 1) {
                //Nattläge aktiverat skippa start
                $startapumpen = 0;
            } else {
                $sql2 = "SELECT * FROM pumpar WHERE id = '$pump' AND startad < (NOW() - INTERVAL ".$fukttid." MINUTE)";
                $result2 = $mysqli->query($sql2);

                if ($result2->num_rows > 0) {
                    // Räkna ihop antalet reserverade stolar
                    while ($row2 = $result2->fetch_assoc()) {
                        $startapumpen = 1;
                        if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '1' WHERE pumpnr = ?")) {
                            $insert_stmt->bind_param('s', $pumpnr);
                            // Execute the prepared query.
                            if (! $insert_stmt->execute()) {
                                exit();
                            }
                        }
                    }
                }
            }
        }
    } //Else slut
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
            // Execute the prepared query.
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
            // Execute the prepared query.
            if (! $insert_stmt->execute()) {
                exit();
            }
        }
    }
}

echo '{"pump": '.$pumpnr.',"aktivera": '.$startapumpen.'}';
