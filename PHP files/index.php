<?php
include 'i18n_setup.php';
include 'db.php';
include("check.php");
$idag = date("Y-m-d");
$nu = date("H:i:s");
$datum = date("Y-m-d", strtotime($idag));
$tid = date("H:i:s", strtotime($nu));
$existerar = 0;

$sql = "SELECT * FROM uppgifter WHERE id = '1'";
$result = $mysqli->query($sql);

if ($result->num_rows == 1) {
    while ($row = $result->fetch_assoc()) {
        $sys_name = $row["namn"];
        $dlast = $row["datum"];
        $tlast = $row["tid"];
    }
}

$sql = "SELECT * FROM luftfukt ORDER BY id DESC LIMIT 1";
$result = $mysqli->query($sql);

if ($result->num_rows == 1) {
    while ($row = $result->fetch_assoc()) {
        $ltemp = $row["temp"];
        $lfukt = $row["fukt"];
        $lheat = $row["heat"];
    }
}

$currentPage = 'index';
 ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo $sys_name; ?></title>
    <!-- HTML5 Shim and Respond.js IE10 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 10]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Wireless Watering System" />
    <meta name="keywords" content="arduino, water" />
    <meta name="author" content="Andreas Olsson" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="files/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/pages/waves/css/waves.min.css" type="text/css" media="all">
    <link rel="stylesheet" type="text/css" href="assets/icon/feather/css/feather.css">
    <link rel="stylesheet" type="text/css" href="assets/icon/weather-icons/css/weather-icons.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="assets/css/widget.css">
    <link rel="stylesheet" type="text/css" href="assets/icon/icofont/css/icofont.css">

    <link rel="stylesheet" type="text/css" href="files/pnotify/css/pnotify.css">
    <link rel="stylesheet" type="text/css" href="files/pnotify/css/pnotify.brighttheme.css">
    <link rel="stylesheet" type="text/css" href="files/pnotify/css/pnotify.buttons.css">
    <link rel="stylesheet" type="text/css" href="files/pnotify/css/pnotify.history.css">
    <link rel="stylesheet" type="text/css" href="files/pnotify/css/pnotify.mobile.css">
    <link rel="stylesheet" type="text/css" href="assets/pages/pnotify/notify.css">
</head>

