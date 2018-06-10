<div class="loader-bg">
    <div class="loader-bar"></div>
</div>
<div id="pcoded" class="pcoded">
    <div class="pcoded-overlay-box"></div>
    <div class="pcoded-container navbar-wrapper">
        <nav class="navbar header-navbar pcoded-header">
            <div class="navbar-wrapper">
                <div class="navbar-logo">
                    <a class="mobile-menu waves-effect waves-light" id="mobile-collapse" href="#!">
                        <i class="feather icon-toggle-right"></i>
                    </a>
                    <a href="index.php">
                        <P><?php echo $sys_name; ?></p>
                    </a>
                    <a class="mobile-options waves-effect waves-light">
                        <i class="feather icon-more-horizontal"></i>
                    </a>
                </div>
                <div class="navbar-container container-fluid">
                    <ul class="nav-left">

                        <li>
                            <a href="#!" onclick="javascript:toggleFullScreen()" class="waves-effect waves-light">
                                <i class="full-screen feather icon-maximize"></i>
                            </a>
                        </li>
                    </ul>

                </div>
            </div>
        </nav>
        <div class="pcoded-main-container">
            <div class="pcoded-wrapper">
              <nav class="pcoded-navbar">
                    <div class="pcoded-inner-navbar main-menu">

                        <div class="pcoded-navigation-label"><?=gettext('Navigation')?></div>
                        <ul class="pcoded-item pcoded-left-item">


                            <li class="<?php if ($currentPage =='index') {
    echo 'active';
} ?>">
                                <a href="index.php" class="waves-effect waves-dark">
                <span class="pcoded-micon">
                  <i class="feather icon-home"></i>
                </span>
                                    <span class="pcoded-mtext"><?=gettext('Hem')?></span>
                                </a>
                            </li>


                        </ul>
                        <div class="pcoded-navigation-label"><?=gettext('Odlingar')?></div>
                        <ul class="pcoded-item pcoded-left-item">

                          <?php
                          $sql = "SELECT * FROM zoner";
                          $result = $mysqli->query($sql);

                          if ($result->num_rows > 0) {
                              while ($row = $result->fetch_assoc()) {
                                  $zonid = $row["id"];
                                  $zonnamn = $row["namn"];
                                  $menynamn1 = 'zon_'.$zonid;
                                  $menynamn2 = 'zonsensor_'.$zonid;
                                  $menynamn3 = 'zonvilt_'.$zonid;
                                  $menynamn4 = 'zonpump_'.$zonid;
                                  $menynamn5 = 'zonsensorer_'.$zonid;
                                  $menynamn6 = 'zonpumpar_'.$zonid;
                                  $menynamn7 = 'zonviltar_'.$zonid;
                                  $menynamn8 = 'zonloggar_'.$zonid;
                                  $menynamn9 = 'zonloggadd_'.$zonid;
                                  $menynamn10 = 'zonregn_'.$zonid;
                                  $menynamn11 = 'zonregnar_'.$zonid; ?>
                                  <li class="pcoded-hasmenu <?php if ($currentPage == $menynamn11 || $currentPage == $menynamn10 || $currentPage == $menynamn9 || $currentPage == $menynamn8 || $currentPage == $menynamn7 || $currentPage == $menynamn6 || $currentPage == $menynamn5 || $currentPage == $menynamn1 || $currentPage == $menynamn2 || $currentPage == $menynamn3 || $currentPage == $menynamn4) {
                                      echo 'active pcoded-trigger';
                                  } ?>">
                                      <a href="javascript:void(0)" class="waves-effect waves-dark">
                                          <span class="pcoded-micon"><i class="icofont icofont-flora-flower"></i></span>
                                          <span class="pcoded-mtext"><?php echo $zonnamn; ?></span>
                                      </a>
                                      <ul class="pcoded-submenu">
                                          <li class="<?php if ($currentPage == $menynamn1) {
                                      echo 'active';
                                  } ?>">
                                              <a href="zon.php?id=<?php echo $zonid; ?>" class="waves-effect waves-dark">
                                                  <span class="pcoded-mtext"><?=gettext('Startsidan')?></span>
                                              </a>
                                          </li>
                                          <li class="<?php if ($currentPage == $menynamn8) {
                                      echo 'active';
                                  } ?>">
                                              <a href="zonloggar.php?id=<?php echo $zonid; ?>" class="waves-effect waves-dark">
                                                  <span class="pcoded-mtext"><?=gettext('Odlings loggar')?></span>
                                              </a>
                                          </li>
                                          <li class="<?php if ($currentPage == $menynamn9) {
                                      echo 'active';
                                  } ?>">
                                              <a href="zonloggadd.php?id=<?php echo $zonid; ?>" class="waves-effect waves-dark">
                                                  <span class="pcoded-mtext"><?=gettext('Skapa logg')?></span>
                                              </a>
                                          </li>
                                          <li class="<?php if ($currentPage == $menynamn2) {
                                      echo 'active';
                                  } ?>">
                                              <a href="zonsensor.php?id=<?php echo $zonid; ?>" class="waves-effect waves-dark">
                                                  <span class="pcoded-mtext"><?=gettext('Lägg till sensor')?></span>
                                              </a>
                                          </li>
                                          <li class="<?php if ($currentPage == $menynamn5) {
                                      echo 'active';
                                  } ?>">
                                              <a href="zonsensorer.php?id=<?php echo $zonid; ?>" class="waves-effect waves-dark">
                                                  <span class="pcoded-mtext"><?=gettext('Redigera sensorer')?></span>
                                              </a>
                                          </li>
                                          <li class="<?php if ($currentPage == $menynamn4) {
                                      echo 'active';
                                  } ?>">
                                              <a href="zonpump.php?id=<?php echo $zonid; ?>" class="waves-effect waves-dark">
                                                  <span class="pcoded-mtext"><?=gettext('Lägg till pump')?></span>
                                              </a>
                                          </li>
                                          <li class="<?php if ($currentPage == $menynamn6) {
                                      echo 'active';
                                  } ?>">
                                              <a href="zonpumpar.php?id=<?php echo $zonid; ?>" class="waves-effect waves-dark">
                                                  <span class="pcoded-mtext"><?=gettext('Redigera pumpar')?></span>
                                              </a>
                                          </li>
                                          <li class="<?php if ($currentPage == $menynamn3) {
                                      echo 'active';
                                  } ?>">
                                              <a href="zonvilt.php?id=<?php echo $zonid; ?>" class="waves-effect waves-dark">
                                                  <span class="pcoded-mtext"><?=gettext('Lägg till vilt')?></span>
                                              </a>
                                          </li>
                                          <li class="<?php if ($currentPage == $menynamn7) {
                                      echo 'active';
                                  } ?>">
                                              <a href="zonviltar.php?id=<?php echo $zonid; ?>" class="waves-effect waves-dark">
                                                  <span class="pcoded-mtext"><?=gettext('Redigera vilt')?></span>
                                              </a>
                                          </li>
                                          <li class="<?php if ($currentPage == $menynamn10) {
                                      echo 'active';
                                  } ?>">
                                              <a href="zonregn.php?id=<?php echo $zonid; ?>" class="waves-effect waves-dark">
                                                  <span class="pcoded-mtext"><?=gettext('Lägg till regnsensor')?></span>
                                              </a>
                                          </li>
                                          <li class="<?php if ($currentPage == $menynamn11) {
                                      echo 'active';
                                  } ?>">
                                              <a href="zonregnar.php?id=<?php echo $zonid; ?>" class="waves-effect waves-dark">
                                                  <span class="pcoded-mtext"><?=gettext('Redigera regnsensorer')?></span>
                                              </a>
                                          </li>
                                      </ul>
                                  </li>

                            <?php
                              }
                          }
                            ?>

                        </ul>
                        <div class="pcoded-navigation-label"><?=gettext('Sensorer')?></div>
                        <ul class="pcoded-item pcoded-left-item">


                            <li class="<?php if ($currentPage =='adderasensor') {
                                echo 'active';
                            } ?>">
                                <a href="adderasensor.php" class="waves-effect waves-dark">
                <span class="pcoded-micon">
                  <i class="icofont icofont-compass-alt"></i>
                </span>
                                    <span class="pcoded-mtext"><?=gettext('Lägg till sensor')?></span>
                                </a>
                            </li>
                            <li class="<?php if ($currentPage =='redigerasensor' || $currentPage =='redsensor') {
                                echo 'active';
                            } ?>">
                                <a href="redigerasensor.php" class="waves-effect waves-dark">
                <span class="pcoded-micon">
                  <i class="icofont icofont-snow-temp"></i>
                </span>
                                    <span class="pcoded-mtext"><?=gettext('Redigera sensorer')?></span>
                                </a>
                            </li>


                        </ul>
                        <div class="pcoded-navigation-label"><?=gettext('Pumpar')?></div>
                        <ul class="pcoded-item pcoded-left-item">


                            <li class="<?php if ($currentPage =='adderapump') {
                                echo 'active';
                            } ?>">
                                <a href="adderapump.php" class="waves-effect waves-dark">
                <span class="pcoded-micon">
                  <i class="icofont icofont-water-drop"></i>
                </span>
                                    <span class="pcoded-mtext"><?=gettext('Lägg till pump')?></span>
                                </a>
                            </li>
                            <li class="<?php if ($currentPage =='redigerapump' || $currentPage =='redpump') {
                                echo 'active';
                            } ?>">
                                <a href="redigerapump.php" class="waves-effect waves-dark">
                <span class="pcoded-micon">
                  <i class="icofont icofont-energy-water"></i>
                </span>
                                    <span class="pcoded-mtext"><?=gettext('Redigera pump')?></span>
                                </a>
                            </li>
                            <li class="<?php if ($currentPage =='pumptimer') {
                                echo 'active';
                            } ?>">
                                <a href="timerpump.php" class="waves-effect waves-dark">
                <span class="pcoded-micon">
                  <i class="icofont icofont-ui-timer"></i>
                </span>
                                    <span class="pcoded-mtext"><?=gettext('Timer')?></span>
                                </a>
                            </li>


                        </ul>

                        <div class="pcoded-navigation-label"><?=gettext('Vilt')?></div>
                        <ul class="pcoded-item pcoded-left-item">


                          <li class="<?php if ($currentPage =='adderaviltsiren') {
                                echo 'active';
                            } ?>">
                              <a href="adderaviltsiren.php" class="waves-effect waves-dark">
              <span class="pcoded-micon">
                <i class="icofont icofont-megaphone"></i>
              </span>
                                  <span class="pcoded-mtext"><?=gettext('Lägg till vilt siren')?></span>
                              </a>
                          </li>
                          <li class="<?php if ($currentPage =='redigeraviltsiren' || $currentPage =='redviltsiren') {
                                echo 'active';
                            } ?>">
                              <a href="redigeraviltsiren.php" class="waves-effect waves-dark">
              <span class="pcoded-micon">
                <i class="icofont icofont-megaphone-alt"></i>
              </span>
                                  <span class="pcoded-mtext"><?=gettext('Redigera vilt siren')?></span>
                              </a>
                          </li>
                            <li class="<?php if ($currentPage =='adderavilt') {
                                echo 'active';
                            } ?>">
                                <a href="adderavilt.php" class="waves-effect waves-dark">
                <span class="pcoded-micon">
                  <i class="icofont icofont-animal-cat-with-dog"></i>
                </span>
                                    <span class="pcoded-mtext"><?=gettext('Lägg till vilt')?></span>
                                </a>
                            </li>
                            <li class="<?php if ($currentPage =='redigeravilt' || $currentPage =='redvilt') {
                                echo 'active';
                            } ?>">
                                <a href="redigeravilt.php" class="waves-effect waves-dark">
                <span class="pcoded-micon">
                  <i class="icofont icofont-animal-cat"></i>
                </span>
                                    <span class="pcoded-mtext"><?=gettext('Redigera vilt')?></span>
                                </a>
                            </li>
                            <li class="<?php if ($currentPage =='vilttimer') {
                                echo 'active';
                            } ?>">
                                <a href="timervilt.php" class="waves-effect waves-dark">
                <span class="pcoded-micon">
                  <i class="icofont icofont-ui-timer"></i>
                </span>
                                    <span class="pcoded-mtext"><?=gettext('Timer')?></span>
                                </a>
                            </li>


                        </ul>
                        <div class="pcoded-navigation-label"><?=gettext('Regnsensor')?></div>
                        <ul class="pcoded-item pcoded-left-item">


                          <li class="<?php if ($currentPage =='adderaregn') {
                                echo 'active';
                            } ?>">
                              <a href="adderaregn.php" class="waves-effect waves-dark">
              <span class="pcoded-micon">
                <i class="icofont icofont-rainy-thunder"></i>
              </span>
                                  <span class="pcoded-mtext"><?=gettext('Lägg till regnsensor')?></span>
                              </a>
                          </li>
                          <li class="<?php if ($currentPage =='redigeraregn'  || $currentPage =='redregn') {
                                echo 'active';
                            } ?>">
                              <a href="redigeraregn.php" class="waves-effect waves-dark">
              <span class="pcoded-micon">
                <i class="icofont icofont-rainy"></i>
              </span>
                                  <span class="pcoded-mtext"><?=gettext('Redigera regnsensorer')?></span>
                              </a>
                          </li>


                        </ul>
                        <div class="pcoded-navigation-label"><?=gettext('Zoner')?></div>
                        <ul class="pcoded-item pcoded-left-item">


                            <li class="<?php if ($currentPage =='adderazon') {
                                echo 'active';
                            } ?>">
                                <a href="adderazon.php" class="waves-effect waves-dark">
                <span class="pcoded-micon">
                  <i class="icofont icofont-farmer"></i>
                </span>
                                    <span class="pcoded-mtext"><?=gettext('Lägg till zon')?></span>
                                </a>
                            </li>

                            <li class="<?php if ($currentPage =='redigerazon' || $currentPage =='redzon') {
                                echo 'active';
                            } ?>">
                                <a href="redigerazon.php" class="waves-effect waves-dark">
                <span class="pcoded-micon">
                  <i class="icofont icofont-farmer1"></i>
                </span>
                                    <span class="pcoded-mtext"><?=gettext('Redigera zon')?></span>
                                </a>
                            </li>


                        </ul>
                        <div class="pcoded-navigation-label"><?=gettext('Övrigt')?></div>
                        <ul class="pcoded-item pcoded-left-item">


                            <li class="<?php if ($currentPage =='uppgifter') {
                                echo 'active';
                            } ?>">
                                <a href="uppgifter.php" class="waves-effect waves-dark">
                <span class="pcoded-micon">
                  <i class="icofont icofont-settings-alt"></i>
                </span>
                                    <span class="pcoded-mtext"><?=gettext('Inställningar')?></span>
                                </a>
                            </li>
                            <li class="<?php if ($currentPage =='anvandare') {
                                echo 'active';
                            } ?>">
                                <a href="anvandare.php" class="waves-effect waves-dark">
                <span class="pcoded-micon">
                  <i class="icofont icofont-settings-alt"></i>
                </span>
                                    <span class="pcoded-mtext"><?=gettext('Användare')?></span>
                                </a>
                            </li>
                            <li class="<?php if ($currentPage =='startaom') {
                                echo 'active';
                            } ?>">
                                <a href="#!" id="startaom" onclick="omstart()" class="waves-effect waves-dark">
                <span class="pcoded-micon">
                  <i class="icofont icofont-retweet"></i>
                </span>
                                    <span class="pcoded-mtext"><?=gettext('Starta om')?></span>
                                </a>
                            </li>
                            <li class="<?php if ($currentPage =='loggaut') {
                                echo 'active';
                            } ?>">
                                <a href="logout.php" class="waves-effect waves-dark">
                <span class="pcoded-micon">
                  <i class="icofont icofont-logout"></i>
                </span>
                                    <span class="pcoded-mtext"><?=gettext('Logga ut')?></span>
                                </a>
                            </li>


                        </ul>

                    </div>
                </nav>
