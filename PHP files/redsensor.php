<?php
include 'i18n_setup.php';
include 'db.php';
include("check.php");
$idet = $_GET["id"];
$currentPage = 'redsensor';
$sql = "SELECT * FROM sensorer";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    $sensorfinns = 1;
} else {
    $sensorfinns = 0;
}
$sql = "SELECT * FROM sensorer WHERE id = '$idet'";
$result = $mysqli->query($sql);

if ($result->num_rows == 1) {
    while ($row = $result->fetch_assoc()) {
        $osensid = $row["id"];
        $osensnamn = $row["namn"];
        $osenspump = $row["pump"];
        $osensfukt = $row["fuktighet"];
        $osensauto = $row["autostart"];
    }
}
$sql = "SELECT * FROM uppgifter WHERE id = '1'";
$result = $mysqli->query($sql);

if ($result->num_rows == 1) {
    while ($row = $result->fetch_assoc()) {
        $sys_name = $row["namn"];
    }
}
 ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo $sys_name; ?> - <?=gettext('Redigera sensor')?> </title>
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
      <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
      <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
      <link rel="stylesheet" type="text/css" href="files/bootstrap/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/pages/waves/css/waves.min.css" type="text/css" media="all">
      <link rel="stylesheet" type="text/css" href="assets/icon/feather/css/feather.css">
      <link rel="stylesheet" type="text/css" href="assets/icon/themify-icons/themify-icons.css">
      <link rel="stylesheet" type="text/css" href="assets/icon/icofont/css/icofont.css">
      <link rel="stylesheet" type="text/css" href="assets/icon/font-awesome/css/font-awesome.min.css">
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
                                          <h4 class="m-b-10"><?=gettext('Redigera sensor')?></h4>
                                      </div>
                                      <ul class="breadcrumb">
                                          <li class="breadcrumb-item">
                                              <a href="index.html">
                                                  <i class="feather icon-home"></i>
                                              </a>
                                          </li>
                                          <li class="breadcrumb-item"><a href="#!"><?=gettext('Sensorer')?></a>
                                          </li>
                                          <li class="breadcrumb-item">
                                              <a href="#!"><?=gettext('Redigera sensor')?></a>
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
                                            <div class="col-sm-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5><?=gettext('Redigera befintlig sensor')?></h5>
                                                        <span><?=gettext('Här kan du ställa in uppgifter för denna sensor. Fuktighet är den procent jorden ska vara för att anses som "blöt". Om det är under det, så kommer pumpen att automatiskt att starta om autostart är aktiverad.')?></span>
                                                    </div>
                                                    <div class="card-block">
                                                        <form id="main" method="post" action="functions/redsensor.php" novalidate>
                                                            <div class="form-group row">
                                                                <label class="col-sm-2 col-form-label"><?=gettext('Bevattnings pump')?></label>
                                                                <div class="col-sm-10">
                                                                  <select name="pump" class="form-control" id="pump">
                                                                    <?php
                                                                    $sql = "SELECT * FROM pumpar";
   $result = $mysqli->query($sql);

   if ($result->num_rows > 0) {
       while ($row = $result->fetch_assoc()) {
           $pumpid = $row["id"];
           $pumpnr = $row["pumpnr"];
           $pumpnamn = $row["namn"];
           if ($osenspump == $pumpid) {
               ?>
             <option value="<?php echo $pumpid; ?>" selected><?php echo $pumpnr; ?> - <?php echo $pumpnamn; ?></option>

        <?php
           } else {
               ?>

                                                                          <option value="<?php echo $pumpid; ?>"><?php echo $pumpnr; ?> - <?php echo $pumpnamn; ?></option>
                                                                          <?php
           }
       }
   } ?>
                                                                  </select>
                                                                    <span class="messages"></span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-2 col-form-label"><?=gettext('Sensorns Namn')?></label>
                                                                <div class="col-sm-10">
                                                                    <input type="text" class="form-control" name="namn" id="namn" value="<?php echo $osensnamn; ?>" placeholder="<?=gettext('Ett lämpligt namn')?>">
                                                                    <span class="messages"></span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-2 col-form-label"><?=gettext('Fuktighet')?></label>
                                                                <div class="col-sm-10">
                                                                    <input type="text" class="form-control" name="fukt" id="fukt" value="<?php echo $osensfukt; ?>" placeholder="<?=gettext('Anges i procent')?>">
                                                                    <span class="messages"></span>
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <label class="col-sm-2"><?=gettext('Autostart av pump')?></label>
                                                                <div class="col-sm-10">
                                                                    <div class="checkbox-fade fade-in-primary">
                                                                        <label>
                                                                          <?php if ($osensauto == 1) {
       ?>
                                                                            <input type="checkbox" id="auto" name="auto" value="1" checked>
                                                                        <?php
   } else {
       ?>
                                                                            <input type="checkbox" id="auto" name="auto" value="1">
                                                                        <?php
   } ?>
                                                                            <span class="cr">
                                                                               <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                                           </span>
                                                                           <span><?=gettext('Aktivera autostart')?></span>
                                                                       </label>
                                                                   </div>
                                                                <span class="messages"></span>
                                                            </div>
                                                        </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-2"></label>
                                                                <div class="col-sm-10">
                                                                  <input type="hidden" name="idnr" value="<?php echo $osensid; ?>">
                                                                    <button type="submit" class="btn btn-primary m-b-0"><?=gettext('Skicka')?></button>
                                                                </div>
                                                            </div>
                                                        </form>
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
<!-- Required Jquery -->
<script type="text/javascript" src="files/jquery/js/jquery.min.js"></script>
<script type="text/javascript" src="files/jquery-ui/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="files/popper.js/js/popper.min.js"></script>
<script type="text/javascript" src="files/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/pages/waves/js/waves.min.js"></script>
<script type="text/javascript" src="files/jquery-slimscroll/js/jquery.slimscroll.js"></script>
<script type="text/javascript" src="files/modernizr/js/modernizr.js"></script>
<script type="text/javascript" src="files/modernizr/js/css-scrollbars.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
<script type="text/javascript" src="assets/pages/form-validation/validate.js"></script>
<script type="text/javascript" src="assets/pages/form-validation/redsensor.js.php"></script>
<script src="assets/js/pcoded.min.js"></script>
<script src="assets/js/vertical/vertical-layout.min.js"></script>
<script src="assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script type="text/javascript" src="assets/js/script.js.php"></script>
</body>

</html>