<body>
    <?php include 'include/header.php'; ?>
                    <div class="pcoded-content">
                        <div class="page-header">
                            <div class="page-block">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <div class="page-header-title">
                                            <h4 class="m-b-10"><?php echo $sys_name; ?></h4>
                                        </div>
                                        <ul class="breadcrumb">
                                            <li class="breadcrumb-item">
                                                <a href="index.php">
                                                    <i class="feather icon-home"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pcoded-inner-content">
                            <div class="main-body">
                                <div class="page-wrapper">
                                    <div class="page-body">
                                        <div class="row">

                                            <div class="col-xl-3 col-md-6">
                                                <div class="card">
                                                    <div class="card-block">
                                                        <div class="row align-items-center m-l-0">
                                                            <div class="col-auto">
                                                                <i class="icofont icofont-wave f-30 text-c-purple"></i>
                                                            </div>
                                                            <div class="col-auto">
                                                                <h6 class="text-muted m-b-10"><?=gettext('Luftfuktighet')?></h6>
                                                                <h2 class="m-b-0"><?php echo $lfukt; ?></h2>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="card">
                                                    <div class="card-block">
                                                        <div class="row align-items-center m-l-0">
                                                            <div class="col-auto">
                                                                <i class="icofont icofont-celsius f-30 text-c-green"></i>
                                                            </div>
                                                            <div class="col-auto">
                                                                <h6 class="text-muted m-b-10"><?=gettext('Luft temperatur')?></h6>
                                                                <h2 class="m-b-0"><?php echo $ltemp; ?></h2>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="card">
                                                    <div class="card-block">
                                                        <div class="row align-items-center m-l-0">
                                                            <div class="col-auto">
                                                                <i class="icofont icofont-sun-alt f-30 text-c-red"></i>
                                                            </div>
                                                            <div class="col-auto">
                                                                <h6 class="text-muted m-b-10"><?=gettext('Värmeindex')?></h6>
                                                                <h2 class="m-b-0"><?php echo $lheat; ?></h2>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="card">
                                                    <div class="card-block">
                                                        <div class="row align-items-center m-l-0">
                                                            <div class="col-auto">
                                                                <i class="icofont icofont-ui-calendar f-30 text-c-blue"></i>
                                                            </div>
                                                            <div class="col-auto">
                                                                <h6 class="text-muted m-b-10"><?=gettext('Datum')?></h6>
                                                                <h2 class="m-b-0"><?php echo $datum; ?></h2>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-8 col-md-12">
                                                <div class="card table-card">
                                                    <div class="card-header">
                                                        <h5><?=gettext('Sensorer')?></h5>

                                                    </div>
                                                    <div class="card-block">
                                                        <div class="table-responsive">
                                                            <table class="table table-hover">
                                                                <thead>
                                                                    <tr>

                                                                        <th><?=gettext('Namn')?></th>
                                                                        <th><?=gettext('Jordfuktighet')?></th>
                                                                        <th><?=gettext('Jordtemp')?></th>
                                                                        <th><?=gettext('Volt')?></th>
                                                                        <th><?=gettext('Avläst')?></th>
                                                                        <th><?=gettext('Meny')?></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>

                                                                  <?php
                                                                              $sql2 = "SELECT * FROM sensorer ORDER BY sensornr ASC";
                                                                              $result2 = $mysqli->query($sql2);

                                                                              if ($result2->num_rows > 0) {
                                                                                  while ($row2 = $result2->fetch_assoc()) {
                                                                                      $ensensornr = $row2["sensornr"];
                                                                                      $ensensornamn = $row2["namn"];
                                                                                      $ensensor = $row2["id"];
                                                                                      $sql3 = "SELECT * FROM jordfukt WHERE sensor = '$ensensornr' ORDER BY id DESC LIMIT 1";
                                                                                      $result3 = $mysqli->query($sql3);

                                                                                      if ($result3->num_rows > 0) {
                                                                                          while ($row3 = $result3->fetch_assoc()) {
                                                                                              $ensensorfukt = $row3["fukt"];
                                                                                              $ensensorjord = $row3["jordtemp"];
                                                                                              $ensensorvolt = $row3["volt"];
                                                                                              $ensensortid = $row3["tid"]; ?>
                                                                                                      <tr>
                                                                                                      <td><?php echo $ensensornamn; ?></td>
                                                                                                      <td><?php echo $ensensorfukt; ?></td>
                                                                                                      <td><?php echo $ensensorjord; ?></td>
                                                                                                      <td><?php echo $ensensorvolt; ?></td>
                                                                                                      <td><?php echo $ensensortid; ?></td>
                                                                                                      <td><a href="sensorstatistik.php?id=<?php echo $ensensor; ?>&zon=<?php echo $idet; ?>"><?=gettext('Statistik')?></a></td>
                                                                                                    </tr>
                                                                                      <?php
                                                                                          }
                                                                                      }
                                                                                  }
                                                                              }
                                                                           ?>
                                                                    </tr>



                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-4 col-md-12">
                                                <div class="card feed-card">
                                                    <div class="card-header">
                                                        <h5><?=gettext('Funktioner')?></h5>
                                                        </div>
                                                    <div class="card-block">
                                                      <?php    $sql2 = "SELECT * FROM pumpar";
                                                                  $result2 = $mysqli->query($sql2);

                                                                  if ($result2->num_rows > 0) {
                                                                      while ($row2 = $result2->fetch_assoc()) {
                                                                          $enpump = $row2["id"];
                                                                          $enpumpnr = $row2["pumpnr"];
                                                                          $enpumpnamn = $row2["namn"];
                                                                          $enpumpaktiverad = $row2["aktiverad"]; ?>

                                                        <div class="row m-b-30">
                                                            <div class="col-auto p-r-0">
                                                                <i class="icofont icofont-water-drop bg-c-blue feed-icon"></i>
                                                            </div>
                                                            <div class="col">
                                                                <h6 class="m-b-5"><?php echo $enpumpnr; ?> - <?php echo $enpumpnamn; ?> <span class="text-muted float-right f-13"><?php if ($enpumpaktiverad == 1) {
                                                                              ?><a href="#!" onclick="pump(<?php echo $enpump; ?>)"><span id="pump_<?php echo $enpump; ?>"><?=gettext('Inaktivera')?></span></a><?php
                                                                          } else {
                                                                              ?><a href="#!" onclick="pump(<?php echo $enpump; ?>)"><span id="pump_<?php echo $enpump; ?>"><?=gettext('Aktivera')?></span></a><?php
                                                                          } ?></span></h6>
                                                                            </div>
                                                                        </div><?php
                                                                      }
                                                                  }
                                                               ?>
                                                        <?php    $sql2 = "SELECT * FROM vilt";
                                                                    $result2 = $mysqli->query($sql2);

                                                                    if ($result2->num_rows > 0) {
                                                                        while ($row2 = $result2->fetch_assoc()) {
                                                                            $envilt = $row2["id"];
                                                                            $enviltnr = $row2["viltnr"];
                                                                            $enviltnamn = $row2["namn"];
                                                                            $enviltaktiverad = $row2["aktiverad"];
                                                                            $enviltmanuell= $row2["manuellt"];
                                                                            $enviltvolt= $row2["volt"];
                                                                            $enviltsiren= $row2["viltsiren"];
                                                                            if ($enviltsiren != 0) {
                                                                                ?>

                                                        <div class="row m-b-30">
                                                            <div class="col-auto p-r-0">
                                                                <i class="icofont icofont-animal-cat-alt-4 bg-c-red feed-icon"></i>
                                                            </div>
                                                            <div class="col">
                                                                <h6 class="m-b-5"><?php echo $enviltnr; ?> - <?php echo $enviltnamn; ?> <span class="text-muted float-right f-13"><?php if ($enviltaktiverad == 1) {
                                                                                    ?><a href="#!" onclick="vilt(<?php echo $envilt; ?>)"><span id="viltstatus_<?php echo $envilt; ?>"><?=gettext('Inaktivera')?></span></a><?php
                                                                                } else {
                                                                                    ?><a href="#!" onclick="vilt(<?php echo $envilt; ?>)"><span id="viltstatus_<?php echo $envilt; ?>"><?=gettext('Aktivera')?></span></a><?php
                                                                                } ?> - <a href="#!" onclick="siren(<?php echo $envilt; ?>)"><?=gettext('Starta siren')?></a></span></h6></div>
                                                                        </div><?php
                                                                            }
                                                                        }
                                                                    }
                                                               ?>


                                                    </div>
                                                </div>

                                            </div>

                                            <?php    $sql = "SELECT * FROM luftfuktdag ORDER BY id DESC";
                                                $result = $mysqli->query($sql);

                                                if ($result->num_rows > 0) {
                                                    $luftfuktdagexisterar = 1;
                                                } else {
                                                    $luftfuktdagexisterar = 0;
                                                }
                                                if ($luftfuktdagexisterar == 1) {
                                                    ?>

                                            <div class="col-xl-12 col-md-12">
                                                <div class="card table-card">
                                                    <div class="card-header">
                                                        <h5><?=gettext('Luft statistik dagar')?></h5>

                                                    </div>
                                                    <div class="card-block">
                                                        <div class="table-responsive">
                                                            <table class="table table-hover">
                                                                <thead>
                                                                    <tr>

                                                                        <th><?=gettext('Temp')?></th>
                                                                        <th><?=gettext('Fukt')?></th>
                                                                        <th><?=gettext('Värmeindex')?></th>
                                                                        <th><?=gettext('Datum')?></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>

                                                                  <?php    $sql = "SELECT * FROM luftfuktdag ORDER BY id DESC LIMIT 5";
                                                    $result = $mysqli->query($sql);

                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) {
                                                            $lfdtemp = $row["temp"];
                                                            $lfdfukt = $row["fukt"];
                                                            $lfdheat = $row["heat"];
                                                            $lfddatum = $row["datum"]; ?>

                                                            <tr>
                                                            <td><?php echo $lfdtemp; ?></td>
                                                            <td><?php echo $lfdfukt; ?></td>
                                                            <td><?php echo $lfdheat; ?></td>
                                                            <td><?php echo $lfddatum; ?></td>
                                                          </tr>


                                                                                      <?php
                                                        }
                                                    } ?>
                                                                    </tr>



                                                                </tbody>
                                                            </table>
                                                            <div class="text-right m-r-20">
                                                                <a href="luftstatistik.php" class=" b-b-primary text-primary"><?=gettext('Detaljerad statistik')?></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                          <?php
                                                } ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Warning Section Starts -->
    <!-- Older IE warning message -->
    <!--[if lt IE 10]>
    <div class="ie-warning">
        <h1>Warning!!</h1>
        <p>You are using an outdated version of Internet Explorer, please upgrade
            <br/>to any of the following web browsers to access this website.
        </p>
        <div class="iew-container">
            <ul class="iew-download">
                <li>
                    <a href="http://www.google.com/chrome/">
                        <img src="assets/images/browser/chrome.png" alt="Chrome">
                        <div>Chrome</div>
                    </a>
                </li>
                <li>
                    <a href="https://www.mozilla.org/en-US/firefox/new/">
                        <img src="assets/images/browser/firefox.png" alt="Firefox">
                        <div>Firefox</div>
                    </a>
                </li>
                <li>
                    <a href="http://www.opera.com">
                        <img src="assets/images/browser/opera.png" alt="Opera">
                        <div>Opera</div>
                    </a>
                </li>
                <li>
                    <a href="https://www.apple.com/safari/">
                        <img src="assets/images/browser/safari.png" alt="Safari">
                        <div>Safari</div>
                    </a>
                </li>
                <li>
                    <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                        <img src="assets/images/browser/ie.png" alt="">
                        <div>IE (9 & above)</div>
                    </a>
                </li>
            </ul>
        </div>
        <p>Sorry for the inconvenience!</p>
    </div>
