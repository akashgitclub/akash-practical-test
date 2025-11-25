<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your OTP Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9fafb;
            color: #333;
            padding: 20px;
        }

        .container {
            max-width: 500px;
            margin: auto;
            background: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            font-size: 20px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 20px;
        }

        .otp-box {
            background: #f3f4f6;
            border: 2px dashed #2563eb;
            border-radius: 6px;
            padding: 20px;
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            color: #1d4ed8;
            letter-spacing: 6px;
        }

        .footer {
            margin-top: 25px;
            font-size: 13px;
            color: #6b7280;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">Hello,</div>
        <p>Use the OTP below to complete your registration:</p>

        <div class="otp-box">
            {{ $otp }}
        </div>

        <p>This OTP is valid for <strong>5 minute</strong>. Please do not share it with anyone.</p>

        <div class="footer">
            &copy; {{ date('Y') }} Akash Test. All rights reserved.
        </div>
    </div>
</body>

</html>
