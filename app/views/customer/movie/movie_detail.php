<?php
    require_once __DIR__ . '/../layout/nav.php';
?>

<section class="movie-detail-page-content py-4">
    <div class="container">
        <div class="mb-3">
            <a href="<?php echo URLROOT; ?>/movie/nowShowing" class="btn btn-back-to-list">
                <i class="fas fa-arrow-left me-2"></i> 
            </a>
        </div>

        <div class="card movie-detail-card mb-4">
            <div class="row g-0">
                <div class="col-md-5 col-lg-4 movie-detail-poster-col">
                    <img src="../../public/image/movie_poster_placeholder.jpg" class="img-fluid rounded-start movie-detail-poster" alt="Movie Poster">
                    </div>
                <div class="col-md-7 col-lg-8">
                    <div class="card-body movie-detail-body">
                        <h1 class="card-title movie-detail-title">Movie Name</h1>
                        <p class="movie-detail-type">Movie Type</p>
                        <p class="movie-detail-actors">Actor/Actress Name</p>
                        <p class="movie-detail-description">
                            Description Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                            Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                        </p>
                        <div class="movie-detail-rating mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i> </div>
                        <a href="<?php echo URLROOT; ?>/booking/booking"><button class="btn btn-book-now">Book Now</button></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="comments-section mb-4">
            <h3 class="comments-heading mb-3">Comments</h3>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card comment-card">
                        <div class="card-body d-flex align-items-center">
                            <i class="fas fa-user-circle comment-avatar me-3"></i>
                            <div>
                                <h6 class="comment-username">username</h6>
                                <p class="comment-text">Comment...</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card comment-card">
                        <div class="card-body d-flex align-items-center">
                            <i class="fas fa-user-circle comment-avatar me-3"></i>
                            <div>
                                <h6 class="comment-username">username</h6>
                                <p class="comment-text">Comment...</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card comment-card">
                        <div class="card-body d-flex align-items-center">
                            <i class="fas fa-user-circle comment-avatar me-3"></i>
                            <div>
                                <h6 class="comment-username">username</h6>
                                <p class="comment-text">Comment...</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card comment-card">
                        <div class="card-body d-flex align-items-center">
                            <i class="fas fa-user-circle comment-avatar me-3"></i>
                            <div>
                                <h6 class="comment-username">username</h6>
                                <p class="comment-text">Comment...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card comment-input-card mt-4">
                <div class="card-body">
                    <textarea class="form-control comment-textarea" rows="3" placeholder="Comment..."></textarea>
                    <button class="btn btn-send-comment mt-3">Send</button>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
    require_once __DIR__ . '/../layout/footer.php';
?>