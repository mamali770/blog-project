<?php
    include './include/layout/header.php';
    if (isset($_GET['category'])) {
        $query = "SELECT posts.id , posts.image , posts.title , categories.title as `ctg` , posts.body , posts.author FROM posts INNER JOIN categories ON posts.category_id = categories.id WHERE categories.title = :category ORDER BY posts.id DESC";
        $posts = $conn->prepare($query);
        $posts->execute([
            'category' => $_GET['category']
        ]);
    } else {
        $query = "SELECT posts.id , posts.image , posts.title , categories.title as `ctg` , posts.body , posts.author FROM posts INNER JOIN categories ON posts.category_id = categories.id ORDER BY posts.id DESC";
        $posts = $conn->query($query);
    }
    
?>

<main>
    <!-- Slider Section -->
    <?php
        include './include/layout/slider.php';
    ?>

    <!-- Content Section -->
    <section class="mt-4">
        <div class="row">
            <!-- Posts Content -->
            <div class="col-lg-8">
                <div class="row g-3">
                    <?php if($posts->rowCount() > 0) : ?>
                        <?php foreach ($posts as $post) : ?>
                            <div class="col-sm-6">
                                <div class="card">
                                    <img src="./upload/<?= $post['image'] ?>" class="card-img-top" alt="post-image"/>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="card-title fw-bold"><?= $post['title'] ?></h5>
                                            <div>
                                                <span class="badge text-bg-secondary"><?= $post['ctg'] ?></span>
                                            </div>
                                        </div>
                                        <p class="card-text text-secondary pt-3"><?= substr($post['body'] , 0 , 200) . "..." ?></p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="single.php?post=<?= $post['id'] ?>" class="btn btn-sm btn-dark">مشاهده</a>
                                            <p class="fs-7 mb-0">
                                                نویسنده : <span><?= $post['author'] ?></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                        <?php else : ?>
                            <div class="col">
                                <div class="alert alert-danger">
                                    مقاله ای یافت نشد ....
                                </div>
                            </div>
                    <?php endif ?>
                </div>
            </div>

            <!-- Sidebar Section -->
            <?php
                include './include/layout/sidebar.php';
            ?>
        </div>
    </section>
</main>

<!-- Footer Section -->
<?php
    include './include/layout/footer.php';
?>