<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Login OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            padding: 20px;
            text-align: center;
        }
        .logo {
            margin-bottom: 20px;
        }
        .headerText {
            font-size: 24px;
            font-weight: bold;
            color: #333333;
            margin-bottom: 10px;
        }
        .bodyText {
            font-size: 16px;
            color: #666666;
            margin-bottom: 30px;
        }
        .otp {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 10px;
            color: #333333;
            margin-bottom: 30px;
        }
        .infoText {
            font-size: 14px;
            color: #666666;
            margin-bottom: 20px;
        }
        .footerText {
            font-size: 12px;
            color: #999999;
        }
    </style>
</head>
<body>
<div class="email-container">
    <img src='https://i.imgur.com/YVsL4gX.jpeg' alt='Logo' style='max-width: 300px; margin-bottom: 20px;' />
    <div class="headerText">Let's log you in</div>
    <div class="bodyText">Use this code to log in. This code will expire in 10 minutes.</div>
    <div class="otp">{{ $otp }}</div>
    <div class="footerText">If you didn't request this email, you can safely ignore it.</div>
</div>
</body>
</html>
