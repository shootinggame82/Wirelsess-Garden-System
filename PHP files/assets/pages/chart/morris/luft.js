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



function dagsensor() {
  $.getJSON("sensorer/luftdag.php", function(data) {
    Morris.Area({
      element: "morris-luftdag",
      data: data,
      xkey: 'tid',
      ykeys: ['temp', 'fukt', 'heat'],
      labels: ['Temperatur', 'Fuktighet', 'Värmeindex'],
      lineColors: ['#1B8BF9', '#EF2828', '#EF4B28'],
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
  $.getJSON("sensorer/luftvecka.php", function(data) {
    Morris.Area({
      element: "morris-luftvecka",
      data: data,
      xkey: 'dag',
      ykeys: ['temp', 'fukt', 'heat'],
      labels: ['Temperatur', 'Fuktighet', 'Värmeindex'],
      lineColors: ['#1B8BF9', '#EF2828', '#EF4B28'],
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
  $.getJSON("sensorer/luftmanad.php", function(data) {
    Morris.Area({
      element: "morris-luftmanad",
      data: data,
      xkey: 'dag',
      ykeys: ['temp', 'fukt', 'heat'],
      labels: ['Temperatur', 'Fuktighet', 'Värmeindex'],
      lineColors: ['#1B8BF9', '#EF2828', '#EF4B28'],
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