<?php
    require_once __DIR__ . '/../layout/nav.php';
?>

<section class="now-showing-page-content py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="section-heading mb-4">Movie Trailer</h2>
                
            </div>
            <div class="search-bar-on-page">
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="search" aria-label="Search">
                    <button class="btn btn-secondary" type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>

        <div class="filter-buttons mb-4 me-3">
            <button class="btn btn-filter active">All</button>
            <button class="btn btn-filter">English</button>
            <button class="btn btn-filter">Myanmar</button>
            <button class="btn btn-filter">Korea</button>
            <button class="btn btn-filter">India</button>
            <button class="btn btn-filter">Cartoon</button>
            <button class="btn btn-filter">Japan</button>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-2 g-4 mb-4">
            <div class="col">
                <div class="card movie-card-lg">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="../../public/image/index4.avif" class="img-fluid rounded-start movie-poster-lg" alt="Tangled">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title-lg">Movie Name</h5>
                                <p class="card-text-lg">Movie Type</p>
                                <a href="<?php echo URLROOT; ?>/movie/trailerWatch" ><button class="btn btn-view-detail">Watch</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card movie-card-lg">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="https://via.placeholder.com/180x250?text=Cargo" class="img-fluid rounded-start movie-poster-lg" alt="Cargo">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title-lg">Movie Name</h5>
                                <p class="card-text-lg">Movie Type</p>
                                <a href="<?php echo URLROOT; ?>/movie/trailerWatch"><button class="btn btn-view-detail">Watch</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card movie-card-lg">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="../../public/image/login.jpg" class="img-fluid rounded-start movie-poster-lg" alt="Movie 3">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title-lg">Movie Name</h5>
                                <p class="card-text-lg">Movie Type</p>
                                <a href="<?php echo URLROOT; ?>/movie/trailerWatch"><button class="btn btn-view-detail">Watch</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card movie-card-lg">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="https://via.placeholder.com/180x250?text=Movie+4" class="img-fluid rounded-start movie-poster-lg" alt="Movie 4">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title-lg">Movie Name</h5>
                                <p class="card-text-lg">Movie Type</p>
                                <a href="<?php echo URLROOT; ?>/movie/trailerWatch"><button class="btn btn-view-detail">Watch</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           
        </div>

        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                    <a class="page-link" href="#" aria-label="Previous">
                        <span aria-hidden="true">&lt;</span>
                    </a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Next">
                        <span aria-hidden="true">&gt;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</section>

<?php
    require_once __DIR__ . '/../layout/footer.php';
?>