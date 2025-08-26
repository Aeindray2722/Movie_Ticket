<?php
// session_start();

// Generate CSRF token if not set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieFlow - Book Your Tickets</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/new.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 70px;
            /* your header height */
            background-color: #f2e6f2;
            display: flex;
            /* flex container */
            align-items: center;
            /* vertical centering */
            justify-content: center;
            /* optional, for horizontal center of entire content */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            z-index: 1000;
        }

        .header .container {
            display: flex;
            align-items: center;
            /* vertical center of logo + nav */
            justify-content: space-between;
            /* logo left, nav right */
            height: 100%;
            /* full header height */
        }

        .nav ul {
            display: flex;
            gap: 20px;
            list-style: none;
            margin: 0;
            padding: 0;
            align-items: center;
            /* vertical center nav links */
        }

        .nav a {
            text-decoration: none;
            color: #000;
            line-height: 1;
            /* ensures vertical centering inside li */
        }
    </style>

</head>

<body>
    <header class="header shadow-sm">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="logo text-center">
                Central
            </div>
            <nav class="nav">
                <ul class="d-flex gap-3 list-unstyled mb-0">
                    <li><a href="<?php echo URLROOT; ?>/pages/register">Register</a></li>
                    <li><a href="<?php echo URLROOT; ?>/pages/login">Login</a></li>
                    <li><a href="<?php echo URLROOT; ?>/pages/home">Guest</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="dashboard-content-area d-flex justify-content-center align-items-center"
        style="min-height: calc(100vh - 70px);">
        <div class="background-overlay"></div>
        <div class="register-box shadow-lg">
            <div class="image-side"></div>
            <div class="form-side p-4">
                <h2 class="text-center mt-3 mb-4">Register</h2>
                <form class="login100-form validate-form" name="contactForm" method="POST"
                    action="<?php echo URLROOT; ?>/auth/register">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                    <?php require APPROOT . '/views/components/auth_message.php'; ?>

                    <div class="wrap-input100 validate-input" data-validate="Valid Name is required:">
                        <input class="input100" type="text" id="name" onfocus="this.value=''" name="name"
                            placeholder="Username">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-user" aria-hidden="true"></i>
                        </span>
                    </div>
                    <p class="text-danger">
                        <?php if (isset($data['name-err']))
                            echo $data['name-err']; ?>
                    </p>

                    <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                        <input class="input100" type="email" id="email" name="email" placeholder="Email">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </span>
                    </div>
                    <p class="text-danger">
                        <?php if (isset($data['email-err']))
                            echo $data['email-err']; ?>
                    </p>

                    <div class="wrap-input100 validate-input" data-validate="Valid phone is required: ex@abc.xyz">
                        <input class="input100" type="tel" id="phone" name="phone" placeholder="phone">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-phone" aria-hidden="true"></i>
                        </span>
                    </div>
                    <p class="text-danger">
                        <?php if (isset($data['phone-err']))
                            echo $data['phone-err']; ?>
                    </p>

                    <div class="wrap-input100 validate-input" data-validate="Password is required">
                        <input class="input100" type="password" name="password" placeholder="Password" id="myInput">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="d-flex align-items-center mb-3 mt-1">
                        <input type="checkbox" onclick="myFunction()">
                        <label class="ms-2">Show Password</label>
                    </div>
                    <p class="text-danger">
                        <?php if (isset($data['password-err']))
                            echo $data['password-err']; ?>
                    </p>
                    <div class="d-flex mb-3">
                        <div class="g-recaptcha" data-sitekey="<?= GOOGLE_RECAPTCHA_SITE_KEY ?>"></div>
                    </div>
                    <div class="d-grid gap-2 col-6 mx-auto">
                        <button type="submit" class="btn btn-register w-100" name="btn_register">Register</button>
                    </div>

                    <div class="text-center mt-2 mb-3">
                        Already have an account? <a href="<?php echo URLROOT; ?>/pages/login">Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        // Show Password
        function myFunction() {
            var x = document.getElementById("myInput");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
        $(function () {
            var str = $('name').val();
            if (/^[a-zA-Z- ]*$/.test(str) == false) {
                alert('Your search string contains illegal characters.');
            }
            $("form[name='contactForm']").validate({
                rules: {
                    name: "required",
                    email: "required",
                    password: "required",
                    name: {
                        required: true,
                        minlength: 6,
                        maxlength: 20,
                    },
                    email: {
                        required: true,
                        minlength: 6,
                        maxlength: 50,
                        email: true
                    },
                    password: {
                        minlength: 8,
                        maxlength: 30,
                        required: true,
                    },
                },
                messages: {
                    name: {
                        required: "Please enter your name",
                        minlength: "Name must be min 6 characters long",
                    },
                    email: {
                        required: "Please enter your email",
                        minlength: "Please enter a valid email address",
                    },
                    password: {
                        required: "Please enter your password",
                        minlength: "Password length must be min 8 characters long",
                        maxlength: "Password length must not be more than 30 characters long"
                    },
                },
                submitHandler: function (form) {
                    form.submit();
                }
            });
        });

        $(document).ready(function () {
            $(":input").attr('autocomplete', 'off');
            $(":input").css('box-shadow', 'none');

            $("#name").blur(function () {
                var name = $('#name').val();
                if (name == "") {
                    return;
                }
                var form_url = '<?php echo URLROOT; ?>/auth/formRegister';
                $.ajax({
                    url: form_url,
                    type: 'post',
                    data: {
                        'name': name,
                    },
                    success: function (response) {
                        $("#name_error").remove();
                        $("#name").after("<span id='name_error' class='text-danger'>" + response + "</span>");
                    },
                    error: function (e) {
                        $("#result").html("Something went wrong");
                    }
                });
            });

            $("#email").blur(function () {
                var email = $('#email').val();
                if (email == "") {
                    return;
                }
                var form_url = '<?php echo URLROOT; ?>/auth/formRegister';
                $.ajax({
                    url: form_url,
                    type: 'post',
                    data: {
                        'email': email,
                        'email_check': 1,
                    },
                    success: function (response) {
                        $("#email_error").remove();
                        $("#email").after("<span id='email_error' class='text-danger'>" + response + "</span>");
                    },
                    error: function (e) {
                        $("#result").html("Something went wrong");
                    }
                });
            });

            $("#password").blur(function () {
                var password = $('#password').val();
                if (password == "") {
                    return;
                }
                var form_url = '<?php echo URLROOT; ?>/auth/formRegister';
                $.ajax({
                    url: form_url,
                    type: 'post',
                    data: {
                        'password': password,
                    },
                    success: function (response) {
                        $("#password_error").remove();
                        $("#password").after("<span id='password_error' class='text-danger'>" + response + "</span>");
                    },
                    error: function (e) {
                        $("#result").html("Something went wrong");
                    }
                });
            });

            $("#name").keyup(function () {
                $("#error").remove();
            });
            $("#email").keyup(function () {
                $("#error").remove();
                $("span").remove();
            });
            $("#password").keyup(function () {
                $("#error").remove();
            });
            $("#c_password").keyup(function () {
                $("#error").remove();
            });
        });
    </script>
</body>

</html>