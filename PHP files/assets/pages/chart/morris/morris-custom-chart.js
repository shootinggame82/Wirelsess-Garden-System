"use strict";
//setTimeout(function() {
$(document).ready(function() {

  dagsensor();
  manadsensor();
  veckosensor();

  $(window).on('resize', function() {
    window.dagsensor.redraw();
    window.manadsensor.redraw();
    window.veckosensor.redraw();
  });

});


var sensid = document.getElementById("sensorid").getAttribute("data-name");





function dagsensor() {
  $.getJSON("sensorer/sensorerdagmorris.php?id=" + sensid, function(data) {
    Morris.Area({
      element: "morris-extra-area",
      data: data,
      xkey: 'tid',
      ykeys: ['temp', 'fukt'],
      labels: ['Temperatur', 'Fuktighet'],
      lineColors: ['#1B8BF9', '#EF2828'],
      pointSize: 0,
      lineWidth: 0,
      resize: true,
      fillOpacity: 0.8,
      behaveLikeLine: true,
      gridLineColor: '#5FBEAA',
      hideHover: 'auto',
      parseTime: false
    });
  });
};

function veckosensor() {
  $.getJSON("sensorer/sensorvecka.php?id=" + sensid, function(data) {
    Morris.Area({
      element: "morris-vecka",
      data: data,
      xkey: 'dag',
      ykeys: ['temp', 'fukt'],
      labels: ['Temperatur', 'Fuktighet'],
      lineColors: ['#1B8BF9', '#EF2828'],
      pointSize: 0,
      lineWidth: 0,
      resize: true,
      fillOpacity: 0.8,
      behaveLikeLine: true,
      gridLineColor: '#5FBEAA',
      hideHover: 'auto',
      parseTime: false
    });
  });
};

function manadsensor() {
  $.getJSON("sensorer/sensormanad.php?id=" + sensid, function(data) {
    Morris.Area({
      element: "morris-manad",
      data: data,
      xkey: 'dag',
      ykeys: ['temp', 'fukt'],
      labels: ['Temperatur', 'Fuktighet'],
      lineColors: ['#1B8BF9', '#EF2828'],
      pointSize: 0,
      lineWidth: 0,
      resize: true,
      fillOpacity: 0.8,
      behaveLikeLine: true,
      gridLineColor: '#5FBEAA',
      hideHover: 'auto',
      parseTime: false
    });
  });
};