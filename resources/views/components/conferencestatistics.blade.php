<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .chart-container {
            width: 90%;
            margin: 20px auto;
        }
        .item {
            position: relative;
            
        }
        .label {
            margin-top: 10px;
            text-align: center;
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
                    <h3 id="pending-requests">0</h3>
                    <p>Pending Requests</p>
                </span>
            </li>
            <li>
                <i class='bx bxs-group'></i>
                <span class="text">
                    <h3 id="daily-requests">0</h3>
                    <p>Daily Requests</p>
                </span>
            </li>
            <li>
                <i class='bx bxs-group'></i>
                <span class="text">
                    <h3 id="monthly-requests">0</h3>
                    <p>Monthly Requests</p>
                </span>
            </li>
        </ul>
    </main>
</section>
<div class="bar-chart-wrapper">
    <h1>Total Requests per Office</h1>
    <div class="simple-bar-chart" id="requests-per-office">
        <!-- Dynamic content will be inserted here -->
    </div>
</div>

<script type="text/javascript">
      document.addEventListener('DOMContentLoaded', function () {
    fetch('/api/conference-statistics')
        .then(response => response.json())
        .then(data => {
            // Update the counts in the UI
            document.getElementById('pending-requests').textContent = data.pendingRequests;
            document.getElementById('daily-requests').textContent = data.dailyRequests;
            document.getElementById('monthly-requests').textContent = data.monthlyRequests;

            const requestsPerOfficeContainer = document.getElementById('requests-per-office');

            // Array of colors
            const colors = ['#5EB344', '#e1e81a', '#F8821A', '#E0393E', '#963D97', '#fa5f83', '#069CDB', '#014D4E'];

            data.requestsPerOffice.forEach((office, index) => {
                const item = document.createElement('div');
                item.className = 'item';

                // Set the color dynamically from the colors array
                const color = colors[index % colors.length]; // Cycle through colors if needed
                item.style.setProperty('--clr', color);
                item.style.setProperty('--val', office.total);

                const bar = document.createElement('div');
                bar.className = 'bar';
                bar.style.backgroundColor = color;
                bar.style.height = `${office.total}%`; // Adjust the height based on the total percentage

                const value = document.createElement('div');
                value.className = 'value';
                value.textContent = `${office.total}%`;

                const label = document.createElement('div');
                label.className = 'label';
                label.textContent = office.office; // Use the office name as the label

                item.appendChild(bar);
                item.appendChild(value);
                item.appendChild(label);
                requestsPerOfficeContainer.appendChild(item);
            });
        });
});

</script>

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

  <div id="chartContainer" style="height: 500px; width: 100%;"></div>
   </body>
</html>
