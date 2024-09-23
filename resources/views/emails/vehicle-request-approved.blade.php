<!DOCTYPE html>
<html>
<head>
    <title>Vehicle Request Approved</title>
</head>
<body>
    <h1>Your Vehicle Request Has Been Approved</h1>
    <p>Dear {{ $vehicleRequest->RequesterName }},</p>
    <p>Your vehicle request has been approved.</p>
    <br>
    <p>Event Date: {{ $vehicleRequest->date_start }} - {{ $vehicleRequest->date_end }}</p>
    <p>Destination: {{ $vehicleRequest->Destination }}</p>
    <p>Call Time: {{ $vehicleRequest->time_start }}</p>
    <br>
    <p>Thank you.</p>
</body>
</html>