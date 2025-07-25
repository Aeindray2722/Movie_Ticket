<?php
    require_once __DIR__ . '/../layout/nav.php';
?>

<section class="movie-detail-page-content py-4">
    <div class="container">
        <div class="mb-3">
            <a href="<?php echo URLROOT; ?>/movie/trailer" class="btn btn-back-to-list">
                <i class="fas fa-arrow-left me-2"></i>
            </a>
        </div>

        <div class="card movie-detail-card mb-4">
            <div class="row g-0">
                <div class="col-12 movie-detail-video-col text-center mb-3">
                    <video class="img-fluid rounded movie-detail-video" controls poster="../../public/image/video_poster_placeholder.jpg">
                        <source src="../../public/videos/movie_trailer.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
            <div class="row g-0">
                <div class="col-12">
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
                            <i class="fas fa-star text-warning"></i>
                        </div>
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

<style>
    /* Styling from previous snippets would go here or in a separate CSS file */
    /* Add/Modify styles specific to this change */

  

    .movie-detail-video-col {
        padding: 0; /* Remove padding for the video column to make video fill space */
    }

    .movie-detail-video {
        width: 80%;
        height: auto;
        display: block; /* Remove extra space below video */
        max-height: 400px; /* Optional: limit max height for larger screens */
        margin-left: 50px;
        object-fit: cover; /* Ensures video covers the area without distortion */
        border-radius: 0.75rem 0.75rem 0 0; /* Match card top corners, remove bottom for details */
    }

  
</style>

<script>
    // No specific JavaScript needed for this layout change,
    // the video element provides its own controls.
</script>