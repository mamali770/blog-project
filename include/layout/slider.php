<?php

$sliderPosts = $conn->query("SELECT active , title , body , image FROM posts_slider INNER JOIN posts ON posts_slider.post_id = posts.id ORDER BY posts.id DESC");

?>
<section>
    <div id="carousel" class="carousel slide">
        <div class="carousel-indicators">
            <button
                type="button"
                data-bs-target="#carousel"
                data-bs-slide-to="0"
                class="active"
            ></button>
            <button
                type="button"
                data-bs-target="#carousel"
                data-bs-slide-to="1"
            ></button>
            <button
                type="button"
                data-bs-target="#carousel"
                data-bs-slide-to="2"
            ></button>
        </div>
        <div class="carousel-inner rounded">
            <?php if($sliderPosts->rowCount() > 0) : ?>
                <?php foreach ($sliderPosts as $post) : ?>
                    <div class="carousel-item overlay carousel-height <?= ($post['active']) ? 'active' : '' ?>">
                        <img src="./upload/<?= $post['image'] ?>" class="d-block w-100" alt="post-image"/>
                        <div class="carousel-caption d-none d-md-block">
                            <h5><?= $post['title'] ?></h5>
                            <p><?= substr($post['body'] , 0 , 200) . "..." ?></p>
                        </div>
                    </div>
                <?php endforeach ?>
            <?php endif ?>
        </div>
        <button
            class="carousel-control-prev"
            type="button"
            data-bs-target="#carousel"
            data-bs-slide="prev"
        >
            <span class="carousel-control-prev-icon"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button
            class="carousel-control-next"
            type="button"
            data-bs-target="#carousel"
            data-bs-slide="next"
        >
            <span class="carousel-control-next-icon"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>