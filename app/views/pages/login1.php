<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Booking Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/login.css" />
</head>

<body>

    <div class="main-dashboard-wrapper">
        <nav class="public-navbar">
            <div class="navbar-left">
                <h3 class="logo-text">Minglarpr</h3>
            </div>
            <div class="navbar-right">
                <ul class="nav-links">
                    <li><a  href="<?php echo URLROOT; ?>/pages/register">Register</a></li>
                    <li><a href="<?php echo URLROOT; ?>/pages/login">Login</a></li>
                    <li><a href="<?php echo URLROOT; ?>/pages/home">Guest</a></li>
                </ul>
            </div>
        </nav>

        <div class="dashboard-content-area">
            <div class="background-overlay"></div>

            <div class="login-box">
                <div class="image-side"></div>
                <div class="form-side">
                    <h2 class="text-center mb-4">Login</h2>

                    <form method="post" action="<?php echo URLROOT; ?>/auth/login" class="validate-form">
                        <?php require APPROOT . '/views/components/auth_message.php'; ?>

                        <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                            <input type="email" name="email" class="input100" placeholder="Email Address">
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                            </span>
                        </div>

                        <div class="wrap-input100 validate-input" data-validate="Password is required">
                            <input type="password" class="input100" name="password" placeholder="Password">
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="fa fa-lock" aria-hidden="true"></i>
                            </span>
                        </div>

                        <div class=" offset-3">
                            <button type="submit" class="btn btn-register w-50 justify-content-center">Login</button>
                        </div>

                        <div class="mb-3 mt-3 text-center">
                            <div class="row justify-content-center">
                                <div class="col-auto">
                                    <button type="button" class="btn btn-outline-danger">
                                        <i class="fab fa-google me-2"></i> Google
                                    </button>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-outline-primary">
                                        <i class="fab fa-facebook-f me-2"></i> Facebook
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-2">
                            Don't have an account <a href="<?php echo URLROOT; ?>/pages/register">Register</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php require_once APPROOT . '/views/inc/footer.php'; ?>

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

    </script>

</body>

</html>