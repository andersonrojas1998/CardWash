
$(document).ready(function() {

    $(document).on("click","#btn_showChartApproved",function(){ 
        
        let period=$("#sel_periodScore").val();
        let grade=$("#sel_gradeScore").val();

        if(grade==''){
            sweetMessage('\u00A1Atenci\u00f3n!', 'Por favor complete  los campos requeridos.', 'warning');
        }

        $("#barChart").empty();

        $.ajax({ 
            url:'/Reportes/getReportApproved/'+period+'/'+grade,            
            type:"GET",
            success:function(data){
                var stackedbarChartCanvas = $("#barChart")
                .get(0)
                .getContext("2d");                
                let arr=JSON.parse(data); 
                var stackedbarChart = new Chart(stackedbarChartCanvas, {
                    type: "bar",
                    data: {
                        labels:   arr.asignatura,
                        datasets: [
                            {
                                label: "No Aprobaron",
                                backgroundColor: ChartColor[2],
                                borderColor: ChartColor[2],
                                borderWidth: 1,
                                data:  arr.perdidas
                            },
                            {
                                label: "Aprobaron",
                                backgroundColor: ChartColor[1],
                                borderColor: ChartColor[1],
                                borderWidth: 1,
                                data:  arr.aprovadas
                            }
                        ]
                    },
                    options: {
                    
                        scales: {
                            xAxes: [
                                {
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: "Estudiantes vs Asignaturas",
                                        fontColor: chartFontcolor,
                                        fontSize: 12,
                                        lineHeight: 2
                                    },
                                    ticks: {
                                        fontColor: chartFontcolor,                                      
                                    },
                                    gridLines: {
                                        display: false,
                                        drawBorder: false,
                                        color: chartGridLineColor,
                                        zeroLineColor: chartGridLineColor
                                    }
                                }
                            ],
                            yAxes: [
                                {
                                    display: true,                                   
                                    ticks: {
                                        fontColor: chartFontcolor,                                       
                                        min: 0,
                                        max: 60,                                     
                                    },                                   
                                }
                            ]
                        },
                        legend: {
                            display: true
                        },
                        legendCallback: function(chart) {
                            var text = [];
                            text.push('<div class="chartjs-legend"><ul>');
                            for (var i = 0; i < chart.data.datasets.length; i++) {
                               // console.log(chart.data.datasets[i]); // see what's inside the obj.
                                text.push("<li>");
                                text.push(
                                    '<span style="background-color:' +
                                        chart.data.datasets[i].backgroundColor +
                                        '">' +
                                        "</span>"
                                );
                                text.push(chart.data.datasets[i].label);
                                text.push("</li>");
                            }
                            text.push("</ul></div>");
                            return text.join("");
                        },
                        elements: {
                            point: {
                                radius: 0
                            }
                        }
                    }
                });
                document.getElementById(
                    "stacked-bar-traffic-legend"
                ).innerHTML = stackedbarChart.generateLegend();




        }
        });


    });
    

        

       
       
    



});



