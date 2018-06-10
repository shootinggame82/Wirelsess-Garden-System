<?php
include 'i18n_setup.php';
include 'db.php';
include("check.php");
$idet = $_GET["id"];
$existerar = 0;
$sensorfinns = 0;

$sql = "SELECT * FROM zoner WHERE id = '$idet'";
$result = $mysqli->query($sql);

if ($result->num_rows == 1) {
    $existerar = 1;
} else {
    $existerar = 0;
}

$sql = "SELECT * FROM pumpar";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    $sensorfinns = 1;
} else {
    $sensorfinns = 0;
}

$sql = "SELECT * FROM zoner WHERE id = '$idet'";
$result = $mysqli->query($sql);

if ($result->num_rows == 1) {
    while ($row = $result->fetch_assoc()) {
        $azonid = $row["id"];
        $azonnamn = $row["namn"];
    }
}

$sql = "SELECT * FROM uppgifter WHERE id = '1'";
$result = $mysqli->query($sql);

if ($result->num_rows == 1) {
    while ($row = $result->fetch_assoc()) {
        $sys_name = $row["namn"];
    }
}

$currentPage = 'zonpumpar_'.$idet;
 ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo $sys_name; ?> - <?=gettext('Zon pumpar')?></title>
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
      <link rel="stylesheet" href="assets/pages/waves/css/waves.min.css" type="text/css" media="all">
      <link rel="stylesheet" type="text/css" href="assets/icon/feather/css/feather.css">
      <link rel="stylesheet" type="text/css" href="files/bootstrap/css/bootstrap.min.css">
      <link rel="stylesheet" type="text/css" href="assets/icon/themify-icons/themify-icons.css">
      <link rel="stylesheet" type="text/css" href="assets/icon/font-awesome/css/font-awesome.min.css">
      <link rel="stylesheet" type="text/css" href="assets/icon/icofont/css/icofont.css">
      <link rel="stylesheet" type="text/css" href="assets/css/style.css">
      <link rel="stylesheet" type="text/css" href="assets/css/pages.css">

  </head>
  <body>
  <?php include 'include/header.php'; ?>
                  <div class="pcoded-content">
                      <div class="page-header">
                          <div class="page-block">
                              <div class="row align-items-center">
                                  <div class="col-md-8">
                                      <div class="page-header-title">
                                          <h4 class="m-b-10"><?=gettext('Zon pumpar')?></h4>
                                      </div>
                                      <ul class="breadcrumb">
                                          <li class="breadcrumb-item">
                                              <a href="index.html">
                                                  <i class="feather icon-home"></i>
                                              </a>
                                          </li>
                                          <li class="breadcrumb-item"><a href="#!"><?=gettext('Zoner')?></a>
                                          </li>
                                          <li class="breadcrumb-item">
                                              <a href="#!"><?=gettext('Pumpar')?></a>
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
                                        <div class="card">
                                            <div class="card-header">
                                                <h5><?php echo $azonnamn; ?> <?=gettext('bevattnings pumpar')?></h5>
                                                <span><?=gettext('Här ser du alla bevattnings pumpar som är kopplad till denna zon. Genom att klicka på radera så tar du bort pumpen från zonen (ej från systemet)')?></span>
                                            </div>
                                            <div class="card-block table-border-style">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th><?=gettext('Nr')?></th>
                                                                <th><?=gettext('Namn')?></th>
                                                                <th><?=gettext('Radera')?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                          <?php    $sql = "SELECT * FROM zonpump WHERE zon = '$idet' ORDER BY id ASC";
                                                              $result = $mysqli->query($sql);

                                                              if ($result->num_rows > 0) {
                                                                  while ($row = $result->fetch_assoc()) {
                                                                      $enpump = $row["pump"];
                                                                      $enpumpidet = $row["id"];

                                                                      $sql2 = "SELECT * FROM pumpar WHERE id = '$enpump'";
                                                                      $result2 = $mysqli->query($sql2);

                                                                      if ($result2->num_rows == 1) {
                                                                          while ($row2 = $result2->fetch_assoc()) {
                                                                              $enpumpnr = $row2["pumpnr"];
                                                                              $enpumpnamn = $row2["namn"]; ?>
                                                            <tr>
                                                                <th scope="row"><?php echo $enpumpnr; ?></th>
                                                                <td><?php echo $enpumpnamn; ?></td>
                                                                <td><a href="#!" onclick="radera(<?php echo $enpumpidet; ?>, <?php echo $idet; ?>)"><?=gettext('Radera')?></A></td>
                                                            </tr>
                                                            <?php
                                                                          }
                                                                      }
                                                                  }
                                                              } ?>
                                                        </tbody>
                                                    </table>
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
        </div>
    </div>

    <!-- Warning Section Starts -->
    <!-- Older IE warning message -->
    <!--[if lt IE 10]>
    <div class="ie-warning">
        <h1>Warning!!</h1>
        <p>You are using an outdated version of Internet Explorer, please upgrade <br/>to any of the following web browsers to access this website.</p>
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
    <script src="assets/pages/waves/js/waves.min.js"></script>
    <script type="text/javascript" src="files/modernizr/js/modernizr.js"></script>
    <script type="text/javascript" src="files/modernizr/js/css-scrollbars.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/vertical/vertical-layout.min.js"></script>
    <script src="assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script type="text/javascript" src="assets/js/script.js.php"></script>
    <script type="text/javascript" src="assets/js/zonpump.js.php"></script>
</body>

</html>
