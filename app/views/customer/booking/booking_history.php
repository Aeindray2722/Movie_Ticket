<?php
    require_once __DIR__ . '/../layout/nav.php';
    ?>

<section class="history-page-content py-4">
    <div class="container">
        <h2 class="mb-4">History</h2>

        <div class="card history-table-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped history-table">
                        <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Movie Name</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>20.7.2025</td>
                                <td>Hello</td>
                                <td><span class="badge bg-warning text-dark history-status-pending">Pending</span></td>
                            </tr>
                            <tr>
                                <td>13.7.2025</td>
                                <td>Titanic</td>
                                <td><span class="badge bg-danger history-status-reject">Reject</span></td>
                            </tr>
                            <tr>
                                <td>18.7.2025</td>
                                <td>Barbie</td>
                                <td><span class="badge bg-success history-status-success">Success</span></td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
    require_once __DIR__ . '/../layout/footer.php';
?>