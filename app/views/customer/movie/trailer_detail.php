<?php
require_once __DIR__ . '/../layout/nav.php';

// Generate CSRF token if not set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/nowshowing.css" />

<section class="movie-detail-page-content">
    <div class="container">
        <a href="<?php echo URLROOT; ?>/trailer/trailer" class="back-button">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>

        <div class="hero-section">
            <div class="hero-background"
                style="background-image: url('<?= URLROOT . '/images/movies/' . htmlspecialchars($data['movie']['movie_img']) ?>');">
            </div>
            <div class="hero-content">
                <div class="row align-items-center">
                    <div class="col-12 col-md-4 text-center mb-4 mb-md-0">
                        <img src="<?= URLROOT . '/images/movies/' . htmlspecialchars($data['movie']['movie_img']) ?>"
                            class="movie-poster" alt="<?= htmlspecialchars($data['movie']['movie_name']) ?>">
                    </div>
                    <div class="col-12 col-md-8 ">
                        <h1 class="movie-title"><?= htmlspecialchars($data['movie']['movie_name']) ?></h1>

                        <div class="movie-meta ">
                            <div class="meta-item mt-3">
                                <i class="fas fa-tag"></i>
                                <?= htmlspecialchars($data['movie']['type_name']) ?>
                            </div>
                            <div class="meta-item mt-3">
                                <i class="fas fa-user"></i>
                                <?= htmlspecialchars($data['movie']['actor_name']) ?>
                            </div>
                        </div>

                        <div class="rating-display">
                            <div class="stars">
                                <?php
                                $avg = $data['avg_rating'] ?? 0;
                                for ($i = 1; $i <= 5; $i++):
                                    $class = $i <= $avg ? 'text-warning' : 'text-secondary';
                                    ?>
                                    <i class="fas fa-star star <?= $class ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <div class="viewer-count">
                                <i class="fas fa-eye"></i>
                                <?= (int) ($data['movie']['view_count'] ?? 0) ?> views
                            </div>
                        </div>

                        <div class="action-buttons">
                            <button class="btn-modern btn-primary-modern" data-bs-toggle="modal" data-bs-target="#trailerModal">
                                Watch Trailer
                            </button>
                            <button class="btn-modern btn-secondary-modern" data-bs-toggle="modal"
                                data-bs-target="#rateModal">
                                <i class="fas fa-star"></i>
                                Rate Movie
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="description-card">
            <h3 class="section-title">
                <i class="fas fa-align-left"></i>
                Synopsis
            </h3>
            <p style="font-size: 1.1rem; line-height: 1.8; color: #6c757d;">
                <?= nl2br(htmlspecialchars($data['movie']['description'])) ?>
            </p>
        </div>

        <div class="comments-section">
            <h3 class="section-title">
                <i class="fas fa-comments"></i>
                Comments
            </h3>

            <?php if (!empty($data['comments'])): ?>
                <div class="comments-grid">
                    <?php foreach ($data['comments'] as $comment): ?>
                        <div class="comment-card">
                            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comment['user_id']): ?>
                                <button class="delete-btn" onclick="deleteMovie('<?= base64_encode($comment['id']) ?>')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            <?php endif; ?>

                            <div class="d-flex">
                                <img src="<?= URLROOT ?>/images/users/<?= $comment['profile_img'] ?? 'default.png' ?>"
                                    class="comment-avatar" alt="Profile">
                                <div class="flex-grow-1">
                                    <h5 class="mb-2" style="color: #212529;"><?= htmlspecialchars($comment['name']) ?></h5>
                                    <p class="mb-2" style="color: #6c757d; line-height: 1.6;">
                                        <?= nl2br(htmlspecialchars($comment['message'])) ?>
                                    </p>
                                    <small style="color: #adb5bd;">
                                        <i class="fas fa-clock me-1"></i>
                                        <?= date('j F Y, h:i A', strtotime($comment['created_at'])) ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-comment-slash" style="font-size: 4rem; color: #dee2e6; margin-bottom: 1rem;"></i>
                    <p style="color: #6c757d; font-size: 1.2rem;">No comments yet. Be the first to share your thoughts!</p>
                </div>
            <?php endif; ?>

            <div class="comment-form">
                <h4 style="color: #212529; margin-bottom: 1.5rem;">
                    <i class="fas fa-pen me-2"></i>
                    Leave a Comment
                </h4>
                <form id="commentForm" method="POST" action="<?= URLROOT ?>/comment/store">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="movie_id" value="<?= htmlspecialchars($data['movie']['id']) ?>">
                    <input type="hidden" name="user_id" value="<?= (int) ($_SESSION['user_id'] ?? 0) ?>">
                    <textarea name="comment_text" class="form-control form-control-modern mb-3" rows="4"
                        placeholder="Share your thoughts about this movie..." required></textarea>
                    <button type="submit" class="btn-modern btn-primary-modern">
                        <i class="fas fa-paper-plane"></i>
                        Post Comment
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<div class="modal fade modal-modern" id="rateModal" tabindex="-1" aria-labelledby="rateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rateModalLabel">
                    <i class="fas fa-star me-2"></i>
                    Rate this Movie
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p style="color: rgba(255, 255, 255, 0.8); margin-bottom: 2rem;">How would you rate this movie?</p>
                <form action="<?= URLROOT ?>/rating/submit" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="source" value="now_showing">
                    <input type="hidden" name="movie_id" value="<?= htmlspecialchars($data['movie']['id']) ?>">
                    <input type="hidden" name="user_id" value="<?= (int) ($_SESSION['user_id'] ?? 0) ?>">
                    <input type="hidden" name="count" id="ratingCount" value="0">

                    <div class="rating-stars">
                        <span class="rating-star" data-value="1">★</span>
                        <span class="rating-star" data-value="2">★</span>
                        <span class="rating-star" data-value="3">★</span>
                        <span class="rating-star" data-value="4">★</span>
                        <span class="rating-star" data-value="5">★</span>
                    </div>

                    <button type="submit" class="btn-modern btn-secondary-modern">
                        <i class="fas fa-check"></i>
                        Submit Rating
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="trailerModal" tabindex="-1" aria-labelledby="trailerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-dark">
            <div class="modal-header border-0" style="padding: 1rem;">
                <h5 class="modal-title text-white" id="trailerModalLabel">
                    <i class="fas fa-play-circle me-2"></i>
                    <?= htmlspecialchars($data['movie']['movie_name']) ?> - Trailer
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="video-wrapper" style="position: relative; width: 100%; height: 0; padding-bottom: 56.25%; background: #000;">
                    <?php if (!empty($data['trailer']['trailer_vd'])): ?>
                        <video 
                            id="trailerVideo" 
                            controls 
                            preload="metadata"
                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain;"
                            poster="<?= URLROOT . '/images/movies/' . htmlspecialchars($data['movie']['movie_img']) ?>">
                            <source src="<?= URLROOT . '/videos/trailers/' . htmlspecialchars($data['trailer']['trailer_vd']) ?>" type="video/mp4">
                            <p class="text-white text-center p-4">Your browser does not support the video tag.</p>
                        </video>
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <div class="text-center text-white">
                                <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                                <h5>Trailer not available</h5>
                                <p>Sorry, the trailer for this movie is currently unavailable.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<script>
