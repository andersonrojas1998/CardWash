
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

    if ($("#chart_salesxmonth").length) {

         $.ajax({ 
            url:'/reports/getSalesxMonth',
            type:"GET",
            success:function(data){
                let arr=JSON.parse(data); 

                var barChartCanvas = $("#chart_salesxmonth")
            .get(0)
            .getContext("2d");
        var barChart = new Chart(barChartCanvas, {
            type: "bar",
            data: {
                labels: [
                    "Ene",
                    "Feb",
                    "Mar",
                    "Abr",
                    "May",
                    "Jun",
                    "Jul",
                    "Agos",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dic"
                ],
                datasets: [
                    {
                        label: "ventas",
                        data: arr,
                        backgroundColor: ChartColor[0],
                        borderColor: ChartColor[0],
                        borderWidth: 0
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
               layout: {
                    padding: {
                        left: 0,
                        right: 0,
                        top: 0,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [
                        {
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: "ventas por año",
                                fontColor: chartFontcolor,
                                fontSize: 9,
                                lineHeight: 2
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
                            scaleLabel: {
                                display: true,
                                labelString: "cantidad por mes",
                                fontColor: chartFontcolor,
                                fontSize: 12,
                                lineHeight: 2
                            },
                            ticks: {
                                display: true,
                                autoSkip: false,
                                maxRotation: 0,
                                fontColor: chartFontcolor,
                                stepSize: 50,
                                min: 0,                               
                            },
                            gridLines: {
                                drawBorder: false,
                                color: chartGridLineColor,
                                zeroLineColor: chartGridLineColor
                            }
                        }
                    ]
                },
                legend: {
                    display: false
                },
                legendCallback: function(chart) {
                    var text = [];
                    text.push('<div class="chartjs-legend"><ul>');
                    for (var i = 0; i < chart.data.datasets.length; i++) {
                        console.log(chart.data.datasets[i]); // see what's inside the obj.
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
            "bar-traffic-legend"
        ).innerHTML = barChart.generateLegend();

            }
        });


        
    }
    if ($("#chart_salesxday").length) {


        $.ajax({ 
            url:'/reports/getSalesxDay/',  
            type:"GET",
            success:function(data){
                
                
                let arr=JSON.parse(data); 
                console.log( Object.values(arr.label));
                var lineData = {
                    labels:  Object.values(arr.label)  ,
                    datasets: [
                        {
                            data: Object.values(arr.cantidad),
                            backgroundColor: ChartColor[0],
                            borderColor: ChartColor[0],
                            borderWidth: 3,
                            fill: "ventas",
                            label: "ventas cant."
                        }
                    ]
                };

                var lineOptions = {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        filler: {
                            propagate: false
                        }
                    },

                    

                   scales: {
                        xAxes: [
                            {
                                display: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: "Dias",
                                    fontSize: 12,
                                    lineHeight: 2,
                                    fontColor: chartFontcolor
                                },
                              
                            }
                        ],
                        yAxes: [
                            {
                               display: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: "Cantidad de Ventas",
                                    fontSize: 12,
                                    lineHeight: 2,
                                    fontColor: chartFontcolor
                                },
                                ticks: {                                  
                                    maxRotation: 0,
                                    stepSize: 100,
                                    min: 0,                                  
                                },
                              
                            }
                        ]
                    },
                    legend: {
                        display: false
                    },
                    legendCallback: function(chart) {
                        var text = [];
                        text.push('<div class="chartjs-legend"><ul>');
                        for (var i = 0; i < chart.data.datasets.length; i++) {
                            console.log(chart.data.datasets[i]); // see what's inside the obj.
                            text.push("<li>");
                            text.push(
                                '<span style="background-color:' +
                                    chart.data.datasets[i].borderColor +
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
                        line: {
                            tension: 0
                        },
                        point: {
                            radius: 0
                        }
                    }
                };
                var lineChartCanvas = $("#chart_salesxday")
                    .get(0)
                    .getContext("2d");
                var lineChart = new Chart(lineChartCanvas, {
                    type: "line",
                    data: lineData,
                    options: lineOptions
                });
                document.getElementById(
                    "line-traffic-legend"
                ).innerHTML = lineChart.generateLegend();



            }
        
        
        
        
        
        
        });


      
        
    }


    if ($("#dt_expenses_month").length){
        dt_expenses_month();

    } 


    
if ($("#chart_income_service").length) {
    $.ajax({ 
        url:'/reports/chart_income_service', 
        type:"GET",
        success:function(data){
            let d= JSON.parse(data);
            $('#total_income').html('$ ' + d.total);

            var pieChartCanvas = $("#chart_income_service")
            .get(0)
            .getContext("2d");
        var pieChart = new Chart(pieChartCanvas, {
            type: "pie",
            data: {
                datasets: [
                    {
                        data:  [d.service,d.product],
                        backgroundColor: [
                            ChartColor[0],
                            ChartColor[1],                            
                        ],
                        borderColor: [
                            ChartColor[0],
                            ChartColor[1],                            
                        ]
                    }
                ],
                labels: ["Servicios", "Productos"]
            },
            options: {
                responsive: true,
                animation: {
                    animateScale: true,
                    animateRotate: true
                },
                legend: {
                    display: false
                },
                legendCallback: function(chart) {
                    var text = [];
                    text.push('<div class="chartjs-legend"><ul>');
                    for (
                        var i = 0;
                        i < chart.data.datasets[0].data.length;
                        i++
                    ) {
                        text.push(
                            '<li><span style="background-color:' +
                                chart.data.datasets[0].backgroundColor[i] +
                                '">'
                        );
                        text.push("</span>");
                        if (chart.data.labels[i]) {
                            text.push(chart.data.labels[i]);
                        }
                        text.push("</li>");
                    }
                    text.push("</div></ul>");
                    return text.join("");
                }
            }
        });
        document.getElementById(
            "pie-chart-legend"
        ).innerHTML = pieChart.generateLegend();

        }
    });

    
}

    

        

       
       
    



});


var dt_expenses_month=function(){

    $('#dt_expenses_month').DataTable({                  
        ajax: {
            url: "/reports/dt_expenses_month",
            method: "GET", 
            dataSrc: function (json) {                
                if (!json.data) {
                    return [];
                } else {
                    return json.data;
                }
              }               
            },
        columnDefs: [                  
            { orderable: false, targets: '_all' }
        ],       
        columns: 
        [       
            { "data": "no" , render(data){return '<p class="text-muted">'+data+'</p>';}},         
            { "data": "concepto" , render(data){return '<b>'+data+'</b>';}},         
            { "data": "valor",render(data,type,row){ return data; }},                                
        ]
    });
}



