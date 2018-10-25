
$(document).ready(function () {
    
    var ACTIVE=[];
    var BLOCKED_BY_PAYMENT=[];
    var BLOCKED_BY_INSTA=[];
    var VERIFY_ACCOUNT=[];
    var BLOCKED_BY_TIME=[];
    for(i=0;i<DATAS.length;i++) {
        date = new Date(DATAS[i]['date']*1000);
        ACTIVE.push({'x':date, 'y':parseInt(DATAS[i]['ACTIVE'])});
        BLOCKED_BY_PAYMENT.push({'x':date, 'y':parseInt(DATAS[i]['BLOCKED_BY_PAYMENT'])});
        BLOCKED_BY_INSTA.push({'x':date, 'y':parseInt(DATAS[i]['BLOCKED_BY_INSTA'])});
        VERIFY_ACCOUNT.push({'x':date, 'y':parseInt(DATAS[i]['VERIFY_ACCOUNT'])});
        BLOCKED_BY_TIME.push({'x':date, 'y':parseInt(DATAS[i]['BLOCKED_BY_TIME'])});
    }
        
    chart = new CanvasJS.Chart("chartContainer", {
        title: {
         text: "Estatísticas Dumbu",
         fontSize: 30
         },
        zoomEnabled: true, 
        animationEnabled: true,
        animationDuration: 2500,
        axisX: {
            gridThickness: 0.5,
            gridColor: "Silver",
            tickThickness: 5,
            tickColor: "silver",
	    valueFormatString: "DD/MM/YY"
         },
        toolTip: {
            shared: true
        },
        theme: "theme2",
        axisY: {
            gridThickness: 0.5,
            tickThickness: 5,
            gridColor: "Silver",
            tickColor: "silver"
        },
        legend: {
            verticalAlign: "center",
            horizontalAlign: "right"
        },
        data: [
            {
                type: "line",
                showInLegend: true,
                lineThickness: 2,
                name: 'ACTIVE',
                markerType: "square",
                color: "green",
                dataPoints: ACTIVE
            },
            {
                type: "line",
                showInLegend: true,                
                name: 'B_PAYMENT',
                color: "red",
                lineThickness: 2,
                dataPoints: BLOCKED_BY_PAYMENT                
            },
            {
                type: "line",
                showInLegend: true,                
                name: 'B_INSTA',
                color: "blue",
                lineThickness: 2,
                dataPoints: BLOCKED_BY_INSTA                
            },
            {
                type: "line",
                showInLegend: true,                
                name: 'V_ACCOUNT',
                color: "black",
                lineThickness: 2,
                dataPoints: VERIFY_ACCOUNT                
            },
            {
                type: "line",
                showInLegend: true,                
                name: 'B_TIME',
                color: "orange",
                lineThickness: 2,
                dataPoints: BLOCKED_BY_TIME                
            },
        ],
        legend: {
            cursor: "pointer",
            itemclick: function (e) {
                if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                    e.dataSeries.visible = false;
                } else {
                    e.dataSeries.visible = true;
                }
                chart.render();
            }
        }
    });

    chart.render();

});
