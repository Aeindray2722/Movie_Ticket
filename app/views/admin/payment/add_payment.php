<?php

$payment_methods = [
    [
        'id' => 1,
        'payment_method' => 'Kpay',
        'account_name' => 'Aeindray',
        'account_number' => '09-987545553'
    ],

];
?>
     <?php
    require_once __DIR__ . '/../layout/sidebar.php';
    ?>

    <div class="payment-content-wrapper">
          <?php
            require_once __DIR__ . '/../layout/nav.php';
            ?>
        <div class="payment-content-section">
            <div class="payment-form-card">
                <h4>Add Payment</h4>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="payment_type" name="payment_method" placeholder="Payment Method" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" id="payment_type" name="account_name" placeholder="Account Name" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" id="account_no" name="account_number" placeholder="Account Number" required>
                    </div>
                    <div class="d-grid movie-button">
                        <button type="submit" class="btn btn-add-movie">Add</button>
                    </div>
                </form>
            </div>

            <div class="table-container">
                <div class="table-responsive">
                    <h4>Payment</h4>
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>Payment Method</th>
                                <th>Account Name</th>
                                <th>Account Number</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($payment_methods)): ?>
                                <tr>
                                    <td colspan="3" class="text-center">No payment methods found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($payment_methods as $payment): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($payment['payment_method']); ?></td>
                                        <td><?php echo htmlspecialchars($payment['account_name']); ?></td>
                                        <td><?php echo htmlspecialchars($payment['account_number']); ?></td>
                                        <td class="d-flex justify-content-center">
                                            <a href="edit_payment.php?id=<?php echo $payment['id']; ?>" class="btn btn-sm btn-outline-secondary me-1" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="delete_payment.php?id=<?php echo $payment['id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this payment method?');">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
