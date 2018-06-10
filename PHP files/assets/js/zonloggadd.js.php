<?php
include '../../i18n_setup.php';
 ?>
$(document).ready(function() {
  $('#main').submit(function(event) {
    jQuery.ajax({
      type: "POST",
      url: "functions/zonloggadd.php",
      data: $("#main").serialize(),
      datatype: 'html',
      success: function(data) {
        var mydata = $.parseJSON(data);
        var fel = mydata.error;
        var kod = mydata.errorcode;
        if (fel == "true") {
          if (kod == "0") {
            alert('<?=gettext('Den angivna pumpen finns redan med i zonen.')?>');
          } else if (kod == "1") {
            alert('<?=gettext('Databas fel')?>');
          } else if (kod == "2") {
            alert('<?=gettext('Nått är fel i formuläret')?>');
          }
        } else if (fel == "false") {
          alert('<?=gettext('Din logg är nu skapad och du kan läsa, skriva ut eller ändra den.')?>');
        }
      }
    });
    e.preventDefault();
  });
});
