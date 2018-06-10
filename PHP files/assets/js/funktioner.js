//var sensid = document.getElementById("sensorid").getAttribute("data-name");

function fetchdata() {

  jQuery.ajax({
    type: "POST",
    url: "ajax/startinfo.php",
    data: {
      id: '1',
    },
    datatype: 'html',
    success: function(data) {
      var mydata = $.parseJSON(data);
      var pump = mydata.pump;
      if (pump == "true") {
        PNotify.desktop.permission();
        (new PNotify({
          title: 'Bevattnings pump aktiv',
          type: 'warning',
          text: 'Det finns bevattnings pump(ar) som är igång och kör',
          desktop: {
            desktop: true,
            icon: 'assets/images/pnotify/warning.png'
          }
        })).get().click(function(e) {
          if ($('.ui-pnotify-closer, .ui-pnotify-sticker, .ui-pnotify-closer *, .ui-pnotify-sticker *').is(e.target)) return;
          alert('Det finns bevattnings pumpar som är igång och vattnar just nu!');
        });
      }
    }
  });

  jQuery.ajax({
    type: "POST",
    url: "ajax/viltinfo.php",
    data: {
      id: '1',
    },
    datatype: 'html',
    success: function(data) {
      var mydata = $.parseJSON(data);
      var vilt = mydata.vilt;
      if (vilt == "true") {
        PNotify.desktop.permission();
        (new PNotify({
          title: 'Vilt sensor aktiv',
          type: 'warning',
          text: 'Vilt sensorn är aktiverad och larmar om något händer.',
          desktop: {
            desktop: true,
            icon: 'assets/images/pnotify/warning.png'
          }
        })).get().click(function(e) {
          if ($('.ui-pnotify-closer, .ui-pnotify-sticker, .ui-pnotify-closer *, .ui-pnotify-sticker *').is(e.target)) return;
          alert('Det finns viltsensorer aktiverad och larmar om något händer!');
        });
      }
    }
  });


  jQuery.ajax({
    type: "POST",
    url: "ajax/batterisensor.php",
    data: {
      id: '1',
    },
    datatype: 'html',
    success: function(data) {
      var mydata = $.parseJSON(data);
      var batteri = mydata.sensbat;
      if (batteri == "true") {
        PNotify.desktop.permission();
        (new PNotify({
          title: 'Låg batteri nivå i sensor',
          type: 'danger',
          text: 'Det börjar bli låg batteri nivå i någon jord sensor.',
          desktop: {
            desktop: true,
            icon: 'assets/images/pnotify/danger.png'
          }
        })).get().click(function(e) {
          if ($('.ui-pnotify-closer, .ui-pnotify-sticker, .ui-pnotify-closer *, .ui-pnotify-sticker *').is(e.target)) return;
          alert('Vill upplysa om att det finns jord sensorer som har låg batteri nivå.');
        });
      }
    }
  });


}

$(document).ready(function() {
  //setInterval(fetchdata, 60000);
  fetchdata();
});