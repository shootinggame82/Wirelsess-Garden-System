<?php
include '../../i18n_setup.php';
 ?>
function radera(idnr) {
  //För att starta & stoppa aktuell pump
  var r = confirm("<?=gettext('Vill du ta bort denna siren? Den kommer även att försvinna ifrån vilt sensorerna och dom kommer ej kunna användas!')?>");
  if (r == true) {
    jQuery.ajax({
      type: "POST",
      url: "ajax/sirenbort.php",
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
          alert('<?=gettext('Sirenen är nu borttagen och vi uppdaterar sidan.')?>');
          $(location).attr('href', 'redigeraviltsiren.php');
        }
      }
    });
  } else {
    alert('<?=gettext('Sirenen har INTE tagist bort!')?>');
  }
}

function andra(idnr) {
  //För att starta & stoppa aktuell pump
  var r = confirm("<?=gettext('Vill du göra ändringar i denna siren?')?>");
  if (r == true) {
    $(location).attr('href', 'redviltsiren.php?id=' + idnr);
  } else {
    alert('<?=gettext('Inga ändringar kommer att utföras!')?>');
  }
}