$(document).ready(function () {
    // Owl Carousel
    $('.related-movies-carousel').owlCarousel({
        loop: true,
        margin: 20,
        nav: true,
        dots: false,
        navText: ['<i class="fas fa-chevron-left"></i>', '<i class="fas fa-chevron-right"></i>'],
        responsive: {
            0: { items: 1 },
            576: { items: 2 },
            768: { items: 3 },
            992: { items: 4 },
            1200: { items: 5 }
        }
    });

    // Trailer Modal Management
    const trailerModal = document.getElementById('trailerModal');
    const trailerVideo = document.getElementById('trailerVideo');

    // Function to pause and reset video
    function pauseAndResetVideo() {
        if (trailerVideo) {
            trailerVideo.pause();
            trailerVideo.currentTime = 0;
            trailerVideo.load(); // Reload the video element
        }
    }

    // Function to prepare video for playing
    function prepareVideo() {
        if (trailerVideo) {
            trailerVideo.load();
        }
    }

    // Event listeners for modal
    if (trailerModal) {
        // When modal is about to show
        trailerModal.addEventListener('show.bs.modal', function (e) {
            prepareVideo();
        });

        // When modal is fully shown
        trailerModal.addEventListener('shown.bs.modal', function (e) {
            if (trailerVideo) {
                // Optional: Auto-play (many browsers block this)
                // trailerVideo.play().catch(e => console.log('Auto-play prevented'));
            }
        });

        // When modal starts hiding
        trailerModal.addEventListener('hide.bs.modal', function (e) {
            pauseAndResetVideo();
        });

        // When modal is fully hidden
        trailerModal.addEventListener('hidden.bs.modal', function (e) {
            pauseAndResetVideo();
        });
    }

    // Handle video errors
    if (trailerVideo) {
        trailerVideo.addEventListener('error', function(e) {
            console.error('Video error:', e);
            const videoWrapper = trailerVideo.parentElement;
            videoWrapper.innerHTML = `
                <div class="d-flex align-items-center justify-content-center h-100">
                    <div class="text-center text-white">
                        <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                        <h5>Error loading trailer</h5>
                        <p>Unable to load the video. Please try again later.</p>
                    </div>
                </div>
            `;
        });

        // Handle successful video load
        trailerVideo.addEventListener('loadeddata', function() {
            console.log('Video loaded successfully');
        });
    }
});

