  'use strict';
  $(document).ready(function() {
    jordsensorDag();
    jordsensorVecka();

    $(window).resize(function() {
      jordsensorDag();
      jordsensorVecka();
    });

    $(window).on('resize', function() {
      //dashboardEcharts();
      jordsensorDag();
      jordsensorVecka();
    });


  });



  var sensid = document.getElementById("sensorid").getAttribute("data-name");

  function jordsensorDag() {


    //website States
    var myChart = echarts.init(document.getElementById('tempdag'));
    //myChart.showLoading();
    $.getJSON('sensorer/sensordag.php?id=' + sensid, function(sens1) {
      //  myChart.hideLoading();

      var option = {
        tooltip: {
          trigger: 'axis'
        },
        legend: {
          data: ['Temperatur', 'Jordfuktighet']
        },

        toolbox: {
          show: false,
          feature: {
            mark: {
              show: true
            },
            dataView: {
              show: true,
              readOnly: false
            },
            magicType: {
              show: true,
              type: ['line', 'bar', 'stack', 'tiled']
            },
            restore: {
              show: true
            },
            saveAsImage: {
              show: true
            }
          }
        },
        color: ["#1B8BF9", "#49C1F7", "#EF4B28", "#EF2828"],
        calculable: true,
        xAxis: [{
          type: 'category',
          boundaryGap: false,
          data: sens1.tid
        }],
        yAxis: [{
          type: 'value',
          name: 'Värden',
        }],
        series: [{
            name: 'Temperatur',
            type: 'line',
            smooth: true,
            itemStyle: {
              normal: {
                areaStyle: {
                  type: 'default'
                }
              }
            },
            data: sens1.temp
          },
          {
            name: 'Jordfuktighet',
            type: 'line',
            smooth: true,
            itemStyle: {
              normal: {
                areaStyle: {
                  type: 'default'
                }
              }
            },
            data: sens1.fukt
          }
        ],
        grid: {
          zlevel: 0,
          z: 0,
          x: 40,
          y: 40,
          x2: 40,
          y2: 40,
          backgroundColor: 'rgba(0,0,0,0)',
          borderColor: '#fff',
        },
      }; //

      myChart.setOption(option);

    });

  }

  function jordsensorVecka() {


    //website States
    var myChart = echarts.init(document.getElementById('tempvecka'));
    //  myChart.showLoading();
    $.getJSON('sensorer/sensorvecka.php?id=' + sensid, function(sens2) {
      //  myChart.hideLoading();

      var option = {
        tooltip: {
          trigger: 'axis'
        },
        legend: {
          data: ['Temperatur', 'Jordfuktighet']
        },

        toolbox: {
          show: false,
          feature: {
            mark: {
              show: true
            },
            dataView: {
              show: true,
              readOnly: false
            },
            magicType: {
              show: true,
              type: ['line', 'bar', 'stack', 'tiled']
            },
            restore: {
              show: true
            },
            saveAsImage: {
              show: true
            }
          }
        },
        color: ["#1B8BF9", "#49C1F7", "#EF4B28", "#EF2828"],
        calculable: true,
        xAxis: [{
          type: 'category',
          boundaryGap: false,
          data: sens2.dagar
        }],
        yAxis: [{
          type: 'value',
          name: 'Värden',
        }],
        series: [{
            name: 'Temperatur',
            type: 'line',
            smooth: true,
            itemStyle: {
              normal: {
                areaStyle: {
                  type: 'default'
                }
              }
            },
            data: sens2.temp
          },
          {
            name: 'Jordfuktighet',
            type: 'line',
            smooth: true,
            itemStyle: {
              normal: {
                areaStyle: {
                  type: 'default'
                }
              }
            },
            data: sens2.fukt
          }
        ],
        grid: {
          zlevel: 0,
          z: 0,
          x: 40,
          y: 40,
          x2: 40,
          y2: 40,
          backgroundColor: 'rgba(0,0,0,0)',
          borderColor: '#fff',
        },
      }; //

      myChart.setOption(option);

    });

  }