<?php
include '../../i18n_setup.php';
 ?>
function radera(idnr, zonid) {
  //För att starta & stoppa aktuell pump
  var r = confirm("<?=gettext('Vill du ta bort denna zonlogg?')?>");
  if (r == true) {
    jQuery.ajax({
      type: "POST",
      url: "ajax/zonloggbort.php",
      data: {
        id: idnr,
        zon: zonid,
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

          $(location).attr('href', 'zonloggar.php?id=' + zonid);
        }
      }
    });
  } else {
    alert('<?=gettext('Loggen har inte tagits bort.')?>');
  }
}
