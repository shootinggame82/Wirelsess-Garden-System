<?php
include '../../i18n_setup.php';
 ?>
function radera(idnr) {
  //För att starta & stoppa aktuell pump
  var r = confirm("<?=gettext('Vill du ta bort denna sensor? Den kommer även att försvinna ifrån zonerna och statistiken kommer att försvinna!')?>");
  if (r == true) {
    jQuery.ajax({
      type: "POST",
      url: "ajax/sensorbort.php",
      data: {
        id: idnr,
      },
      datatype: 'html',
      success: function(data) {
        var mydata = $.parseJSON(data);
        var fel = mydata.error;
        var kod = mydata.errorcode;
        if (fel == "true") {
          if (kod == "1") {
            alert('<?=gettext('Vi har tekniska problem med databasen just nu, kontakta admin.')?>');
          } else if (kod == "2") {
            alert('<?=gettext('Det är problem med att skicka just nu, kontakta admin.')?>');
          }
        } else if (fel == "false") {
          alert('<?=gettext('Sensorn är nu borttagen och vi uppdaterar sidan.')?>');
          $(location).attr('href', 'redigerasensor.php');
        }
      }
    });
  } else {
    alert('<?=gettext('Sensorn har INTE tagist bort!')?>');
  }
}

function andra(idnr) {
  //För att starta & stoppa aktuell pump
  var r = confirm("<?=gettext('Vill du göra ändringar i denna sensor?')?>");
  if (r == true) {
    $(location).attr('href', 'redsensor.php?id=' + idnr);
  } else {
    alert('<?=gettext('Inga ändringar kommer att utföras!')?>');
  }
}
