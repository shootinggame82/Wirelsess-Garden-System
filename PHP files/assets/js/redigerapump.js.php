<?php
include '../../i18n_setup.php';
 ?>
function radera(idnr) {
  //För att starta & stoppa aktuell pump
  var r = confirm("<?=gettext('Vill du ta bort denna pump? Den kommer även att försvinna ifrån zonerna och sensorer blir pump lösa!')?>");
  if (r == true) {
    jQuery.ajax({
      type: "POST",
      url: "ajax/pumpbort.php",
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
          alert('<?=gettext('Pumpen är nu borttagen och vi uppdaterar sidan.')?>');
          $(location).attr('href', 'redigerapump.php');
        }
      }
    });
  } else {
    alert('<?=gettext('Pumpen har INTE tagist bort!')?>');
  }
}

function andra(idnr) {
  //För att starta & stoppa aktuell pump
  var r = confirm("<?=gettext('Vill du göra ändringar i denna pump?')?>");
  if (r == true) {
    $(location).attr('href', 'redpump.php?id=' + idnr);
  } else {
    alert('<?=gettext('Inga ändringar kommer att utföras!')?>');
  }
}
