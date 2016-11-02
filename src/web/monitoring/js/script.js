$(document).ready(function() {
  var data = [
    [Date.UTC(2013,5,2,10,20,0),25],
    [Date.UTC(2013,5,2,10,20,5),24],
    [Date.UTC(2013,5,2,10,20,10),22],
    [Date.UTC(2013,5,2,10,20,15),25.5],
    [Date.UTC(2013,5,2,10,20,20),26.3],
    [Date.UTC(2013,5,2,10,20,25),29.3]
  ];
  

  var lectureId = 1;
  var type = "temperature";
  var id = "chart-temperature";
  getMeasurements(id, lectureId, type);

  type = "light";
  id = "chart-brightness";
  getMeasurements(id, lectureId, type);
});

function getMeasurements(id, lectureId, type) {
  var data = {};
  data.lecture_id = lectureId;
  data.type = type;

  $.ajax({
		type: "POST",
		url: "/monitoring/db/db_methods.php",
		data: {
      data: data,
			method: "getMeasurements"
		},
		success: function(result) {
      var resultObj = $.parseJSON(result);
			if(resultObj.status == "success") {
        var dataArray = [];
        for(var i in resultObj.data) {
          var parts = resultObj.data[i].timestamp.split(" ");
          var date = parts[0].split("-");
          var time = parts[1].split(":");
          dataArray.push([
            Date.UTC(date[0], date[1], date[2], time[0], time[1], time[2]), 
            parseInt(resultObj.data[i].value)
          ]);
        }

        var title;
        var textX;
        var textY;

        if(type == "temperature") {
          title = "Temperature - course name, lecture date etc (some ID lul, sweet) ";
          textX = "Time";
          textY = "Temperature (°C)";
        } else if(type == "light") {
          title = "Brightness - course name, lecture date etc (some ID lul, sweet) ";
          textX = "Time";
          textY = "Brightness (whoKnowsWhat.. lumen mayb? kOhm naaah?)";
        }
        drawChart(id, title, textX, textY, dataArray);

			} else {
				if('error' in resultObj) {
					alert("Error: " + resultObj.error);
				} else {
					console.log("What the heck happened??");
				}
			} 
		}    
	});
}

function drawChart(id, title, textX, textY, data){
        Highcharts.chart(id, {
            chart: {
                zoomType: 'x'
            },
            title: {
                text: title
            },
            subtitle: {
                text: document.ontouchstart === undefined ?
                        'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
            },
            xAxis: {
                title: {
                   text: textX
                },
                type: 'datetime'
            },
            yAxis: {
                title: {
                    text: textY
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                area: {
                    fillColor: {
                        linearGradient: {
                            x1: 0,
                            y1: 0,
                            x2: 0,
                            y2: 1
                        },
                        stops: [
                            [0, Highcharts.getOptions().colors[0]],
                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                        ]
                    },
                    marker: {
                        radius: 1
                    },
                    lineWidth: 0.3,
                    states: {
                        hover: {
                            lineWidth: 0.3
                        }
                    },
                    threshold: null
                }
            },

            series: [{
                type: 'area',
                name: 'Temp (°C)',
                data: data
            }]
        });
}