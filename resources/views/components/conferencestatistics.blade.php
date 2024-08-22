<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <style>
        .chart-container {
            width: 90%;
            margin: 20px auto;
        }
        @media (max-width: 768px) {
            .chart-container {
                width: 90%;
                margin: 10px auto;
            }
        }
    </style>
</head>
<body>
<section id="content">
    <main>
        <ul class="box-info">
            <li>
                <i class='bx bxs-group'></i>
                <span class="text">
                        <h3>1020</h3>
                        <p>Pending Requests</p>
                    </span>
            </li>
            <li>
                <i class='bx bxs-group'></i>
                <span class="text">
                        <h3>2834</h3>
                        <p>Daily Requests</p>
                    </span>
            </li>
            <li>
                <i class='bx bxs-group'></i>
                <span class="text">
                        <h3>20543</h3>
                        <p>Monthly Requests</p>
                    </span>
            </li>
        </ul>
    </main>
</section>
<div class="bar-chart-wrapper">
    <h1>Total Requests per Office</h1>
    <div class="simple-bar-chart">
        <div class="item" style="--clr: #5EB344; --val: 80">
            <div class="label">RRCY</div>
            <div class="value">80%</div>
        </div>
        <div class="item" style="--clr: #FCB72A; --val: 50">
            <div class="label">PPD</div>
            <div class="value">50%</div>
        </div>
        <div class="item" style="--clr: #F8821A; --val: 100">
            <div class="label">ICTMS</div>
            <div class="value">100%</div>
        </div>
        <div class="item" style="--clr: #E0393E; --val: 15">
            <div class="label">HR</div>
            <div class="value">15%</div>
        </div>
        <div class="item" style="--clr: #963D97; --val: 1">
            <div class="label">AICS</div>
            <div class="value">1%</div>
        </div>
        <div class="item" style="--clr: #069CDB; --val: 90">
            <div class="label">CASH</div>
            <div class="value">90%</div>
        </div>
        <div class="item" style="--clr: #37766B; --val: 66">
            <div class="label">SOCPEN</div>
            <div class="value">66%</div>
        </div>
        <div class="item" style="--clr: #6234CE; --val: 60">
            <div class="label">DRRMD</div>
            <div class="value">60%</div>
        </div>
    </div>
</div>

<!--Line Chart Container -->
<script type="text/javascript">
    window.onload = function () {
        var chart = new CanvasJS.Chart("chartContainer",
            {
                backgroundColor: "#F2F2F2",
                title:{
                    text: "Usage Comparison: MAGITING & MAAGAP",
                    fontFamily: "Arial",
                    fontColor: "#000000",
                    padding: 25
                },
                axisX: {
                    valueFormatString: "MMM",
                    interval: 1,
                    intervalType: "month"
                },
                axisY: {
                    title: "Number of Events",
                    includeZero: false
                },
                legend: {
                    cursor: "pointer",
                    verticalAlign: "top",
                    horizontalAlign: "center",
                    dockInsidePlotArea: true
                },
                data: [
                    {
                        type: "line",
                        name: "MAGITING Conference",
                        showInLegend: true,
                        dataPoints: [
                            { x: new Date(2024, 0, 1), y: 300 },
                            { x: new Date(2024, 1, 1), y: 340 },
                            { x: new Date(2024, 2, 1), y: 320 },
                            { x: new Date(2024, 3, 1), y: 280 },
                            { x: new Date(2024, 4, 1), y: 290 },
                            { x: new Date(2024, 5, 1), y: 310 },
                            { x: new Date(2024, 6, 1), y: 330 },
                            { x: new Date(2024, 7, 1), y: 350 },
                            { x: new Date(2024, 8, 1), y: 370 },
                            { x: new Date(2024, 9, 1), y: 360 },
                            { x: new Date(2024, 10, 1), y: 380 },
                            { x: new Date(2024, 11, 1), y: 400 }
                        ]
                    },
                    {
                        type: "line",
                        name: "MAAGAP Conference",
                        showInLegend: true,
                        dataPoints: [
                            { x: new Date(2024, 0, 1), y: 450 },
                            { x: new Date(2024, 1, 1), y: 414 },
                            { x: new Date(2024, 2, 1), y: 520, indexLabel: "highest", markerColor: "red", markerType: "triangle" },
                            { x: new Date(2024, 3, 1), y: 460 },
                            { x: new Date(2024, 4, 1), y: 450 },
                            { x: new Date(2024, 5, 1), y: 1000 },
                            { x: new Date(2024, 6, 1), y: 480 },
                            { x: new Date(2024, 7, 1), y: 480 },
                            { x: new Date(2024, 8, 1), y: 410, indexLabel: "lowest", markerColor: "DarkSlateGrey", markerType: "cross" },
                            { x: new Date(2024, 9, 1), y: 500 },
                            { x: new Date(2024, 10, 1), y: 480 },
                            { x: new Date(2024, 11, 1), y: 510 }
                        ]
                    }
                ]
            });

        chart.render();
    }
</script>
<script type="text/javascript" src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</head>
<body>
<div id="chartContainer" style="height: 500px; width: 100%;"></div>
</body>
</html>
