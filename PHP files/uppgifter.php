<?php
include 'i18n_setup.php';
include 'db.php';
include("check.php");
$currentPage = 'uppgifter';
$sql = "SELECT * FROM uppgifter WHERE id = '1'";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    $sensorfinns = 1;
} else {
    $sensorfinns = 0;
}
$sql = "SELECT * FROM uppgifter WHERE id = '1'";
$result = $mysqli->query($sql);

if ($result->num_rows == 1) {
    while ($row = $result->fetch_assoc()) {
        $torrjord = $row["torrjord"];
        $blotjord= $row["blotjord"];
        $sysnamn= $row["namn"];
        $pumptid= $row["pumptid"];
        $fukttid= $row["fukttid"];
        $hogvarme= $row["hogvarme"];
        $hogtemp= $row["hogtemp"];
        $sirentid= $row["sirentid"];
        $torregn= $row["torrregn"];
        $blotregn= $row["blotregn"];
        $nattvatt= $row["nattvatt"];
        $starten= $row["starten"];
        $stoppen= $row["stoppen"];
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
    <title><?php echo $sys_name; ?> - <?=gettext('Uppgifter')?> </title>
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
                                          <h4 class="m-b-10"><?=gettext('Uppgifter')?></h4>
                                      </div>
                                      <ul class="breadcrumb">
                                          <li class="breadcrumb-item">
                                              <a href="index.html">
                                                  <i class="feather icon-home"></i>
                                              </a>
                                          </li>
                                          </li>
                                          <li class="breadcrumb-item">
                                              <a href="#!"><?=gettext('Uppgifter')?></a>
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
                                                        <h5><?=gettext('Redigera systemets uppgifter')?><h5>
                                                        <span><?=gettext('Här kan du ställa in uppgifter för systemet. Notera att det kan ta upp till 1 minut innan systemet har ändrat uppgifterna.')?></span>
                                                    </div>
                                                    <div class="card-block">
                                                        <form id="main" method="post" action="functions/uppgifter.php" novalidate>

                                                            <div class="form-group row">
                                                                <label class="col-sm-2 col-form-label"><?=gettext('Torr jord (i resistans)')?></label>
                                                                <div class="col-sm-10">
                                                                    <input type="text" class="form-control" name="torr" id="torr" value="<?php echo $torrjord; ?>" placeholder="<?=gettext('I resistans utan tecken')?>">
                                                                    <span class="messages"></span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-2 col-form-label"><?=gettext('Blöt jord (i resistans)')?></label>
                                                                <div class="col-sm-10">
                                                                    <input type="text" class="form-control" name="blot" id="blot" value="<?php echo $blotjord; ?>" placeholder="<?=gettext('I resistans utan tecken')?>">
                                                                    <span class="messages"></span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-2 col-form-label"><?=gettext('Torr regn (i resistans)')?></label>
                                                                <div class="col-sm-10">
                                                                    <input type="text" class="form-control" name="torr1" id="torr1" value="<?php echo $torregn; ?>" placeholder="<?=gettext('I resistans utan tecken')?>">
                                                                    <span class="messages"></span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-2 col-form-label"><?=gettext('Blöt regn (i resistans)')?></label>
                                                                <div class="col-sm-10">
                                                                    <input type="text" class="form-control" name="blot1" id="blot1" value="<?php echo $blotregn; ?>" placeholder="<?=gettext('I resistans utan tecken')?>">
                                                                    <span class="messages"></span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-2 col-form-label"><?=gettext('Namn på odlings systemet')?></label>
                                                                <div class="col-sm-10">
                                                                    <input type="text" class="form-control" name="namn" id="namn" value="<?php echo $sysnamn; ?>" placeholder="<?=gettext('Ett lämpligt namn')?>">
                                                                    <span class="messages"></span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-2 col-form-label"><?=gettext('Max tid att köra pumpar')?></label>
                                                                <div class="col-sm-10">
                                                                    <input type="text" class="form-control" name="pumptid" id="pumptid" value="<?php echo $pumptid; ?>" placeholder="<?=gettext('I minuter hur länge en pump får köras')?>">
                                                                    <span class="messages"></span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-2 col-form-label"><?=gettext('Tidigast att auto starta pump från föregående körning')?></label>
                                                                <div class="col-sm-10">
                                                                    <input type="text" class="form-control" name="autotid" id="autotid" value="<?php echo $fukttid; ?>" placeholder="<?=gettext('I minuter hur när en pump får köras')?>">
                                                                    <span class="messages"></span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-2"><?=gettext('Stöd vattna vid värme')?></label>
                                                                <div class="col-sm-10">
                                                                    <div class="checkbox-fade fade-in-primary">
                                                                        <label>
                                                                          <?php if ($hogvarme == 1) {
     ?>
                                                                            <input type="checkbox" id="stod" name="stod" value="1" checked>
                                                                      <?php
 } else {
     ?>
                                                                            <input type="checkbox" id="stod" name="stod" value="1">
                                                                          <?php
 } ?>
                                                                            <span class="cr">
                                                                               <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                                           </span>
                                                                           <span><?=gettext('Vattna extra vid hög temperatur')?></span>
                                                                       </label>
                                                                   </div>
                                                                <span class="messages"></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-2 col-form-label"><?=gettext('Lägsta hög temp att stöd bevattna')?></label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control" name="hogtemp" id="hogtemp" value="<?php echo $hogtemp; ?>" placeholder="<?=gettext('Grader som krävs för stöd bevattning.')?>">
                                                                <span class="messages"></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-2 col-form-label"><?=gettext('Tid i millisekunder som sirenen vid viltlarm tjuter')?></label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control" name="sirentid" id="sirentid" value="<?php echo $sirentid; ?>" placeholder="<?=gettext('Ange i millisekunder 1 sek = 1000 milli')?>">
                                                                <span class="messages"></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-2"><?=gettext('Ingen natt bevattning')?></label>
                                                            <div class="col-sm-10">
                                                                <div class="checkbox-fade fade-in-primary">
                                                                    <label>
                                                                      <?php if ($nattvatt == 1) {
     ?>
                                                                        <input type="checkbox" id="nattvatt" name="nattvatt" value="1" checked>
                                                                  <?php
 } else {
     ?>
                                                                        <input type="checkbox" id="nattvatt" name="nattvatt" value="1">
                                                                      <?php
 } ?>
                                                                        <span class="cr">
                                                                           <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                                       </span>
                                                                       <span><?=gettext('Ingen automatisk bevattning mellan tiderna angivet nedan')?></span>
                                                                   </label>
                                                               </div>
                                                            <span class="messages"></span>
                                                        </div>
                                                    </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-2 col-form-label"><?=gettext('Start tid')?></label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control hour" name="start" id="start" value="<?php echo $starten; ?>" data-mask="99:99:99">
                                                                <span class="messages"></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-2 col-form-label"><?=gettext('Slut tid')?></label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control hour" name="slut" id="slut" value="<?php echo $stoppen; ?>" data-mask="99:99:99">
                                                                <span class="messages"></span>
                                                            </div>
                                                        </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-2"></label>
                                                                <div class="col-sm-10">
                                                                  <input type="hidden" name="idnr" value="1">
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
<script src="assets/pages/form-masking/inputmask.js"></script>
<script src="assets/pages/form-masking/jquery.inputmask.js"></script>
<script src="assets/pages/form-masking/autoNumeric.js"></script>
<script src="assets/pages/form-masking/form-mask.js"></script>
<script type="text/javascript" src="assets/pages/form-validation/uppgifter.js.php"></script>
<script src="assets/js/pcoded.min.js"></script>
<script src="assets/js/vertical/vertical-layout.min.js"></script>
<script src="assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script type="text/javascript" src="assets/js/script.js.php"></script>
</body>

</html>
