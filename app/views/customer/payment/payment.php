<?php
require_once __DIR__ . '/../layout/nav.php';
?>

<section class="payment-page-content py-4">
    <div class="container">
        <h2 class="mb-4">Payment</h2>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card payment-method-card h-100">
                    <div class="card-body">
                        <h3 class="card-title payment-method-title mb-4">Payment Method</h3>
                        <?php foreach ($data['payments'] as $payment): ?>
                            <div class="payment-method-item mb-3">
                                <h5 class="method-name mb-3" style="color: black">
                                    <?= htmlspecialchars($payment['payment_method']) ?> (Name:
                                    <?= htmlspecialchars($payment['account_name']) ?>)
                                </h5>
                                <h5 class="method-number " style="color:black">
                                    <?= htmlspecialchars($payment['account_number']) ?>
                                </h5>
                            </div>
                            <hr>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card payment-info-card h-100">
                    <div class="card-body">
                        <h3 class="card-title payment-info-title mb-4">Payment Info</h3>
                        <form method="POST" enctype="multipart/form-data" action="<?= URLROOT ?>/payment/storePayment">
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="userName" class="form-label visually-hidden">User name</label>
                                        <input type="text" name="name" class="form-control " id="userName" placeholder="User name"
                                            value="<?php echo htmlspecialchars($data['users']['name']); ?>" disabled>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="emailAddress" class="form-label visually-hidden">Email
                                            address</label>
                                        <input type="email" name="email" class="form-control" id="emailAddress"
                                            placeholder="Email address"
                                            value="<?php echo htmlspecialchars($data['users']['email']); ?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="choosePayment" class="form-label visually-hidden">Choose
                                            Payment</label>
                                        <select class="form-select" id="choosePayment" name="payment_method" required>
                                            <option selected disabled>Choose Payment</option>
                                            <?php foreach ($data['payments'] as $payment): ?>
                                                <option value="<?= htmlspecialchars($payment['payment_method']) ?>">
                                                    <?= htmlspecialchars($payment['payment_method']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="chooseFile" class="form-label visually-hidden">Choose file</label>
                                        <input type="file" class="form-control" id="chooseFile" name="payslip_img" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <p class="h5 mt-3">Seats:
                                        <?php echo htmlspecialchars(implode(', ', $data['seat_names'])); ?>
                                    </p>
                                </div>
                                <div class="col">
                                    <p class="h5 mt-3">Total Amount:
                                        <?= htmlspecialchars($data['booking']['total_amount'] ?? '0.00') ?>
                                    </p>
                                </div>

                            </div>

                            <button type="submit" class="btn btn-book-now w-100">Book now</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/../layout/footer.php';
?>