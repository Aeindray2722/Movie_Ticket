<?php
    require_once __DIR__ . '/../layout/nav.php';
?>

<section class="payment-page-content py-4">
    <div class="container">
        <div class="mb-3">
            <a href="<?php echo URLROOT; ?>/booking/booking" class="btn btn-back-to-list">
                <i class="fas fa-arrow-left me-2"></i> 
            </a>
        </div>
        <h2 class="mb-4">Payment</h2>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card payment-method-card h-100">
                    <div class="card-body">
                        <h3 class="card-title payment-method-title mb-4">Payment Method</h3>

                        <div class="payment-method-item mb-3">
                            <h5 class="method-name">Kpay (Name: Aeindray)</h5>
                            <p class="method-number">09-975005602</p>
                        </div>

                        <div class="payment-method-item mb-3">
                            <h5 class="method-name">Wave (Name: Aeindray)</h5>
                            <p class="method-number">09-975005602</p>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card payment-info-card h-100">
                    <div class="card-body">
                        <h3 class="card-title payment-info-title mb-4">Payment Info</h3>

                        <form>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="userName" class="form-label visually-hidden">User name</label>
                                        <input type="text" class="form-control" id="userName" placeholder="User name">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="emailAddress" class="form-label visually-hidden">Email
                                            address</label>
                                        <input type="email" class="form-control" id="emailAddress"
                                            placeholder="Email address">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="phoneNumber" class="form-label visually-hidden">Phone</label>
                                        <input type="tel" class="form-control" id="phoneNumber" placeholder="Phone">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="choosePayment" class="form-label visually-hidden">Choose
                                            Payment</label>
                                        <select class="form-select" id="choosePayment">
                                            <option selected disabled>Choose Payment</option>
                                            <option value="kpay">Kpay</option>
                                            <option value="wave">Wave</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="chooseFile" class="form-label visually-hidden">Choose file</label>
                                        <input type="file" class="form-control" id="chooseFile">
                                    </div>
                                </div>

                                <div class="col">
                                        <p class="h5 mt-3">Total Amount: $.......</p>
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