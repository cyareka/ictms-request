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
        .custom-size {
            font-size: 30px; /* Adjust to any size you want */
          }


        @media (max-width: 768px) {
            .chart-container {
                width: 90%;
                margin: 10px auto;
            }
            .custom-size {
            font-size: 30px; /* Adjust to any size you want */
          }

        }
    </style>
</head>
<body>
<section id="content">
    <main>
        <ul class="box-info">
            <li>
                <i class="bi bi-building custom-size"></i>
                <span class="text">
                    <h3 id="pending-requests">0</h3>
                    <p>Pending Requests</p>
                </span>
            </li>
            <li>
                <i class="bi bi-building custom-size"></i>
                <span class="text">
                    <h3 id="daily-requests">0</h3>
                    <p>Today's Requests </p>
                </span>
            </li>
            <li>
                <i class="bi bi-building custom-size"></i>
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

<div id="conferenceUsageChart" style="height: 400px; width: 91%; border: 2px solid black; margin-left: 55px;"></div>

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
                bar.style.height = `${office.total}`; // Adjust the height based on the total percentage

                const value = document.createElement('div');
                value.className = 'value';
                value.textContent = `${office.total}`;

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

document.addEventListener('DOMContentLoaded', function () {
    Promise.all([
        fetch('/api/conference-statistics').then(response => response.json()),
        fetch('/api/conference-room-usage').then(response => response.json())
    ]).then(([statisticsData, usageData]) => {
        console.log('Statistics Data:', statisticsData);
        console.log('Usage Data:', usageData);

        // ... (rest of your existing code for updating UI elements)

        // Conference room usage chart
        if (!usageData || !usageData.magitingUsage || !usageData.maagapUsage) {
            console.error('Usage data is not in the expected format:', usageData);
            return;
        }

        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        const magitingData = months.map((month, index) => ({
            x: index,
            y: usageData.magitingUsage[index + 1] || 0
        }));

        const maagapData = months.map((month, index) => ({
            x: index,
            y: usageData.maagapUsage[index + 1] || 0
        }));

        console.log('Magiting Data:', magitingData);
        console.log('Maagap Data:', maagapData);

        var chart = new CanvasJS.Chart("conferenceUsageChart", {
            animationEnabled: true,
            title: {
                text: "Usage Comparison: MAGITING & MAAGAP"
            },
            axisX: {
        title: "Month",
        intervalType: "month",
        interval: 1,
        labelFormatter: function(e) {
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            return months[e.value];
        },
        labelAngle: -45
    },
            axisY: {
                title: "Number of Events",
                includeZero: false
            },
            legend: {
                cursor: "pointer",
                itemclick: toggleDataSeries
            },
            data: [{
                type: "line",
                name: "MAGITING Conference",
                showInLegend: true,
                dataPoints: magitingData
            },
            {
                type: "line",
                name: "MAAGAP Conference",
                showInLegend: true,
                dataPoints: maagapData
            }]
            
        });

        try {
            chart.render();
            console.log('Chart rendered successfully');
        } catch (error) {
            console.error('Error rendering chart:', error);
        }

        function toggleDataSeries(e) {
            if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                e.dataSeries.visible = false;
            } else {
                e.dataSeries.visible = true;
            }
            chart.render();
        }
    }).catch(error => console.error('Error fetching data:', error));
});

</script>


  <script type="text/javascript" src="https://cdn.canvasjs.com/canvasjs.min.js"></script>

  <div id="chartContainer" style="height: 500px; width: 100%;"></div>
   </body>
</html>