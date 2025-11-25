<!DOCTYPE html>
<html>

<head>
    <title>OTP Verification</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

        .otp-container {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 340px;
        }

        .otp-inputs {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }

        .otp-box {
            width: 45px;
            height: 55px;
            border: 1px solid #ccc;
            border-radius: 8px;
            text-align: center;
            font-size: 22px;
            font-weight: bold;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            background: #4f46e5;
            color: white;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
        }

        button:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }
    </style>

</head>

<body>

    <div class="otp-container">
        <h2>OTP Verification</h2>

        <form id="validateForm">
            @csrf

            <div class="otp-inputs">
                <input type="text" maxlength="1" class="otp-box" />
                <input type="text" maxlength="1" class="otp-box" />
                <input type="text" maxlength="1" class="otp-box" />
                <input type="text" maxlength="1" class="otp-box" />
                <input type="text" maxlength="1" class="otp-box" />
                <input type="text" maxlength="1" class="otp-box" />
            </div>

            <input type="hidden" name="otp" id="otp">

            <button type="submit" id="submit" disabled>Verify OTP</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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

            let boxes = document.querySelectorAll(".otp-box");

            boxes.forEach((box, index) => {
                box.addEventListener("keyup", function(e) {
                    if (this.value.length === 1 && index < boxes.length - 1) {
                        boxes[index + 1].focus();
                    }

                    if (e.key === "Backspace" && index > 0) {
                        boxes[index - 1].focus();
                    }

                    checkAllFilled();
                });
            });

            function checkAllFilled() {
                let otp = "";
                let allFilled = true;

                boxes.forEach(box => {
                    if (box.value === "") allFilled = false;
                    otp += box.value;
                });

                $("#otp").val(otp);

                $("#submit").prop("disabled", !allFilled);
            }

            $("#validateForm").submit(function(e) {
                e.preventDefault();

                $("#submit").html('Verifying...').attr('disabled', true);

                let user_id = localStorage.getItem('user_id');
                let role = localStorage.getItem('role');

                let formData = new FormData(this);
                formData.append('user_id', user_id);
                formData.append('role', role);

                $.ajax({
                    url: "{{ route('otp.verify') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",

                    success: function(response) {
                        if (response.success) {
                            $.notify(response.message, "success");
                            setTimeout(function() {
                                window.location.href = "/thank-you";
                            }, 1500);
                             $("#validateForm")[0].reset();
                        } else {
                            $("#submit").html('Verify OTP').attr('disabled', false);
                            $.notify(response.message, "error");
                        }
                    },

                    error: function(xhr) {
                        $("#submit").html('Verify OTP').attr('disabled', false);
                        $.notify(xhr.responseJSON.message, "error");
                    }
                });
            });

        });
    </script>

</body>

</html>
