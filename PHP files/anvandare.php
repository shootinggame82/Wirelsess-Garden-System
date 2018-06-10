<?php
include 'i18n_setup.php';
include 'db.php';
include("check.php");
$currentPage = 'anvandare';
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
    <title><?php echo $sys_name; ?> - <?=gettext('Användare')?> </title>
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
                                          <h4 class="m-b-10"><?=gettext('Användare')?></h4>
                                      </div>
                                      <ul class="breadcrumb">
                                          <li class="breadcrumb-item">
                                              <a href="index.html">
                                                  <i class="feather icon-home"></i>
                                              </a>
                                          </li>
                                          <li class="breadcrumb-item"><a href="#!"><?=gettext('Övrigt')?></a>
                                          </li>
                                          <li class="breadcrumb-item">
                                              <a href="#!"><?=gettext('Användare')?></a>
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
                                                      <h5><?=gettext('Användare')?></h5>
                                                      <span><?=gettext('Här ser du alla användare som är tillagd i systemet. Dessa har rätt att logga in och sköta odligen. Nedan för kan du lägga till flera.')?></span>
                                                  </div>
                                                  <div class="card-block table-border-style">
                                                      <div class="table-responsive">
                                                          <table class="table">
                                                              <thead>
                                                                  <tr>
                                                                      <th><?=gettext('Användare')?></th>
                                                                      <th><?=gettext('Radera')?></th>
                                                                  </tr>
                                                              </thead>
                                                              <tbody>
                                                                <?php    $sql = "SELECT * FROM users";
                                                                    $result = $mysqli->query($sql);

                                                                    if ($result->num_rows > 0) {
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            $namn = $row["username"];
                                                                            $anvid = $row["uid"]; ?>
                                                                  <tr>
                                                                      <td><?php echo $namn; ?></td>
                                                                      <td><a href="#!" onclick="radera(<?php echo $anvid; ?>)"><?=gettext('Radera')?></A></td>
                                                                  </tr>
                                                                  <?php
                                                                        }
                                                                    } ?>
                                                              </tbody>
                                                          </table>
                                                      </div>
                                                  </div>
                                              </div>
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5><?=gettext('Användare')?></h5>
                                                        <span><?=gettext('Här kan du lägga till nya användare till systemet. Ange bara användarnamn och lösenord små bokstäver utan åäö och inga konstiga tecken på både användarnamn och lösenord, INGA mellanslag')?></span>
                                                    </div>
                                                    <div class="card-block">
                                                        <form id="main" method="post" action="functions/adderaanvandare.php" novalidate>
                                                            <div class="form-group row">
                                                                <label class="col-sm-2 col-form-label"><?=gettext('Användarnamn')?></label>
                                                                <div class="col-sm-10">
                                                                    <input type="text" class="form-control" name="namn" id="namn" placeholder="<?=gettext('Ett lämpligt användarnamn')?>">
                                                                    <span class="messages"></span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-2 col-form-label"><?=gettext('Lösenord')?></label>
                                                                <div class="col-sm-10">
                                                                    <input type="password" class="form-control" name="losen" id="losen" placeholder="<?=gettext('Ett lämpligt lösenord')?>">
                                                                    <span class="messages"></span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-2"></label>
                                                                <div class="col-sm-10">
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
<script type="text/javascript" src="assets/pages/form-validation/anvandare.js.php"></script>
<script src="assets/js/pcoded.min.js"></script>
<script src="assets/js/vertical/vertical-layout.min.js"></script>
<script src="assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script type="text/javascript" src="assets/js/script.js.php"></script>
<script type="text/javascript" src="assets/js/anvandare.js.php"></script>
</body>

</html>
