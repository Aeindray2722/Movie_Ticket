<?php
require_once __DIR__ . '/../layout/nav.php';
?>
<style>
    .video-wrapper {
        position: relative;
        width: 100%;
        padding-top: 50%;
        /* 2:1 aspect ratio (height is 50% of width) */
        background-color: #000;
        overflow: hidden;
    }

    .video-wrapper video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
</style>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<section class="movie-detail-page-content py-4">
    <div class="container">
        <div class="mb-3">
            <a href="<?php echo URLROOT; ?>/trailer/trailer" class="btn btn-back-to-list">
                <i class="fas fa-arrow-left me-2"></i>
            </a>
        </div>

        <div class="card movie-detail-card mb-4">
            <div class="row g-0 flex-column flex-md-row">
                <div class="col-12 col-md-5 col-lg-4 movie-detail-poster-col">
                    <img src="<?= URLROOT . '/images/movies/' . htmlspecialchars($data['movie']['movie_img']) ?>"
                        class="img-fluid rounded-top rounded-md-start movie-detail-poster w-100"
                        alt="<?= htmlspecialchars($data['movie']['movie_name']) ?>">
                </div>
                <div class="col-12 col-md-7 col-lg-8">
                    <div class="card-body movie-detail-body">
                        <h5 class="card-title movie-detail-title"><strong style="color : black">Title:
                            </strong><?= htmlspecialchars($data['movie']['movie_name']) ?>
                        </h5>
                        <p class="movie-detail-type"><strong style="color : black">Type:
                            </strong><?= htmlspecialchars($data['movie']['type_name']) ?></p>
                        <p class="movie-detail-actors"><strong style="color : black">Actor/Actress:
                            </strong><?= htmlspecialchars($data['movie']['actor_name']) ?></p>
                        <p class="movie-detail-description"><strong style="color : black">Description: </strong>
                            <?= nl2br(htmlspecialchars($data['movie']['description'])) ?>
                        </p>
                        <div class="movie-detail-rating mb-3">
                            <?php
                            $avg = $data['avg_rating'] ?? 0;
                            for ($i = 1; $i <= 5; $i++):
                                $class = $i <= $avg ? 'text-warning' : 'text-secondary';
                                ?>
                                <i class="fas fa-star <?= $class ?>"></i>
                            <?php endfor; ?>
                        </div>

                        <div class="d-flex gap-2 flex-wrap">
                            <button class="btn btn-book-now" data-bs-toggle="modal" data-bs-target="#trailerModal">
                                Watch Trailer
                            </button>
                            <button class="btn btn-warning" data-bs-toggle="modal"
                                data-bs-target="#rateModal">Rate</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="comments-section mb-4">
            <h3 class="comments-heading mb-3">Comments</h3>
            <div class="row g-3">
                <?php if (!empty($data['comment'])): ?>
                    <?php foreach ($data['comment'] as $comment): ?>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="d-flex p-3 shadow-sm rounded bg-white h-100">
                                <img src="<?= URLROOT ?>/images/users/<?= $comment['profile_img'] ?? 'default.png' ?>"
                                    class="img-fluid rounded-circle me-3" style="width: 80px; height: 80px; object-fit: cover;" alt="Profile">

                                <div class="flex-grow-1">

                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h5 class="mb-0 text-truncate"><?= htmlspecialchars($comment['name']) ?></h5>

                                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comment['user_id']): ?>
                                            <button class="btn btn-sm btn-outline-danger btn-action ms-2"
                                                onclick="deleteMovie('<?= base64_encode($comment['id']) ?>')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>

                                    <p class="text-dark mb-0"><?= nl2br(htmlspecialchars($comment['message'])) ?></p>
                                    <p class="mb-1 text-muted" style="font-size: 10px;">
                                        <?= date('j-F-Y h:i A', strtotime($comment['created_at'])) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No comments yet. Be the first to comment!</p>
                <?php endif; ?>
            </div>

            <div class="card comment-input-card mt-4">
                <div class="card-body">
                    <form id="commentForm" method="POST" action="<?= URLROOT ?>/comment/store">
                        <input type="hidden" name="movie_id" value="<?= htmlspecialchars($data['movie']['id']) ?>">
                        <input type="hidden" name="user_id" value="<?= (int) ($_SESSION['user_id'] ?? 0) ?>">
                        <textarea name="comment_text" class="form-control comment-textarea" rows="3"
                            placeholder="Comment..." required></textarea>
                        <button type="submit" class="btn btn-send-comment mt-3">Send</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>
<div class="modal fade" id="rateModal" tabindex="-1" aria-labelledby="rateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rateModalLabel">Rate this Movie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <form action="<?= URLROOT ?>/rating/submit" method="POST">
                    <input type="hidden" name="movie_id" value="<?= htmlspecialchars($data['movie']['id']) ?>">
                    <input type="hidden" name="user_id" value="<?= (int) ($_SESSION['user_id'] ?? 0) ?>">

                    <input type="hidden" name="count" id="ratingCount" value="0">
                    <div class="text-center mb-3">
                        <span class="star fs-3 text-secondary" data-value="1">&#9733;</span>
                        <span class="star fs-3 text-secondary" data-value="2">&#9733;</span>
                        <span class="star fs-3 text-secondary" data-value="3">&#9733;</span>
                        <span class="star fs-3 text-secondary" data-value="4">&#9733;</span>
                        <span class="star fs-3 text-secondary" data-value="5">&#9733;</span>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-warning">Submit Rating</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="trailerModal" tabindex="-1" aria-labelledby="trailerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="padding: 0.5rem 1rem;">
                <h5 class="modal-title" style="font-size: 1rem;">
                    (<?= htmlspecialchars($data['movie']['movie_name']) ?>) Trailer
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick="pauseTrailer()"></button>
            </div>

            <div class="modal-body p-0">
                <div class="video-wrapper">
                    <video id="trailerVideo" controls>
                        <source
                            src="<?= URLROOT . '/videos/trailers/' . htmlspecialchars($data['trailer']['trailer_vd']) ?>"
                            type="video/mp4">
                    </video>
                </div>
            </div>

        </div>
    </div>
</div>
<?php
require_once __DIR__ . '/../layout/footer.php';
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function bindStarClickEvents() {
        const stars = document.querySelectorAll('.star');
        const ratingInput = document.getElementById('ratingCount');

        stars.forEach(star => {
            star.addEventListener('click', () => {
                const selectedValue = parseInt(star.getAttribute('data-value'));
                ratingInput.value = selectedValue;

                stars.forEach(s => {
                    const val = parseInt(s.getAttribute('data-value'));
                    s.classList.remove('text-warning', 'text-secondary');
                    s.classList.add(val <= selectedValue ? 'text-warning' : 'text-secondary');
                });
            });
        });
    }

    document.addEventListener('DOMContentLoaded', bindStarClickEvents);

    const modal = document.getElementById('ratingModal');
    if (modal) {
        modal.addEventListener('shown.bs.modal', bindStarClickEvents);
    }
</script>
<script>
    function deleteMovie(encodedId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to delete this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= URLROOT ?>/comment/destroy/' + encodedId;
            }
        });
    }
</script>
<script>
    function pauseTrailer() {
        const video = document.getElementById('trailerVideo');
        if (video) {
            video.pause();
        }
    }

    const trailerModal = document.getElementById('trailerModal');
    if (trailerModal) {
        trailerModal.addEventListener('hidden.bs.modal', pauseTrailer);
    }
</script>