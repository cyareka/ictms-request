<!DOCTYPE html>
<html>
<head>
    <title>Vehicle Request Approved</title>
</head>
<body>
    <h1>We’re pleased to inform you that the vehicle request has been approved!</h1>
    <p>Dear {{ $vehicleRequest->RequesterName }},</p>
    <p>You have an upcoming trip to {{ $vehicleRequest->Destination }} that will occur on {{ $vehicleRequest->date_start }} at {{ $vehicleRequest->time_start }}.</p>
    <br>
    <p>Thank you.</p>
</body>
</html>