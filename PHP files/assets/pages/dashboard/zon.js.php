<?php
include '../../../i18n_setup.php';
 ?>
function pump(idnr) {
  //För att starta & stoppa aktuell pump
  jQuery.ajax({
    type: "POST",
    url: "ajax/pumpstart.php",
    data: {
      id: idnr,
    },
    datatype: 'html',
    success: function(data) {
      var mydata = $.parseJSON(data);
      var fel = mydata.error;
      var kod = mydata.errorcode;
      var status = mydata.status;
      var pumpnamn = mydata.pumpnamn;
      var pumpnr = mydata.pumpnr;
      if (fel == "true") {
        if (kod == "1") {
          alert('<?=gettext('Vi har tekniska problem med databasen just nu, kontakta admin.')?>');
        } else if (kod == "2") {
          alert('<?=gettext('Det är problem med att skicka just nu, kontakta admin.')?>');
        }
      } else if (fel == "false") {
        if (status == '1') {
          $("#pump_" + idnr).html('Inaktivera');
          new PNotify({
            title: '<?=gettext('Bevattnings pump aktiverad')?>',
            text: '<?=gettext('Bevattning')?> ' + pumpnr + ' - ' + pumpnamn + ' <?=gettext('är aktiverad och körs nu.')?>',
            icon: 'icofont icofont-info-circle',
            type: 'info'
          });
        } else {
          $("#pump_" + idnr).html('Aktivera');
          new PNotify({
            title: '<?=gettext('Bevattnings pump avstängd')?>',
            text: '<?=gettext('Bevattning')?> ' + pumpnr + ' - ' + pumpnamn + ' <?=gettext('är avstängd och stängs av.')?>',
            icon: 'icofont icofont-info-circle',
            type: 'info'
          });
        }
      }
    }
  });
}

function vilt(idnr) {
  //För att starta & stoppa aktuell pump
  jQuery.ajax({
    type: "POST",
    url: "ajax/viltstart.php",
    data: {
      id: idnr,
    },
    datatype: 'html',
    success: function(data) {
      var mydata = $.parseJSON(data);
      var fel = mydata.error;
      var kod = mydata.errorcode;
      var status = mydata.status;
      var viltnamn = mydata.viltnamn;
      var viltnr = mydata.viltnr;
      if (fel == "true") {
        if (kod == "1") {
          alert('<?=gettext('Vi har tekniska problem med databasen just nu, kontakta admin.')?>');
        } else if (kod == "2") {
          alert('<?=gettext('Det är problem med att skicka just nu, kontakta admin.')?>');
        }
      } else if (fel == "false") {
        if (status == '1') {
          $("#viltstatus_" + idnr).html('Inaktivera');
          new PNotify({
            title: '<?=gettext('Viltvanare aktiverad')?>',
            text: '<?=gettext('Viltvanare')?> ' + viltnr + ' - ' + viltnamn + ' <?=gettext('är aktiverad och redo att larma.')?>',
            icon: 'icofont icofont-info-circle',
            type: 'default'
          });
        } else {
          $("#viltstatus_" + idnr).html('Aktivera');
          new PNotify({
            title: '<?=gettext('Viltvanare avstängd')?>',
            text: '<?=gettext('Viltvanare')?> ' + viltnr + ' - ' + viltnamn + ' <?=gettext('är avstängd och kommer ej att larma.')?>',
            icon: 'icofont icofont-info-circle',
            type: 'default'
          });
        }
      }
    }
  });
}

function siren(idnr) {
  //För att starta & stoppa aktuell pump
  jQuery.ajax({
    type: "POST",
    url: "ajax/sirenstart.php",
    data: {
      id: idnr,
    },
    datatype: 'html',
    success: function(data) {
      var mydata = $.parseJSON(data);
      var fel = mydata.error;
      var kod = mydata.errorcode;
      var status = mydata.status;
      if (fel == "true") {
        if (kod == "1") {
          alert('<?=gettext('Vi har tekniska problem med databasen just nu, kontakta admin.')?>');
        } else if (kod == "2") {
          alert('<?=gettext('Det är problem med att skicka just nu, kontakta admin.')?>');
        }
      } else if (fel == "false") {
        alert("<?=gettext('Sirenen startar strax, stängs av automatiskt.')?>");
      }
    }
  });
}

function status() {

}

$(document).ready(function() {
  //  setInterval(status(), 30000);

  setInterval(function() {
    $.getJSON('ajax/autostart.php', function(data) {
      $.each(data, function(index) {
        if (data[index].aktiverad == 1) {
          $("#pump_" + data[index].id).html('<?=gettext('Inaktivera')?>');
        } else {
          $("#pump_" + data[index].id).html('<?=gettext('Aktivera')?>');
        }
      });
    });
  }, 30000);
  setInterval(function() {
    $.getJSON('ajax/autostartvilt.php', function(data) {
      $.each(data, function(index) {
        if (data[index].aktiverad == 1) {
          $("#viltstatus_" + data[index].id).html('<?=gettext('Inaktivera')?>');
        } else {
          $("#viltstatus_" + data[index].id).html('<?=gettext('Aktivera')?>');
        }
      });
    });
  }, 30000);

  setInterval(function() {
    $.getJSON('ajax/autostartsensor.php', function(data) {
      $.each(data, function(index) {
        if (data[index].avlast == 1) {
          new PNotify({
            title: '<?=gettext('Jordsensor avläst')?>',
            text: '<?=gettext('Jordsensor')?> ' + data[index].sensornr + ' - ' + data[index].namn + ' <?=gettext('har blivit avläst nyligen.')?>',
            icon: 'icofont icofont-info-circle',
            type: 'info'
          });
        }
      });
    });
  }, 30000);
});
