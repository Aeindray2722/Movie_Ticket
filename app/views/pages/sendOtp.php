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
        .verification-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }

        .verification-subtitle {
            font-size: 1rem;
            color: #666;
            margin-bottom: 30px;
        }

        .otp-input-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .otp-input {
            width: 45px;
            height: 45px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 600;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
            color: #333;
        }

        .otp-input:focus {
            border-color: #b57d84;
            box-shadow: 0 0 0 0.25rem rgba(181, 125, 132, 0.25);
        }

        .resend-text {
            font-size: 0.9rem;
            color: #999;
            margin-top: 20px;
        }

        .resend-text a {
            color: #b57d84;
            font-weight: 600;
            text-decoration: none;
        }

        .resend-text a:hover {
            text-decoration: underline;
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

                <h1 class="verification-title ">Verification</h1>
                <p class="verification-subtitle">Enter OTP code sent to your email</p>
                <form method="post" action="<?php echo URLROOT; ?>/auth/verifyOtp">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <?php require APPROOT . '/views/components/auth_message.php'; ?>

                    <div class="otp-input-group mb-3">
                        <input type="text" name="digit1" class="form-control otp-input" maxlength="1">
                        <input type="text" name="digit2" class="form-control otp-input" maxlength="1">
                        <input type="text" name="digit3" class="form-control otp-input" maxlength="1">
                        <input type="text" name="digit4" class="form-control otp-input" maxlength="1">
                    </div>
                    <button type="submit" class="btn btn-register w-50 justify-content-center">Continue</button>
                </form>
                <div class="resend-text">
                    The code will expire in <span id="timer">60</span>s.
                    <a href="<?php echo URLROOT; ?>/auth/resendOtp" id="resend-link">Send again</a>
                </div>

            </div>
        </div>
    </div>
    </div>
    <?php require_once APPROOT . '/views/inc/footer.php'; ?>
    <script>
        let timeLeft = 60;
        const timerEl = document.getElementById('timer');
        const resendLink = document.getElementById('resend-link');

        const countdown = setInterval(() => {
            timeLeft--;
            timerEl.textContent = timeLeft;
            if (timeLeft <= 0) {
                clearInterval(countdown);
                resendLink.style.pointerEvents = 'auto';
                resendLink.style.opacity = 1;
            }
        }, 1000);

        // Disable link until timer ends
        resendLink.style.pointerEvents = 'none';
        resendLink.style.opacity = 0.5;
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const inputs = document.querySelectorAll('.otp-input');
            inputs.forEach((input, index) => {
                input.addEventListener('keyup', (event) => {
                    if (event.key >= '0' && event.key <= '9' && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    } else if (event.key === 'Backspace' && index > 0) {
                        if (input.value === '') {
                            inputs[index - 1].focus();
                        }
                    }
                });
            });
        });
    </script>

</body>

</html>