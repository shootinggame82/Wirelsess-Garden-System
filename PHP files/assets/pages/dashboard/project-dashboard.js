'use strict';
$(document).ready(function() {
    var chart = AmCharts.makeChart("product-sales-chart", {
        "type": "serial",
        "theme": "light",
        "dataDateFormat": "YYYY-MM-DD",
        "precision": 2,
        "valueAxes": [{
            "id": "v1",
            "title": "Sales",
            "position": "left",
            "autoGridCount": false,
            "labelFunction": function(value) {
                return "$" + Math.round(value) + "M";
            }
        }, {
            "id": "v2",
            "gridAlpha": 0.1,
            "autoGridCount": false
        }],
        "graphs": [{
            "id": "g1",
            "valueAxis": "v2",
            "lineThickness": 0,
            "fillAlphas": 0.9,
            "lineColor": "#448aff",
            "type": "smoothedLine",
            "title": "Laptop",
            "useLineColorForBulletBorder": true,
            "valueField": "market1",
            "balloonText": "[[title]]<br /><b style='font-size: 130%'>[[value]]</b>"
        }, {
            "id": "g2",
            "valueAxis": "v2",
            "fillAlphas": 0.9,
            "bulletColor": "#ff5252",
            "lineThickness": 0,
            "lineColor": "#ff5252",
            "type": "smoothedLine",
            "title": "TV",
            "useLineColorForBulletBorder": true,
            "valueField": "market2",
            "balloonText": "[[title]]<br /><b style='font-size: 130%'>[[value]]</b>"
        }, {
            "id": "g3",
            "valueAxis": "v2",
            "fillAlphas": 0.9,
            "bulletColor": "#9ccc65",
            "lineThickness": 0,
            "lineColor": "#9ccc65",
            "type": "smoothedLine",
            "title": "Mobile",
            "useLineColorForBulletBorder": true,
            "valueField": "sales1",
            "balloonText": "[[title]]<br /><b style='font-size: 130%'>[[value]]</b>"
        }],
        "chartCursor": {
            "pan": true,
            "valueLineEnabled": true,
            "valueLineBalloonEnabled": true,
            "cursorAlpha": 0,
            "valueLineAlpha": 0.2
        },
        "categoryField": "date",
        "categoryAxis": {
            "parseDates": true,
            "gridAlpha": 0,
            "minorGridEnabled": true
        },
        "legend": {
            "position": "top",
        },
        "balloon": {
            "borderThickness": 1,
            "shadowAlpha": 0
        },
        "export": {
            "enabled": true
        },
        "dataProvider": [{
            "date": "2013-01-01",
            "market1": 0,
            "market2": 0,
            "sales1": 0
        }, {
            "date": "2013-02-01",
            "market1": 0,
            "market2": 0,
            "sales1": 40
        }, {
            "date": "2013-03-01",
            "market1": 0,
            "market2": 0,
            "sales1": 0
        }, {
            "date": "2013-04-01",
            "market1": 30,
            "market2": 0,
            "sales1": 0
        }, {
            "date": "2013-05-01",
            "market1": 0,
            "market2": 20,
            "sales1": 0
        }, {
            "date": "2013-06-01",
            "market1": 25,
            "market2": 0,
            "sales1": 0
        }, {
            "date": "2013-07-01",
            "market1": 0,
            "market2": 0,
            "sales1": 0
        }, {
            "date": "2013-08-01",
            "market1": 0,
            "market2": 0,
            "sales1": 30
        }, {
            "date": "2013-09-01",
            "market1": 0,
            "market2": 0,
            "sales1": 0
        }, {
            "date": "2013-10-01",
            "market1": 0,
            "market2": 50,
            "sales1": 0
        }, {
            "date": "2013-11-01",
            "market1": 0,
            "market2": 0,
            "sales1": 65
        }, {
            "date": "2013-12-01",
            "market1": 0,
            "market2": 0,
            "sales1": 0
        }]
    });
    // pageview and prod sale end
    floatchart()
    $(window).on('resize', function() {
        floatchart();
    });
    $('#mobile-collapse').on('click', function() {
        setTimeout(function() {
            floatchart();
        }, 700);
    });

});

function floatchart() {
    $(function() {
        //flot options
        var options = {
            legend: {
                show: false
            },
            series: {
                label: "",
                curvedLines: {
                    active: true,
                    nrSplinePoints: 20
                },
            },
            tooltip: {
                show: true,
                content: "x : %x | y : %y"
            },
            grid: {
                hoverable: true,
                borderWidth: 0,
                labelMargin: 0,
                axisMargin: 0,
                minBorderMargin: 0,
            },
            yaxis: {
                min: 0,
                max: 30,
                color: 'transparent',
                font: {
                    size: 0,
                }
            },
            xaxis: {
                color: 'transparent',
                font: {
                    size: 0,
                }
            }
        };

    });
}
