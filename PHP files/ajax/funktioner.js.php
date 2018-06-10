<?php
include '../i18n_setup.php';
include '../db.php';
$sql = "SELECT * FROM uppgifter WHERE id = '1'";
$result = $mysqli->query($sql);

if ($result->num_rows == 1) {
    // Räkna ihop antalet reserverade stolar
    while ($row = $result->fetch_assoc()) {
        $pumptid= $row["pumptid"];
        $fukttid= $row["fukttid"];
        $hogvarme= $row["hogvarme"];
        $hogtemp= $row["hogtemp"];
        $nattlagepa= $row["nattlagepa"];
    }
}

if ($nattlagepa == 1) {
    ?>
  new PNotify({
      title: '<?=gettext('Nattläge aktiverat')?>',
      text: '<?=gettext('Nattläge är aktiverat så ingen pumpautomatik eller timer kommer köras.')?>',
      icon: 'icofont icofont-info-circle',
      type: 'info'
  });
<?php
}

$sql = "SELECT * FROM sensorer WHERE volt <= '3.20'";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    // Gör jquery
    while ($row = $result->fetch_assoc()) {
        $sensornr = $row["sensornr"];
        $sensornamn = $row["namn"]; ?>
        new PNotify({
            title: '<?=gettext('Låg batteri nivå i jordsensor.')?>',
            text: '<?=gettext('Det börjar bli låg batteri nivå i')?> <?php echo $sensornr; ?> - <?php echo $sensornamn; ?>',
            icon: 'icofont icofont-info-circle',
            type: 'error'
        });

        <?php
    }
}

$sql = "SELECT * FROM regnsensor WHERE volt <= '3.20'";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    // Gör jquery
    while ($row = $result->fetch_assoc()) {
        $vregnnr = $row["regnid"];
        $vregnnamn = $row["namn"]; ?>
        new PNotify({
            title: '<?=gettext('Låg batteri nivå i regnsensor.')?>',
            text: '<?=gettext('Det börjar bli låg batteri nivå i')?> <?php echo $vregnnr; ?> - <?php echo $vregnnamn; ?>',
            icon: 'icofont icofont-info-circle',
            type: 'error'
        });

        <?php
    }
}

$sql = "SELECT * FROM vilt WHERE volt <= '3.20'";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    // Gör jquery
    while ($row = $result->fetch_assoc()) {
        $vviltnr = $row["viltnr"];
        $vviltnamn = $row["namn"]; ?>
        new PNotify({
            title: '<?=gettext('Låg batteri nivå i viltsensor.')?>',
            text: '<?=gettext('Det börjar bli låg batteri nivå i')?> <?php echo $vviltnr; ?> - <?php echo $vviltnamn; ?>',
            icon: 'icofont icofont-info-circle',
            type: 'error'
        });

        <?php
    }
}

//zonregn
$sql = "SELECT * FROM zoner";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    // Räkna ihop antalet reserverade stolar
    while ($row = $result->fetch_assoc()) {
        $zonidet= $row["id"];
        //Nu kollar vi om det finns någon regnsensor kopplad till zonen
        $sql2 = "SELECT * FROM zonregn WHERE zon = '$zonidet'";
        $result2 = $mysqli->query($sql2);
        if ($result2->num_rows > 0) {
            // Räkna ihop antalet reserverade stolar
            while ($row2 = $result2->fetch_assoc()) {
                //Nu hämtar vi uppgifter från Regnsensorn
                $rengnid= $row2["regn"];
                $sql3 = "SELECT * FROM regnsensor WHERE id = '$rengnid'";
                $result3 = $mysqli->query($sql3);
                if ($result3->num_rows > 0) {
                    // Räkna ihop antalet reserverade stolar
                    while ($row3 = $result3->fetch_assoc()) {
                        //Nu hämtar senaste värdet
                        $rengnnr= $row3["regnid"];
                        $sql4 = "SELECT * FROM regndata WHERE regnnr = '$rengnnr' ORDER BY id DESC LIMIT 1";
                        $result4 = $mysqli->query($sql4);
                        if ($result4->num_rows > 0) {
                            // Räkna ihop antalet reserverade stolar
                            while ($row4 = $result4->fetch_assoc()) {
                                //Vi ska nu kolla om det har regnat nyligen om så fallet så ska inga automatiska bevattningar startas! Även timers!
                                $regnar= $row4["regnar"];
                                if ($regnar == 1) {
                                    ?>
                                  new PNotify({
                                      title: '<?=gettext('Det har regnat')?>',
                                      text: '<?=gettext('Det har eller så regnar det just nu så ingen automatiskt bevattning kommer att köras.')?>',
                                      icon: 'icofont icofont-info-circle',
                                      type: 'info'
                                  });

                            <?php
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

if ($hogvarme == 1) {
    $sql = "SELECT * FROM luftfukt ORDER BY id DESC LIMIT 1";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        // Räkna ihop antalet reserverade stolar
        while ($row = $result->fetch_assoc()) {
            $lufttemp = $row["temp"];

            if ($lufttemp >= $hogtemp) {
                ?>
            new PNotify({
                title: '<?=gettext('Stöd bevattning')?>',
                text: '<?=gettext('Det är extra varmt idag så stöd bevattning i en minut varannan timme kommer att köras.')?>',
                icon: 'icofont icofont-info-circle',
                type: 'info'
            });

      <?php
            }
        }
    }
} else {
    $sql = "SELECT * FROM luftfukt ORDER BY id DESC LIMIT 1";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        // Räkna ihop antalet reserverade stolar
        while ($row = $result->fetch_assoc()) {
            $lufttemp = $row["temp"];

            if ($lufttemp >= $hogtemp) {
                ?>
            new PNotify({
                title: '<?=gettext('Extra vattning behövs')?>',
                text: '<?=gettext('Det är extra varmt idag så stöd bevattning behövs så inte odlingen torkar ut.')?>',
                icon: 'icofont icofont-info-circle',
                type: 'info'
            });

      <?php
            }
        }
    }
}

$sql = "SELECT * FROM pumpar WHERE aktiverad = '1'";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    // Gör jquery
    while ($row = $result->fetch_assoc()) {
        $pumpnr = $row["pumpnr"];
        $pumpnamn = $row["namn"]; ?>
        new PNotify({
            title: '<?=gettext('Bevattnings pump aktiverad')?>',
            text: '<?=gettext('Bevattning')?> <?php echo $pumpnr; ?> - <?php echo $pumpnamn; ?> <?=gettext('är aktiverad och körs nu.')?>',
            icon: 'icofont icofont-info-circle',
            type: 'info'
        });

        <?php
    }
}


 $sql = "SELECT * FROM vilt WHERE aktiverad = '1'";
 $result = $mysqli->query($sql);

 if ($result->num_rows > 0) {
     // Gör jquery
     while ($row = $result->fetch_assoc()) {
         $viltnr = $row["viltnr"];
         $viltnamn = $row["namn"]; ?>
         new PNotify({
             title: '<?=gettext('Viltvanare aktiverad')?>',
             text: '<?=gettext('Viltvanare')?> <?php echo $viltnr; ?> - <?php echo $viltnamn; ?> <?=gettext('är aktiverad och redo att larma.')?>',
             icon: 'icofont icofont-info-circle',
             type: 'default'
         });

         <?php
     }
 }
  ?>
