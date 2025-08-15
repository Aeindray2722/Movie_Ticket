<?php
// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Showcase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/customer.css" />

</head>

<body>
    <footer class="footer py-2 text-white">
    <div class="container">
        <div class="row mt-4">
            <div class="col-md-6 meet-us-col">
                <h4>Meet us</h4>
                <p>Phone No : 0998765302</p>
                <p>Email : aeindray@gmail.com</p>
                <p>Location : Yangon</p>
            </div>
            <div class="col-md-6 contact-us-col">
                <h4>Contact us</h4>
                <form method="POST" action="<?php echo URLROOT; ?>/contact/store">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <div class="mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Your Email" required>
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control" rows="3" name="contact_text" placeholder="Your Message" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-send">Send</button>
                </form>

                <div class="social-icons mt-3">
                    <a href="#" class="me-2 text-primary"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="text-danger me-2"><i class="fa-brands fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </div>
</footer>
    <?php require_once APPROOT . '/views/inc/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>


</body>

</html>
<?php if (isset($_SESSION['success'])): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '<?= $_SESSION['success'] ?>',
            timer: 2000,
            showConfirmButton: false
        });
    </script>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>