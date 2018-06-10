<?php
include 'i18n_setup.php';
include 'db.php';
include("check.php");
$currentPage = 'timervilt';
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
    <title><?php echo $sys_name; ?> - <?=gettext('Vilt timer')?> </title>
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
                                          <h4 class="m-b-10"><?=gettext('Vilt timer')?></h4>
                                      </div>
                                      <ul class="breadcrumb">
                                          <li class="breadcrumb-item">
                                              <a href="index.html">
                                                  <i class="feather icon-home"></i>
                                              </a>
                                          </li>
                                          <li class="breadcrumb-item"><a href="#!"><?=gettext('Vilt')?></a>
                                          </li>
                                          <li class="breadcrumb-item">
                                              <a href="#!"><?=gettext('Vilt timer')?></a>
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
                                                      <h5><?=gettext('Vilt timers')?></h5>
                                                      <span><?=gettext('Här ser du alla vilt timers i systemet. Dessa tider kommer dom automatiskt att aktiveras och stängas av.')?></span>
                                                  </div>
                                                  <div class="card-block table-border-style">
                                                      <div class="table-responsive">
                                                          <table class="table">
                                                              <thead>
                                                                  <tr>
                                                                      <th><?=gettext('Namn')?></th>
                                                                      <th><?=gettext('Aktiv')?></th>
                                                                      <th><?=gettext('Dagar')?></th>
                                                                      <th><?=gettext('Vilt')?></th>
                                                                      <th><?=gettext('Radera')?></th>
                                                                  </tr>
                                                              </thead>
                                                              <tbody>
                                                                <?php    $sql = "SELECT * FROM vilttimer";
                                                                    $result = $mysqli->query($sql);

                                                                    if ($result->num_rows > 0) {
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            $namn = $row["namn"];
                                                                            $start = $row["start"];
                                                                            $slut = $row["slut"];
                                                                            $mon = $row["mon"];
                                                                            $tis = $row["tis"];
                                                                            $ons = $row["ons"];
                                                                            $tor = $row["tor"];
                                                                            $fre = $row["fre"];
                                                                            $lor = $row["lor"];
                                                                            $son = $row["son"];
                                                                            $pump = $row["vilt"];
                                                                            $timerid = $row["id"];

                                                                            $sql2 = "SELECT * FROM vilt WHERE id = '$pump'";
                                                                            $result2 = $mysqli->query($sql2);

                                                                            if ($result2->num_rows == 1) {
                                                                                while ($row2 = $result2->fetch_assoc()) {
                                                                                    $enpumpnr = $row2["viltnr"];
                                                                                    $enpumpnamn = $row2["namn"]; ?>
                                                                  <tr>
                                                                      <td><?php echo $namn; ?></td>
                                                                      <td><?=gettext('Start')?>: <?php echo $start; ?> <?=gettext('Stopp')?>:  <?php echo $slut; ?></td>
                                                                      <?php if ($mon == 1 && $tis == 1 && $ons == 1 && $tor == 1 && $fre == 1 && $lor == 0 && $son == 0) {
                                                                                        ?>
                                                                        <td><?=gettext('Måndag - Fredag')?></td>
                                                                    <?php
                                                                                    } elseif ($mon == 1 && $tis == 1 && $ons == 1 && $tor == 1 && $fre == 1 && $lor == 1 && $son == 1) {
                                                                                        ?>
                                                                      <td><?=gettext('Hela veckan')?></td>
                                                                    <?php
                                                                                    } elseif ($mon == 0 && $tis == 0 && $ons == 0 && $tor == 0 && $fre == 0 && $lor == 1 && $son == 1) {
                                                                                        ?>
                                                                      <td><?=gettext('Lördag & Söndag')?></td>
                                                                    <?php
                                                                                    } else {
                                                                                        ?>
                                                                      <td><?php if ($mon == 1) {
                                                                                            echo gettext('Mån ');
                                                                                        } else {
                                                                                            echo "";
                                                                                        }
                                                                                        if ($tis == 1) {
                                                                                            echo gettext('Tis ');
                                                                                        } else {
                                                                                            echo "";
                                                                                        }
                                                                                        if ($ons == 1) {
                                                                                            echo gettext('Ons ');
                                                                                        } else {
                                                                                            echo "";
                                                                                        }
                                                                                        if ($tor == 1) {
                                                                                            echo gettext('Tor ');
                                                                                        } else {
                                                                                            echo "";
                                                                                        }
                                                                                        if ($fre == 1) {
                                                                                            echo gettext('Fre ');
                                                                                        } else {
                                                                                            echo "";
                                                                                        }
                                                                                        if ($lor == 1) {
                                                                                            echo gettext('Lör ');
                                                                                        } else {
                                                                                            echo "";
                                                                                        }
                                                                                        if ($son == 1) {
                                                                                            echo gettext('Sön ');
                                                                                        } else {
                                                                                            echo "";
                                                                                        } ?></td>
                                                                  <?php
                                                                                    } ?>
                                                                      <td><?php echo $enpumpnr; ?> - <?php echo $enpumpnamn; ?></td>
                                                                      <td><a href="#!" onclick="redigera(<?php echo $timerid; ?>)"><?=gettext('Redigera')?></A> - <a href="#!" onclick="radera(<?php echo $timerid; ?>)"><?=gettext('Radera')?></A></td>
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
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5><?=gettext('Pump timers')?></h5>
                                                        <span><?=gettext('Här kan du lägga till och redigera befintliga timers. Timers gör att du kan automatiskt starta vilt sensorer önskade tider. Du kan ha flera tider på samma vilt sensor.')?></span>
                                                    </div>
                                                    <div class="card-block">
                                                        <form id="main" method="post" action="functions/adderatimervilt.php" novalidate>
                                                            <div class="form-group row">
                                                                <label class="col-sm-2 col-form-label"><?=gettext('Vilt sensorns ID Nummer')?></label>
                                                                <div class="col-sm-10">
                                                                  <select name="pump" class="form-control" id="pump">
                                                                    <?php
                                                                    $sql = "SELECT * FROM vilt";
   $result = $mysqli->query($sql);

   if ($result->num_rows > 0) {
       while ($row = $result->fetch_assoc()) {
           $pumpid = $row["id"];
           $pumpnr = $row["viltnr"];
           $pumpnamn = $row["namn"]; ?>
                                                                          <option value="<?php echo $pumpid; ?>"><?php echo $pumpnr; ?> - <?php echo $pumpnamn; ?></option>
                                                                          <?php
       }
   } ?>
                                                                  </select>
                                                                    <span class="messages"></span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-2 col-form-label"><?=gettext('Timer namn')?></label>
                                                                <div class="col-sm-10">
                                                                    <input type="text" class="form-control" name="namn" id="namn" placeholder="<?=gettext('Ett lämpligt namn')?>">
                                                                    <span class="messages"></span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-2 col-form-label"><?=gettext('Start tid')?></label>
                                                                <div class="col-sm-10">
                                                                    <input type="text" class="form-control hour" name="start" id="start" data-mask="99:99:99">
                                                                    <span class="messages"></span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-2 col-form-label"><?=gettext('Slut tid')?></label>
                                                                <div class="col-sm-10">
                                                                    <input type="text" class="form-control hour" name="slut" id="slut" data-mask="99:99:99">
                                                                    <span class="messages"></span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-2"><?=gettext('Dagar')?></label>
                                                                <div class="col-sm-10">
                                                                    <div class="checkbox-fade fade-in-primary">
                                                                        <label>
                                                                            <input type="checkbox" id="mon" name="mon" value="1">
                                                                            <span class="cr">
                                                                               <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                                           </span>
                                                                           <span><?=gettext('Måndag')?></span>
                                                                       </label>
                                                                   </div>
                                                                   <div class="checkbox-fade fade-in-primary">
                                                                    <label>
                                                                        <input type="checkbox" id="tis" name="tis" value="1">
                                                                        <span class="cr">
                                                                            <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                                        </span>
                                                                        <span><?=gettext('Tisdag')?></span>
                                                                    </label>
                                                                </div>
                                                                <div class="checkbox-fade fade-in-primary">
                                                                 <label>
                                                                     <input type="checkbox" id="ons" name="ons" value="1">
                                                                     <span class="cr">
                                                                         <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                                     </span>
                                                                     <span><?=gettext('Onsdag')?></span>
                                                                 </label>
                                                             </div>
                                                             <div class="checkbox-fade fade-in-primary">
                                                              <label>
                                                                  <input type="checkbox" id="tors" name="tors" value="1">
                                                                  <span class="cr">
                                                                      <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                                  </span>
                                                                  <span><?=gettext('Torsdag')?></span>
                                                              </label>
                                                          </div>
                                                          <div class="checkbox-fade fade-in-primary">
                                                           <label>
                                                               <input type="checkbox" id="fre" name="fre" value="1">
                                                               <span class="cr">
                                                                   <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                               </span>
                                                               <span><?=gettext('Fredag')?></span>
                                                           </label>
                                                       </div>
                                                       <div class="checkbox-fade fade-in-primary">
                                                        <label>
                                                            <input type="checkbox" id="lor" name="lor" value="1">
                                                            <span class="cr">
                                                                <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                            </span>
                                                            <span><?=gettext('Lördag')?></span>
                                                        </label>
                                                    </div>
                                                    <div class="checkbox-fade fade-in-primary">
                                                     <label>
                                                         <input type="checkbox" id="son" name="son" value="1">
                                                         <span class="cr">
                                                             <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                         </span>
                                                         <span><?=gettext('Söndag')?></span>
                                                     </label>
                                                 </div>
                                                                <span class="messages"></span>
                                                            </div>
                                                        </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-2"></label>
                                                                <div class="col-sm-10">
                                                                  <input type="hidden" name="uppdatera" id="uppdatera" value="0">
                                                                    <input type="hidden" name="idnumret" id="idnumret" value="0">
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
<script type="text/javascript" src="assets/pages/form-validation/vilttimer.js.php"></script>
<script src="assets/js/pcoded.min.js"></script>
<script src="assets/js/vertical/vertical-layout.min.js"></script>
<script src="assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script type="text/javascript" src="assets/js/script.js.php"></script>
<script type="text/javascript" src="assets/js/viltimer.js.php"></script>
</body>

</html>
