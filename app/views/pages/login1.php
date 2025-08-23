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
    z-index: 1000;
    background-color: #f2e6f2;
    height: 70px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

/* Push page content below fixed header */
body {
    padding-top: 10px;
}
.login-box {
    display: flex;
    flex-direction: column; /* Stack image and form on small screens */
    width: 90%;
    max-width: 900px;
}
.image-side,
.form-side {
    width: 100%; /* Take full width on small screens */
}
@media (min-width: 768px) {
    .login-box {
        flex-direction: row; /* Side-by-side layout on larger screens */
    }
    .image-side {
        width: 50%;
    }
    .form-side {
        width: 50%;
    }
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
    <div class="dashboard-content-area d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 70px);">
        <div class="background-overlay"></div>
        <div class="login-box shadow-lg">
            <div class="image-side"></div>
            <div class="form-side p-4">
                <h2 class="text-center mt-3 mb-4">Login</h2>
                <form method="post" action="<?php echo URLROOT; ?>/auth/login" class="validate-form">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                    <?php require APPROOT . '/views/components/auth_message.php'; ?>

                    <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                        <input type="email" name="email" class="input100 mb-3" placeholder="Email Address">
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
                    <div class="text-end mt-1 mb-2">
                        <a href="<?php echo URLROOT; ?>/auth/forgotPassword">Forgot Password?</a>
                    </div>

                    <div class="d-grid gap-2 col-6 mx-auto">
                        <button type="submit" class="btn btn-register w-100">Login</button>
                    </div>

                    <div class="mt-3 text-center">
                        <!-- <p class="mb-2">Or log in with:</p> -->
                        <div class="d-flex justify-content-center gap-2">
                            <a href="<?php echo URLROOT; ?>/auth/googleLogin" class="btn btn-outline-danger">
                                <i class="fab fa-google me-2"></i> Google
                            </a>
                            <a href="<?php echo URLROOT; ?>/auth/githubLogin" class="btn btn-outline-dark">
                                <i class="fa-brands fa-github me-2"></i> GitHub
                            </a>
                        </div>
                    </div>

                    <div class="text-center mt-2 mb-3">
                        Don't have an account <a href="<?php echo URLROOT; ?>/pages/register">Register</a>
                    </div>
                    <div class="g-recaptcha d-flex justify-content-center" data-sitekey="<?= GOOGLE_RECAPTCHA_SITE_KEY ?>"></div>
                </form>
            </div>
        </div>
    </div>
    </div>
    <?php require_once APPROOT . '/views/inc/footer.php'; ?>
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
    </script>
</body>
</html>