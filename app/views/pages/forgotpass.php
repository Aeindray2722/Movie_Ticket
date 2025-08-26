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
    height: 70px; /* your header height */
    background-color: #f2e6f2;
    display: flex;              /* flex container */
    align-items: center;        /* vertical centering */
    justify-content: center;    /* optional, for horizontal center of entire content */
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    z-index: 1000;
}

.header .container {
    display: flex;
    align-items: center;       /* vertical center of logo + nav */
    justify-content: space-between; /* logo left, nav right */
    height: 100%;              /* full header height */
}

.nav ul {
    display: flex;
    gap: 20px;
    list-style: none;
    margin: 0;
    padding: 0;
    align-items: center; /* vertical center nav links */
}

.nav a {
    text-decoration: none;
    color: #000;
    line-height: 1; /* ensures vertical centering inside li */
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
    <div class="dashboard-content-area">
        <div class="background-overlay"></div>

        <div class="login-box">
            <div class="image-side"></div>
            <div class="form-side">
                <h2 class="text-center mb-4">Forgot Password!</h2>
                <form method="post" action="<?php echo URLROOT; ?>/auth/sendResetLink">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <?php require APPROOT . '/views/components/auth_message.php'; ?>

                    <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                        <input type="email" name="email" class="input100" placeholder="Enter your Email">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="row mt-4 offset-1">
                        <div class="col-5 text-center ">
                            <button type="submit" class="btn btn-register w-50 ">Send</button>
                        </div>
                        <div class="col-3 text-center ">
                            <a href="<?php echo URLROOT; ?>/pages/login" class="btn btn-register ">Cancel</a>
                        </div>
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