<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="">
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
                document.getElementById('pending-requests').textContent = data.pendingRequests;
                document.getElementById('daily-requests').textContent = data.dailyRequests;
                document.getElementById('monthly-requests').textContent = data.monthlyRequests;

                const requestsPerOfficeContainer = document.getElementById('requests-per-office');
                data.requestsPerOffice.forEach(office => {
                    const item = document.createElement('div');
                    item.className = 'item';
                    item.style.setProperty('--clr', '#5EB344'); // You can set different colors dynamically
                    item.style.setProperty('--val', office.total);

                    const label = document.createElement('div');
                    label.className = 'label';
                    label.textContent = office.office;

                    const value = document.createElement('div');
                    value.className = 'value';
                    value.textContent = `${office.total}%`;

                    item.appendChild(label);
                    item.appendChild(value);
                    requestsPerOfficeContainer.appendChild(item);
                });
            });
    });
</script>
</body>
</html>
