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
    <title>Vehicle Statistics</title>
</head>
<body>
<section id="content">
    <main>
        <ul class="box-info">
            <li>
                <i class="bi bi-truck-front-fill custom-size"></i>
                <span class="text">
                    <h3 id="pending-requests">0</h3>
                    <p>Pending Requests</p>
                </span>
            </li>
            <li>
                <i class="bi bi-truck-front-fill custom-size"></i>
                <span class="text">
                    <h3 id="daily-requests">0</h3>
                    <p>Today's Requests</p>
                </span>
            </li>
            <li>
                <i class="bi bi-truck-front-fill custom-size"></i>
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
    <div class="simple-bar-chart"></div>
</div>


<br>

<div class="bar-chart-wrapper">
    <h1 style="text-align: center;">Total Cancelled Requests for Offices</h1>
    <div class="simple-bar-chart" id="cancelled-vehicle-requests-chart"></div>
</div>

<br>


<!-- Adjust the size of the pie chart -->
<div class="chart-container">
    <div id="chartContainer1" style="height: 500px; width: 100%;"></div> <!-- Increased height -->
</div>

<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function fetchStatistics() {
            fetch('/api/vehicle-statistics')
                .then(response => response.json())
                .then(data => {
                    // Update the counts in the UI
                    document.getElementById('pending-requests').textContent = data.pendingRequests;
                    document.getElementById('daily-requests').textContent = data.dailyRequests;
                    document.getElementById('monthly-requests').textContent = data.monthlyRequests;

                    const requestsPerOfficeContainer = document.querySelector('.simple-bar-chart');
                    const cancelledVehicleRequestsContainer = document.getElementById('cancelled-vehicle-requests-chart');

                    requestsPerOfficeContainer.innerHTML = ''; // Clear existing items
                    cancelledVehicleRequestsContainer.innerHTML = ''; // Clear cancelled vehicle requests

                    // Array of colors
                    const colors = ['#5EB344', '#e1e81a', '#F8821A', '#E0393E', '#963D97', '#fa5f83', '#069CDB', '#014D4E'];

                    // Display requests per office
                    data.requestsPerOffice.forEach((office, index) => {
                        const item = document.createElement('div');
                        item.className = 'item';
                        const color = colors[index % colors.length];
                        item.style.setProperty('--clr', color);
                        item.style.setProperty('--val', office.total);

                        const label = document.createElement('div');
                        label.className = 'label';
                        label.textContent = office.office;

                        const value = document.createElement('div');
                        value.className = 'value';
                        value.textContent = `${office.total}`;

                        item.appendChild(label);
                        item.appendChild(value);
                        requestsPerOfficeContainer.appendChild(item);
                    });

                    // Display cancelled requests per office
                    data.cancelledPerOffice.forEach((office, index) => {
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
                        cancelledVehicleRequestsContainer.appendChild(item);
                    });
                })
                .catch(error => console.error('Error fetching statistics:', error));
        }

        function getRandomColor() {
            const colors = ['#5EB344', '#FCB72A', '#F8821A', '#E0393E', '#963D97', '#069CDB', '#6234CE', '#06934A'];
            return colors[Math.floor(Math.random() * colors.length)];
        }

        function fetchVehicleTypeUsage() {
            fetch('/api/vehicle-usage')
                .then(response => response.json())
                .then(data => {
                    var dataPoints = data.dataPoints;
                    console.log("dataPoints:", dataPoints);

                    var chart1 = new CanvasJS.Chart("chartContainer1", {
                        backgroundColor: "#F2F2F2",
                        animationEnabled: true,
                        title: {
                            text: "Vehicle Type Usage"
                        },
                        data: [{
                            type: "pie",
                            startAngle: 240,
                            yValueFormatString: "##0.00\"%\"",
                            indexLabel: "{label} {y}",
                            dataPoints: dataPoints
                        }]
                    });

                    console.log("Chart configuration:", chart1.options);
                    chart1.render();
                    console.log("Chart rendered successfully");

                })
                .catch(error => console.error('Error fetching vehicle usage:', error));
        }

        fetchStatistics();
        fetchVehicleTypeUsage();
    });
</script>
</body>
</html>
