<?php
include '../db.php';

//Run this cronjob every secounds, this holds all the automation.

$idag = date("Y-m-d");
$nu = date("H:i:s");
$datum = date("Y-m-d", strtotime($idag));
$tid = date("H:i:s", strtotime($nu));

$tidutansekund = date("H:i", strtotime($nu));
$curr_time = strtotime(date('Y-m-d H:i:s'));
$tidnu = time();

$sql = "SELECT * FROM uppgifter WHERE id = '1'";
$result = $mysqli->query($sql);

if ($result->num_rows == 1) {
    while ($row = $result->fetch_assoc()) {
        $pumptid= $row["pumptid"];
        $fukttid= $row["fukttid"];
        $hogvarme= $row["hogvarme"];
        $hogtemp= $row["hogtemp"];
        $nattvatt= $row["nattvatt"];
        $starten= $row["starten"];
        $stoppen= $row["stoppen"];
    }
}

if ($nattvatt == 1) {
    //Night mode enabled so do not water this time
    if ($tid >= $starten && $tid <= $stoppen) {
        if ($insert_stmt = $mysqli->prepare("UPDATE uppgifter SET nattlagepa = '1' WHERE id = '1'")) {
            if (! $insert_stmt->execute()) {
                exit();
            }
        }
    } else {
        //No night time so we can water now
        if ($insert_stmt = $mysqli->prepare("UPDATE uppgifter SET nattlagepa = '0' WHERE id = '1'")) {
            if (! $insert_stmt->execute()) {
                exit();
            }
        }




        //Check if raining
        $sql = "SELECT * FROM zoner";
        $result = $mysqli->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $zonidet= $row["id"];
                
                $sql2 = "SELECT * FROM zonregn WHERE zon = '$zonidet'";
                $result2 = $mysqli->query($sql2);
                if ($result2->num_rows > 0) {
                    while ($row2 = $result2->fetch_assoc()) {
                        //Get value from rain sensor
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
                                            //It has not raining so we can water


                                            //If the air heat is hot we need some extra water

                                            if ($hogvarme == 1) {
                                                $sql = "SELECT * FROM luftfukt ORDER BY id DESC LIMIT 1";
                                                $result = $mysqli->query($sql);

                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        $lufttemp = $row["temp"];

                                                        if ($lufttemp >= $hogtemp) {
                                                            if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '1', stod = '1' WHERE aktiverad = '0' AND startad < (NOW() - INTERVAL 2 HOUR)")) {
                                                                if (! $insert_stmt->execute()) {
                                                                    exit();
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                            $sql = "SELECT * FROM pumpar WHERE aktiverad = '1' AND stod = '1' AND startad < (NOW() - INTERVAL 1 MINUTE)";
                                            $result = $mysqli->query($sql);

                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    $pustart = $row["aktiverad"];
                                                    $pustartad = $row["startad"];
                                                    $puid = $row["id"];
                                                    $diff = $curr_time-$pustartad;
                                                    if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '0', stod = '0' WHERE id = '$puid'")) {
                                                        if (! $insert_stmt->execute()) {
                                                            exit();
                                                        }
                                                    }
                                                }
                                            }

                                            $sql = "SELECT * FROM pumpar WHERE aktiverad = '1' AND stod = '0' AND startad < (NOW() - INTERVAL ".$pumptid." MINUTE)";
                                            $result = $mysqli->query($sql);

                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    $pustart = $row["aktiverad"];
                                                    $pustartad = $row["startad"];
                                                    $puid = $row["id"];
                                                    $diff = $curr_time-$pustartad;
                                                    if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '0' WHERE id = '$puid'")) {
                                                        
                                                        if (! $insert_stmt->execute()) {
                                                            exit();
                                                        }
                                                    }
                                                }
                                            }

                                            $sql = "SELECT * FROM sensorer WHERE autostart = '1'";
                                            $result = $mysqli->query($sql);

                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    $aupump = $row["pump"];
                                                    $aufukt = $row["fuktighet"];
                                                    $auauto = $row["autostart"];
                                                    $aunr = $row["sensornr"];
                                                    $auid = $row["id"];

                                                    $sql2 = "SELECT * FROM pumpar WHERE id = '$aupump' AND stod = '0' AND startad < (NOW() - INTERVAL ".$fukttid." MINUTE)";
                                                    $result2 = $mysqli->query($sql2);

                                                    if ($result2->num_rows > 0) {
                                                        while ($row2 = $result2->fetch_assoc()) {
                                                            $spustart = $row2["aktiverad"];
                                                            $spustartad = $row2["startad"];
                                                            $spuid = $row2["id"];

                                                            $sql3 = "SELECT * FROM jordfukt WHERE sensor = '$aunr' ORDER BY id DESC LIMIT 1";
                                                            $result3 = $mysqli->query($sql3);

                                                            if ($result3->num_rows > 0) {
                                                                while ($row3 = $result3->fetch_assoc()) {
                                                                    $soufukt = $row3["fukt"];
                                                                    if ($auauto == 1 && $soufukt < $aufukt) {
                                                                        if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '1' WHERE id = '$spuid'")) {
                                                                            if (! $insert_stmt->execute()) {
                                                                                exit();
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }



                                            function getWeekday($date)
                                            {
                                                return date('w', strtotime($date));
                                            }

                                            $sql = "SELECT * FROM pumptimer";
                                            $result = $mysqli->query($sql);

                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    $mon = $row["mon"];
                                                    $tis = $row["tis"];
                                                    $ons = $row["ons"];
                                                    $tor = $row["tor"];
                                                    $fre = $row["fre"];
                                                    $lor = $row["lor"];
                                                    $son = $row["son"];
                                                    $start = $row["start"];
                                                    $slut = $row["slut"];
                                                    $pump = $row["pump"];
                                                    $start = strtotime($start);
                                                    $slut = strtotime($slut);

                                                    $startutan = date("H:i", strtotime($row["start"]));
                                                    $slututan = date("H:i", strtotime($row["slut"]));
                                                    $catid         = strtotime('+1 minute', $start);
                                                    $catidslut = strtotime('+1 minute', $slut);
                                                    if ($mon == '1') {
                                                        //Check if it that day today
                                                        if (getWeekday($datum) == 1) {
                                                            if ($startutan == $tidutansekund) {
                                                                if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '1' WHERE id = '$pump'")) {
                                                                    if (! $insert_stmt->execute()) {
                                                                        exit();
                                                                    }
                                                                }
                                                            } elseif ($slututan == $tidutansekund) {
                                                                if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '0' WHERE id = '$pump'")) {
                                                                    if (! $insert_stmt->execute()) {
                                                                        exit();
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                    if ($tis == '1') {
                                                    //Check if it that day today
                                                        if (getWeekday($datum) == 2) {
                                                            if ($startutan == $tidutansekund) {
                                                                if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '1' WHERE id = '$pump'")) {
                                                                    if (! $insert_stmt->execute()) {
                                                                        exit();
                                                                    }
                                                                }
                                                            } elseif ($slututan == $tidutansekund) {
                                                                if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '0' WHERE id = '$pump'")) {
                                                                    if (! $insert_stmt->execute()) {
                                                                        exit();
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    } //
                                                    if ($ons == '1') {
                                                        //Check if it that day today
                                                        if (getWeekday($datum) == 3) {
                                                            if ($startutan == $tidutansekund) {
                                                                if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '1' WHERE id = '$pump'")) {
                                                                    if (! $insert_stmt->execute()) {
                                                                        exit();
                                                                    }
                                                                }
                                                            } elseif ($slututan == $tidutansekund) {
                                                                if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '0' WHERE id = '$pump'")) {
                                                                    if (! $insert_stmt->execute()) {
                                                                        exit();
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    } //
                                                    if ($tor == '1') {
                                                        //Check if it that day today
                                                        if (getWeekday($datum) == 4) {
                                                            if ($startutan == $tidutansekund) {
                                                                if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '1' WHERE id = '$pump'")) {
                                                                    if (! $insert_stmt->execute()) {
                                                                        exit();
                                                                    }
                                                                }
                                                            } elseif ($slututan == $tidutansekund) {
                                                                if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '0' WHERE id = '$pump'")) {
                                                                    if (! $insert_stmt->execute()) {
                                                                        exit();
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    } //
                                                    if ($fre == '1') {
                                                        //Check if it that day today
                                                        if (getWeekday($datum) == 5) {
                                                            if ($startutan == $tidutansekund) {
                                                                if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '1' WHERE id = '$pump'")) {
                                                                    if (! $insert_stmt->execute()) {
                                                                        exit();
                                                                    }
                                                                }
                                                            } elseif ($slututan == $tidutansekund) {
                                                                if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '0' WHERE id = '$pump'")) {
                                                                    if (! $insert_stmt->execute()) {
                                                                        exit();
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    } //
                                                    if ($lor == '1') {
                                                        //Check if it that day today
                                                        if (getWeekday($datum) == 6) {
                                                            if ($startutan == $tidutansekund) {
                                                                if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '1' WHERE id = '$pump'")) {
                                                                    if (! $insert_stmt->execute()) {
                                                                        exit();
                                                                    }
                                                                }
                                                            } elseif ($slututan == $tidutansekund) {
                                                                if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '0' WHERE id = '$pump'")) {
                                                                    if (! $insert_stmt->execute()) {
                                                                        exit();
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    } //
                                                    if ($son == '1') {
                                                        //Check if it that day today
                                                        if (getWeekday($datum) == 0) {
                                                            if ($startutan == $tidutansekund) {
                                                                if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '1' WHERE id = '$pump'")) {
                                                                    if (! $insert_stmt->execute()) {
                                                                        exit();
                                                                    }
                                                                }
                                                            } elseif ($slututan == $tidutansekund) {
                                                                if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '0' WHERE id = '$pump'")) {
                                                                    if (! $insert_stmt->execute()) {
                                                                        exit();
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    } 
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        //Night mode stops here
    }
} else {
    //Night mode is not enabled so lets begin
    if ($insert_stmt = $mysqli->prepare("UPDATE uppgifter SET nattlagepa = '0' WHERE id = '1'")) {
        if (! $insert_stmt->execute()) {
            exit();
        }
    }
    //Check if it's raining
    $sql = "SELECT * FROM zoner";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $zonidet= $row["id"];
            $sql2 = "SELECT * FROM zonregn WHERE zon = '$zonidet'";
            $result2 = $mysqli->query($sql2);
            if ($result2->num_rows > 0) {
                while ($row2 = $result2->fetch_assoc()) {
                    //Get values from rain sensors
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
                                        //It has not been raining so lets begin


                                        //If extra watering on high temp is activated

                                        if ($hogvarme == 1) {
                                            $sql = "SELECT * FROM luftfukt ORDER BY id DESC LIMIT 1";
                                            $result = $mysqli->query($sql);

                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    $lufttemp = $row["temp"];

                                                    if ($lufttemp >= $hogtemp) {
                                                        if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '1', stod = '1' WHERE aktiverad = '0' AND startad < (NOW() - INTERVAL 2 HOUR)")) {
                                                            if (! $insert_stmt->execute()) {
                                                                exit();
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }

                                        $sql = "SELECT * FROM pumpar WHERE aktiverad = '1' AND stod = '1' AND startad < (NOW() - INTERVAL 1 MINUTE)";
                                        $result = $mysqli->query($sql);

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $pustart = $row["aktiverad"];
                                                $pustartad = $row["startad"];
                                                $puid = $row["id"];
                                                $diff = $curr_time-$pustartad;
                                                if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '0', stod = '0' WHERE id = '$puid'")) {
                                                    if (! $insert_stmt->execute()) {
                                                        exit();
                                                    }
                                                    
                                                }
                                            }
                                        }

                                        $sql = "SELECT * FROM pumpar WHERE aktiverad = '1' AND stod = '0' AND startad < (NOW() - INTERVAL ".$pumptid." MINUTE)";
                                        $result = $mysqli->query($sql);

                                        if ($result->num_rows > 0) {
                                             while ($row = $result->fetch_assoc()) {
                                                $pustart = $row["aktiverad"];
                                                $pustartad = $row["startad"];
                                                $puid = $row["id"];
                                                $diff = $curr_time-$pustartad;
                                                if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '0' WHERE id = '$puid'")) {
                                                    if (! $insert_stmt->execute()) {
                                                        exit();
                                                    }
                                                    
                                                }
                                            }
                                        }

                                        $sql = "SELECT * FROM sensorer WHERE autostart = '1'";
                                        $result = $mysqli->query($sql);

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $aupump = $row["pump"];
                                                $aufukt = $row["fuktighet"];
                                                $auauto = $row["autostart"];
                                                $aunr = $row["sensornr"];
                                                $auid = $row["id"];

                                                $sql2 = "SELECT * FROM pumpar WHERE id = '$aupump' AND stod = '0' AND startad < (NOW() - INTERVAL ".$fukttid." MINUTE)";
                                                $result2 = $mysqli->query($sql2);

                                                if ($result2->num_rows > 0) {
                                                    while ($row2 = $result2->fetch_assoc()) {
                                                        $spustart = $row2["aktiverad"];
                                                        $spustartad = $row2["startad"];
                                                        $spuid = $row2["id"];

                                                        $sql3 = "SELECT * FROM jordfukt WHERE sensor = '$aunr' ORDER BY id DESC LIMIT 1";
                                                        $result3 = $mysqli->query($sql3);

                                                        if ($result3->num_rows > 0) {
                                                            while ($row3 = $result3->fetch_assoc()) {
                                                                $soufukt = $row3["fukt"];
                                                                if ($auauto == 1 && $soufukt < $aufukt) {
                                                                    if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '1' WHERE id = '$spuid'")) {
                                                                        if (! $insert_stmt->execute()) {
                                                                            exit();
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }



                                        function getWeekday($date)
                                        {
                                            return date('w', strtotime($date));
                                        }

                                        $sql = "SELECT * FROM pumptimer";
                                        $result = $mysqli->query($sql);

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $mon = $row["mon"];
                                                $tis = $row["tis"];
                                                $ons = $row["ons"];
                                                $tor = $row["tor"];
                                                $fre = $row["fre"];
                                                $lor = $row["lor"];
                                                $son = $row["son"];
                                                $start = $row["start"];
                                                $slut = $row["slut"];
                                                $pump = $row["pump"];
                                                $start = strtotime($start);
                                                $slut = strtotime($slut);

                                                $startutan = date("H:i", strtotime($row["start"]));
                                                $slututan = date("H:i", strtotime($row["slut"]));
                                                $catid         = strtotime('+1 minute', $start);
                                                $catidslut = strtotime('+1 minute', $slut);
                                                if ($mon == '1') {
                                                    //Check if that day is today:
                                                    if (getWeekday($datum) == 1) {
                                                        if ($startutan == $tidutansekund) {
                                                            if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '1' WHERE id = '$pump'")) {
                                                                 if (! $insert_stmt->execute()) {
                                                                    exit();
                                                                }
                                                               
                                                            }
                                                        } elseif ($slututan == $tidutansekund) {
                                                            if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '0' WHERE id = '$pump'")) {
                                                                if (! $insert_stmt->execute()) {
                                                                    exit();
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                if ($tis == '1') {
                                                    //Check if that day is today:
                                                    if (getWeekday($datum) == 2) {
                                                        if ($startutan == $tidutansekund) {
                                                            if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '1' WHERE id = '$pump'")) {
                                                                if (! $insert_stmt->execute()) {
                                                                    exit();
                                                                }
                                                            }
                                                        } elseif ($slututan == $tidutansekund) {
                                                            if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '0' WHERE id = '$pump'")) {
                                                                if (! $insert_stmt->execute()) {
                                                                    exit();
                                                                }
                                                            }
                                                        }
                                                    }
                                                } //
                                                if ($ons == '1') {
                                                //Check if that day is today:
                                                    if (getWeekday($datum) == 3) {
                                                        if ($startutan == $tidutansekund) {
                                                            if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '1' WHERE id = '$pump'")) {
                                                                // Execute the prepared query.
                                                                if (! $insert_stmt->execute()) {
                                                                    exit();
                                                                }
                                                            }
                                                        } elseif ($slututan == $tidutansekund) {
                                                            if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '0' WHERE id = '$pump'")) {
                                                                if (! $insert_stmt->execute()) {
                                                                    exit();
                                                                }
                                                            }
                                                        }
                                                    }
                                                } //
                                                if ($tor == '1') {
                                                    //Check if that day is today:
                                                    if (getWeekday($datum) == 4) {
                                                        if ($startutan == $tidutansekund) {
                                                            if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '1' WHERE id = '$pump'")) {
                                                                if (! $insert_stmt->execute()) {
                                                                    exit();
                                                                }
                                                            }
                                                        } elseif ($slututan == $tidutansekund) {
                                                            if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '0' WHERE id = '$pump'")) {
                                                                if (! $insert_stmt->execute()) {
                                                                    exit();
                                                                }
                                                            }
                                                        }
                                                    }
                                                } //
                                                if ($fre == '1') {
                                                    //Check if that day is today:
                                                    if (getWeekday($datum) == 5) {
                                                        if ($startutan == $tidutansekund) {
                                                            if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '1' WHERE id = '$pump'")) {
                                                                if (! $insert_stmt->execute()) {
                                                                    exit();
                                                                }
                                                            }
                                                        } elseif ($slututan == $tidutansekund) {
                                                            if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '0' WHERE id = '$pump'")) {
                                                                if (! $insert_stmt->execute()) {
                                                                    exit();
                                                                }
                                                                
                                                            }
                                                        }
                                                    }
                                                } //
                                                if ($lor == '1') {
                                                    //Check if that day is today:
                                                    if (getWeekday($datum) == 6) {
                                                        if ($startutan == $tidutansekund) {
                                                            if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '1' WHERE id = '$pump'")) {
                                                                if (! $insert_stmt->execute()) {
                                                                    exit();
                                                                }
                                                            }
                                                        } elseif ($slututan == $tidutansekund) {
                                                            if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '0' WHERE id = '$pump'")) {
                                                                if (! $insert_stmt->execute()) {
                                                                    exit();
                                                                }
                                                            }
                                                        }
                                                    }
                                                } //
                                                if ($son == '1') {
                                                    //Check if that day is today:
                                                    if (getWeekday($datum) == 0) {
                                                        if ($startutan == $tidutansekund) {
                                                            if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '1' WHERE id = '$pump'")) {
                                                                if (! $insert_stmt->execute()) {
                                                                    exit();
                                                                }
                                                            }
                                                        } elseif ($slututan == $tidutansekund) {
                                                            if ($insert_stmt = $mysqli->prepare("UPDATE pumpar SET aktiverad = '0' WHERE id = '$pump'")) {
                                                                if (! $insert_stmt->execute()) {
                                                                    exit();
                                                                }
                                                            }
                                                        }
                                                    }
                                                } 
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

//Animal Timer

$sql = "SELECT * FROM vilttimer";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $mon = $row["mon"];
        $tis = $row["tis"];
        $ons = $row["ons"];
        $tor = $row["tor"];
        $fre = $row["fre"];
        $lor = $row["lor"];
        $son = $row["son"];
        $start = $row["start"];
        $slut = $row["slut"];
        $pump = $row["vilt"];
        $start = strtotime($start);
        $slut = strtotime($slut);

        $startutan = date("H:i", strtotime($row["start"]));
        $slututan = date("H:i", strtotime($row["slut"]));
        $catid         = strtotime('+1 minute', $start);
        $catidslut = strtotime('+1 minute', $slut);
        if ($mon == '1') {
            //Check if that day is today:
            if (getWeekday($datum) == 1) {
                if ($startutan == $tidutansekund) {
                    if ($insert_stmt = $mysqli->prepare("UPDATE vilt SET aktiverad = '1' WHERE id = '$pump'")) {
                        if (! $insert_stmt->execute()) {
                            exit();
                        }
                    }
                } elseif ($slututan == $tidutansekund) {
                    if ($insert_stmt = $mysqli->prepare("UPDATE vilt SET aktiverad = '0' WHERE id = '$pump'")) {
                        if (! $insert_stmt->execute()) {
                            exit();
                        }
                    }
                }
            }
        }
        if ($tis == '1') {
            //Check if that day is today:
            if (getWeekday($datum) == 2) {
                if ($startutan == $tidutansekund) {
                    if ($insert_stmt = $mysqli->prepare("UPDATE vilt SET aktiverad = '1' WHERE id = '$pump'")) {
                        if (! $insert_stmt->execute()) {
                            exit();
                        }
                    }
                } elseif ($slututan == $tidutansekund) {
                    if ($insert_stmt = $mysqli->prepare("UPDATE vilt SET aktiverad = '0' WHERE id = '$pump'")) {
                        if (! $insert_stmt->execute()) {
                            exit();
                        }
                    }
                }
            }
        } //
        if ($ons == '1') {
            //Check if that day is today:
            if (getWeekday($datum) == 3) {
                if ($startutan == $tidutansekund) {
                    if ($insert_stmt = $mysqli->prepare("UPDATE vilt SET aktiverad = '1' WHERE id = '$pump'")) {
                        if (! $insert_stmt->execute()) {
                            exit();
                        }
                    }
                } elseif ($slututan == $tidutansekund) {
                    if ($insert_stmt = $mysqli->prepare("UPDATE vilt SET aktiverad = '0' WHERE id = '$pump'")) {
                        if (! $insert_stmt->execute()) {
                            exit();
                        }
                    }
                }
            }
        } //
        if ($tor == '1') {
            //Check if that day is today:
            if (getWeekday($datum) == 4) {
                if ($startutan == $tidutansekund) {
                    if ($insert_stmt = $mysqli->prepare("UPDATE vilt SET aktiverad = '1' WHERE id = '$pump'")) {
                        if (! $insert_stmt->execute()) {
                            exit();
                        }
                    }
                } elseif ($slututan == $tidutansekund) {
                    if ($insert_stmt = $mysqli->prepare("UPDATE vilt SET aktiverad = '0' WHERE id = '$pump'")) {
                        if (! $insert_stmt->execute()) {
                            exit();
                        }
                    }
                }
            }
        } //
        if ($fre == '1') {
            //Check if that day is today:
            if (getWeekday($datum) == 5) {
                if ($startutan == $tidutansekund) {
                    if ($insert_stmt = $mysqli->prepare("UPDATE vilt SET aktiverad = '1' WHERE id = '$pump'")) {
                        if (! $insert_stmt->execute()) {
                            exit();
                        }
                    }
                } elseif ($slututan == $tidutansekund) {
                    if ($insert_stmt = $mysqli->prepare("UPDATE vilt SET aktiverad = '0' WHERE id = '$pump'")) {
                        if (! $insert_stmt->execute()) {
                            exit();
                        }
                    }
                }
            }
        } //
        if ($lor == '1') {
            //Check if that day is today:
            if (getWeekday($datum) == 6) {
                if ($startutan == $tidutansekund) {
                    if ($insert_stmt = $mysqli->prepare("UPDATE vilt SET aktiverad = '1' WHERE id = '$pump'")) {
                        if (! $insert_stmt->execute()) {
                            exit();
                        }
                    }
                } elseif ($slututan == $tidutansekund) {
                    if ($insert_stmt = $mysqli->prepare("UPDATE vilt SET aktiverad = '0' WHERE id = '$pump'")) {
                        if (! $insert_stmt->execute()) {
                            exit();
                        }
                    }
                }
            }
        } //
        if ($son == '1') {
            //Check if that day is today:
            if (getWeekday($datum) == 0) {
                if ($startutan == $tidutansekund) {
                    if ($insert_stmt = $mysqli->prepare("UPDATE vilt SET aktiverad = '1' WHERE id = '$pump'")) {
                        if (! $insert_stmt->execute()) {
                            exit();
                        }
                    }
                } elseif ($slututan == $tidutansekund) {
                    if ($insert_stmt = $mysqli->prepare("UPDATE vilt SET aktiverad = '0' WHERE id = '$pump'")) {
                        if (! $insert_stmt->execute()) {
                            exit();
                        }
                    }
                }
            }
        } 
    }
}
