<?php
include '../../i18n_setup.php';
 ?>
function radera(idnr) {
  //För att starta & stoppa aktuell pump
  var r = confirm("<?=gettext('Vill du ta bort denna timer?')?>");
  if (r == true) {
    jQuery.ajax({
      type: "POST",
      url: "ajax/timerbortvilt.php",
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

          $(location).attr('href', 'timervilt.php');
        }
      }
    });
  } else {
    alert('<?=gettext('Timern har inte tagits bort')?>');
  }
}

function redigera(idnr) {
  //För att starta & stoppa aktuell pump
  var r = confirm("<?=gettext('Vill du ta redigera denna timer?')?>");
  if (r == true) {
    $.ajax({
      url: 'ajax/vilttimerinfo.php',
      type: "GET",
      data: "id=" + idnr,
      dataType: 'json',
      success: function(response) {
        var objData = jQuery.parseJSON(response);
        var gid = objData.id;
        var namn = objData.namn;
        var start = objData.start;
        var slut = objData.slut;
        var mon = objData.mon;
        var tis = objData.tis;
        var ons = objData.ons;
        var tor = objData.tor;
        var fre = objData.fre;
        var lor = objData.lor;
        var son = objData.son;
        var vilt = objData.vilt;
        $('#namn').val(namn);
        $('#start').val(start);
        $('#slut').val(slut);
        if (mon == 1) {
          $("#mon").prop("checked", true);
        } else {
          $("#mon").prop("checked", false);
        }
        if (tis == 1) {
          $("#tis").prop("checked", true);
        } else {
          $("#tis").prop("checked", false);
        }
        if (ons == 1) {
          $("#ons").prop("checked", true);
        } else {
          $("#ons").prop("checked", false);
        }
        if (tor == 1) {
          $("#tors").prop("checked", true);
        } else {
          $("#tors").prop("checked", false);
        }
        if (fre == 1) {
          $("#fre").prop("checked", true);
        } else {
          $("#fre").prop("checked", false);
        }
        if (lor == 1) {
          $("#lor").prop("checked", true);
        } else {
          $("#lor").prop("checked", false);
        }
        if (son == 1) {
          $("#son").prop("checked", true);
        } else {
          $("#son").prop("checked", false);
        }
        $("#pump").val(vilt).change();
        $("#uppdatera").val("1");
        $("#idnumret").val(gid);
      }

    });
  } else {
    alert('<?=gettext('Timern kommer ej att redigeras.')?>');
  }
}
