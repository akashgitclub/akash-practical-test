<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- jQuery Validate -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <!-- Notify -->

    <style>
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-card {
            background: #fff;
            border-radius: 16px;
            padding: 30px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .login-title {
            font-weight: 600;
            text-align: center;
            margin-bottom: 20px;
        }

        .btn-login {
            background: #667eea;
            border: none;
        }

        .btn-login:hover {
            background: #5a6fdc;
        }

        .form-control {
            height: 45px;
        }

        label.error {
            color: crimson;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>

<body>

    <div class="login-card">
        <h3 class="login-title">Login</h3>

        <form id="loginForm">
            @csrf

            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email">
            </div>

            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password">
            </div>

            <button type="submit" id="submit" class="btn btn-login text-white w-100">
                Login
            </button>
        </form>

        <div class="text-center mt-3">
            <small>Don't have an account?
                <a href="{{ url('/') }}">Register</a>
            </small>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.js"
        integrity="sha512-uE2UhqPZkcKyOjeXjPCmYsW9Sudy5Vbv0XwAVnKBamQeasAVAmH6HR9j5Qpy6Itk1cxk+ypFRPeAZwNnEwNuzQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function() {

            // CSRF Token setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#loginForm").validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 6
                    }
                },
                submitHandler: function(form) {

                    $("#submit").html('Processing...');
                    $("#submit").attr('disabled', true);

                    let formData = new FormData(form);

                    $.ajax({
                        url: "{{ route('admin.login') }}",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: "json",
                        success: function(response) {

                            if (response.success == true) {
                                $.notify(response.message, "success");
                                $("#submit").html('Redirecting...');
                                localStorage.setItem('user_id', response.data.user_id);

                                setTimeout(function() {
                                    $("#loginForm")[0].reset();
                                    window.location.href = "/dashboard";
                                }, 1500);

                                resetButton();
                                // setTimeout(function() {
                                //     window.location.replace("/dashboard");
                                // }, 1500);

                            } else {
                                resetButton();
                                $.notify(response.message, "error");
                            }
                        },
                        error: function(xhr) {
                            resetButton();
                            $.notify(xhr.responseJSON?.message ?? "Server error", "error");
                        }
                    });

                    function resetButton() {
                        $("#submit").html('Login');
                        $("#submit").attr('disabled', false);
                    }
                }
            });

        });
    </script>

</body>

</html>
