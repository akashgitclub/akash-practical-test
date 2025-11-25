<!DOCTYPE html>
<html>

<head>
    <title>Customer Register</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #6366f1, #ec4899);
        }

        .card {
            width: 100%;
            max-width: 380px;
            background: #ffffff;
            border-radius: 16px;
            padding: 30px 25px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.7s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card h2 {
            text-align: center;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .subtitle {
            text-align: center;
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        input {
            width: 100%;
            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            font-size: 14px;
            transition: 0.2s ease;
        }

        input:focus {
            border-color: #6366f1;
            outline: none;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }

        label.error {
            font-size: 12px;
            color: #ef4444;
            margin-top: 4px;
            display: block;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #4f46e5, #ec4899);
            color: white;
            font-size: 15px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: 0.2s ease;
            margin-top: 10px;
        }

        .btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .bottom-text {
            text-align: center;
            margin-top: 18px;
            font-size: 13px;
            color: #6b7280;
        }

        .bottom-text a {
            color: #6366f1;
            font-weight: 500;
            text-decoration: none;
        }

        .bottom-text a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="card">
        <h2>Customer Registration</h2>
        <p class="subtitle">Create your account</p>

        <form id="validateForm">
            @csrf

            <div class="form-group">
                <input type="text" name="first_name" placeholder="First Name">
            </div>

            <div class="form-group">
                <input type="text" name="last_name" placeholder="Last Name">
            </div>

            <div class="form-group">
                <input type="email" name="email" placeholder="Email Address">
            </div>

            <div class="form-group">
                <input type="password" name="password" placeholder="Password">
            </div>

            <input type="hidden" name="role" value="customer">

            <button type="submit" id="submit" class="btn-submit">Register</button>
        </form>

        <div class="bottom-text">
            Already have an account?
            <a href="/login">Login</a>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- jQuery Validate -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <!-- Notify -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.js"
        integrity="sha512-uE2UhqPZkcKyOjeXjPCmYsW9Sudy5Vbv0XwAVnKBamQeasAVAmH6HR9j5Qpy6Itk1cxk+ypFRPeAZwNnEwNuzQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#validateForm").validate({
                rules: {
                    first_name: {
                        required: true
                    },
                    last_name: {
                        required: true
                    },
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
                    $("#submit").prop('disabled', true);

                    var formData = new FormData(form);

                    $.ajax({
                        url: "{{ route('register.store') }}",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: "json",

                        success: function(response) {
                            if (response.success == true) {
                                $.notify(response.message, "success");

                                localStorage.setItem('user_id', response.data.user_id);
                                localStorage.setItem('role', response.data.role);

                                setTimeout(function() {
                                    window.location.replace("/verify-otp");
                                }, 1000);
                                  $("#validateForm")[0].reset();
                            } else {
                                resetButton();
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    $.notify(xhr.responseJSON.message, "error");
                                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                                    let firstError = Object.values(xhr.responseJSON.errors)[
                                        0][
                                        0
                                    ];
                                    $.notify(firstError, "error");
                                } else {
                                    $.notify("Something went wrong!", "error");
                                }
                            }
                        },

                        error: function(xhr) {
                            resetButton();
                            $.notify("Something went wrong!", "error");
                        }
                    });

                    function resetButton() {
                        $("#submit").html('Register');
                        $("#submit").prop('disabled', false);
                    }
                }
            });

        });
    </script>

</body>

</html>
