<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .box {
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .btn {
            display: block;
            width: 250px;
            margin: 10px auto;
            padding: 12px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 16px;
            color: white;
        }
        .admin { background: #2563eb; }
        .customer { background: #16a34a; }
        .login { background: #9333ea; }
    </style>
</head>
<body>

<div class="box">
    <h2>Welcome Page</h2>

    <a href="{{ route('admin.register') }}" class="btn admin">Admin Register</a>

    <a href="{{ route('customer.register') }}" class="btn customer">Customer Register</a>

    <a href="{{ route('login') }}" class="btn login">Login</a>
</div>

</body>
</html>
