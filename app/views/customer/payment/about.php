<?php require_once __DIR__ . '/../layout/nav.php'; ?>

<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>AOS.init();</script>

<style>
    :root {
        --main-color: #c170cd;
        /* nav color */
        --accent-color: #e50914;
        /* Netflix red / cinema tone */
        --gold-color: #ffcc00;
        --text-color: #333;
        --bg-light: #fff6f6;
        --muted-color: #666;
    }

    body {
        background-color: var(--bg-light);
        color: var(--text-color);
    }

    .about-section {
        padding: 90px 0;
        background: linear-gradient(to right, #fff6f6, #fceaff);
    }

    .about-text h2 {
        font-size: 2.8rem;
        font-weight: 700;
        color: var(--main-color);
    }

    .about-text p {
        font-size: 1.15rem;
        line-height: 1.8;
        color: var(--muted-color);
    }

    .about-img {
        max-width: 100%;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .team-section {
        background: #fff;
        padding: 80px 0;
    }

    .team-section h2 {
        color: var(--accent-color);
        font-weight: 700;
        font-size: 2.5rem;
    }

    .team-member img {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--main-color);
    }

    .team-member:hover {
        transform: scale(1.05);
        transition: all 0.3s ease-in-out;
    }

    .team-member h5 {
        margin-top: 15px;
        color: var(--text-color);
        font-weight: 600;
    }

    .team-member p {
        color: var(--muted-color);
        font-size: 0.95rem;
    }
</style>

<section class="about-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 about-text" data-aos="fade-right">
                <h2>🎬 Our Story</h2>
                <p>
                    We built this platform out of pure passion for movies. Whether you're a fan of heart-pounding
                    thrillers or tear-jerking dramas, our mission is to bring that cinematic magic to your fingertips.
                </p>
                <p>
                    Just like in the golden age of film, we believe that every story deserves a spotlight — and every
                    viewer deserves an experience they won’t forget.
                </p>
            </div>
            <div class="col-lg-6 text-center mt-4 mt-lg-0" data-aos="fade-left">
                <img src="https://images.unsplash.com/photo-1536440136628-849c177e76a1?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OHx8bW92aWUlMjB0aGVhdGVyfGVufDB8fDB8fHww"
                    class="about-img w-100" alt="Movie Experience">
            </div>
        </div>
    </div>
</section>

<section class="team-section">
    <div class="container text-center">
        <h2 data-aos="fade-up">Meet Our Team</h2>
        <p class="mb-5" data-aos="fade-up" data-aos-delay="100" style="color: var(--muted-color);">The people behind the
            screen</p>
        <div class="row justify-content-center g-4">
            <?php
            foreach ($data['team'] as $i => $member): ?>
                <div class="col-6 col-md-4 col-lg-3" data-aos="zoom-in" data-aos-delay="<?= $i * 100 ?>">
                    <div class="team-member">
                        <img src="/images/users/<?= $member['profile_img']; ?>"
                            alt="<?= htmlspecialchars($member['name']); ?>" class="img-fluid">
                        <h5><?= $member['name']; ?></h5>
                        <p>
                            <?= $member['role'] == 1 ? 'Customer' : 'Admin'; ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>