<![endif]-->
    <!-- Warning Section Ends -->
    <script type="text/javascript" src="files/jquery/js/jquery.min.js"></script>
    <script type="text/javascript" src="files/jquery-ui/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="files/popper.js/js/popper.min.js"></script>
    <script type="text/javascript" src="files/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/pages/waves/js/waves.min.js"></script>
    <script type="text/javascript" src="files/jquery-slimscroll/js/jquery.slimscroll.js"></script>
    <script src="assets/pages/chart/float/jquery.flot.js"></script>
    <script src="assets/pages/chart/float/jquery.flot.categories.js"></script>
    <script src="assets/pages/chart/float/curvedLines.js"></script>
    <script src="assets/pages/chart/float/jquery.flot.tooltip.min.js"></script>
    <script src="assets/pages/widget/amchart/amcharts.js"></script>
    <script src="assets/pages/widget/amchart/gauge.js"></script>
    <script src="assets/pages/widget/amchart/serial.js"></script>
    <script src="assets/pages/widget/amchart/light.js"></script>

    <script type="text/javascript" src="files/pnotify/js/pnotify.js"></script>
    <script type="text/javascript" src="files/pnotify/js/pnotify.desktop.js"></script>
    <script type="text/javascript" src="files/pnotify/js/pnotify.buttons.js"></script>
    <script type="text/javascript" src="files/pnotify/js/pnotify.confirm.js"></script>
    <script type="text/javascript" src="files/pnotify/js/pnotify.callbacks.js"></script>
    <script type="text/javascript" src="files/pnotify/js/pnotify.animate.js"></script>
    <script type="text/javascript" src="files/pnotify/js/pnotify.history.js"></script>
    <script type="text/javascript" src="files/pnotify/js/pnotify.mobile.js"></script>
    <script type="text/javascript" src="files/pnotify/js/pnotify.nonblock.js"></script>
    <script type="text/javascript" src="assets/pages/pnotify/notify.js"></script>

    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/vertical/vertical-layout.min.js"></script>
    <script type="text/javascript" src="assets/pages/dashboard/project-dashboard.min.js"></script>
    <script type="text/javascript" src="assets/pages/dashboard/zon.js.php"></script>
    <script type="text/javascript" src="assets/js/script.js.php"></script>
    <script type="text/javascript" src="ajax/funktioner.js.php"></script>
</body>

</html>