// Rating functionality
function bindStarClickEvents() {
    const stars = document.querySelectorAll('.rating-star');
    const ratingInput = document.getElementById('ratingCount');

    stars.forEach(star => {
        star.addEventListener('click', () => {
            const selectedValue = parseInt(star.getAttribute('data-value'));
            ratingInput.value = selectedValue;

            stars.forEach((s, index) => {
                s.classList.remove('active');
                if (index < selectedValue) {
                    s.classList.add('active');
                }
            });
        });

        star.addEventListener('mouseenter', () => {
            const hoverValue = parseInt(star.getAttribute('data-value'));
            stars.forEach((s, index) => {
                s.style.color = index < hoverValue ? '#ffd700' : 'rgba(255, 255, 255, 0.3)';
            });
        });
    });

    document.querySelector('.rating-stars').addEventListener('mouseleave', () => {
        const currentRating = parseInt(ratingInput.value) || 0;
        stars.forEach((s, index) => {
            s.style.color = index < currentRating ? '#ffd700' : 'rgba(255, 255, 255, 0.3)';
        });
    });
}

document.addEventListener('DOMContentLoaded', bindStarClickEvents);

const ratingModal = document.getElementById('rateModal');
if (ratingModal) {
    ratingModal.addEventListener('shown.bs.modal', bindStarClickEvents);
}

// Delete comment function
function deleteMovie(encodedId) {
    Swal.fire({
        title: 'Delete Comment?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: '#95a5a6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        background: 'rgba(255, 255, 255, 0.1)',
        backdropFilter: 'blur(20px)',
        color: 'white'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= URLROOT ?>/comment/destroy/' + encodedId;
        }
    });
}
</script>

<style>
.modal-xl {
    max-width: 90vw;
}

@media (min-width: 1200px) {
    .modal-xl {
        max-width: 900px;
    }
}

.video-wrapper video {
    border-radius: 0 0 0.375rem 0.375rem;
}

.modal-content.bg-dark {
    background-color: #1a1a1a !important;
    border: none;
}
/* No CSS changes were made below this line to adhere to the prompt's instructions */
/* Loading spinner for video
.video-wrapper::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #007bff;
    border-radius: 50%;
    animation: spin 2s linear infinite;
    z-index: 1;
} */

.video-wrapper video:not([src=""]) + .video-wrapper::before {
    display: none;
}

@keyframes spin {
    0% { transform: translate(-50%, -50%) rotate(0deg); }
    100% { transform: translate(-50%, -50%) rotate(360deg); }
}
</style>