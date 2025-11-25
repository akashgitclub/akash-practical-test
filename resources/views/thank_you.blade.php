<!DOCTYPE html>
<html lang="en">

<head>
    <title>Registration Complete</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #43cea2, #185a9d);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .card-box {
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .success-icon {
            font-size: 60px;
            color: #28a745;
        }
    </style>
</head>

<body>

    <div class="card-box">
        <div class="success-icon">✅</div>

        <h2 class="mt-3">Registration Successful!</h2>
        <p class="text-muted">Your account has been verified successfully.</p>

        <a href="{{ url('/') }}" class="btn btn-primary mt-3 w-100">Go to Home</a>
        <a href="{{ url('/login') }}" class="btn btn-outline-dark mt-2 w-100">Login Now</a>
    </div>

</body>

</html>
