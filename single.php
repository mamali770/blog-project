<?php
    include './include/layout/header.php';
    $postId = trim($_GET['post']);
    if (isset($postId)) {
        $query = "SELECT posts.id , posts.image , posts.title , categories.title as `ctg` , posts.body , posts.author FROM posts INNER JOIN categories ON posts.category_id = categories.id WHERE posts.id = :id ORDER BY posts.id DESC";
        $posts = $conn->prepare($query);
        $posts->execute([
            'id' => $postId
        ]);
        $post = $posts->fetch();
    }
?>

<main>
    <!-- Content -->
    <section class="mt-4">
        <div class="row">
            <!-- Posts & Comments Content -->
            <div class="col-lg-8">
                <div class="row justify-content-center">
                    <!-- Post Section -->
                    <?php if ($posts->rowCount() == 0) : ?>
                        <div class="alert alert-danger">
                            مقاله مورد نظر پیدا نشد !!!!
                        </div>
                    <?php else : ?>
                        <div class="col">
                            <div class="card">
                                <img src="./upload/<?= $post['image'] ?>" class="card-img-top" alt="post-image"/>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title fw-bold"><?= $post['title'] ?></h5>
                                        <div>
                                            <span class="badge text-bg-secondary"><?= $post['ctg'] ?></span>
                                        </div>
                                    </div>
                                    <p class="card-text text-secondary text-justify pt-3"><?= $post['body'] ?></p>
                                    <div>
                                        <p class="fs-6 mt-5 mb-0">
                                            نویسنده : <span><?= $post['author'] ?></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="mt-4" />

                        <!-- Comment Section -->
                        
                        <div class="col">
                            <!-- Comment Form -->
                            <div class="card">
                                <?php
                                $message = [
                                    "invalName" => "",
                                    "invalComment" => "",
                                    "successfully" => ""
                                ];
                                if (isset($_POST['commentSub'])) {
                                    $name = trim($_POST['name']);
                                    $comment = trim($_POST['comment']);
                                    if (empty($name) || strlen($name) < 3) {
                                        $message['invalName'] = 'فیلد نام ضروری است.';
                                    } elseif (empty($comment) || strlen($comment) < 7) {
                                        $message['invalComment'] = 'فیلد کامنت ضروری است.';
                                    } else {
                                        $addComment = $conn->prepare("INSERT INTO comments (name , comment , post_id) VALUES (:name , :comment , {$post['id']})");
                                        $addComment->execute([
                                            "name" => $_POST['name'],
                                            "comment" => $_POST['comment']
                                        ]);
                                        $message['successfully'] = 'کامنت شما با موفقیت ثبت شد و پس از تایید مدیر نمایش داده می شود.';
                                    }
                                }
                                ?>
                                <div class="card-body">
                                    <p class="fw-bold fs-5">ارسال کامنت</p>

                                    <form method="POST">
                                        <div class="text-success"><?= $message['successfully'] ?></div>
                                        <div class="mb-3">
                                            <label class="form-label">نام</label>
                                            <input type="text" name="name" class="form-control"/>
                                            <div class="form-text text-danger"><?= $message['invalName'] ?></div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">متن کامنت</label>
                                            <textarea class="form-control" name="comment" rows="3"></textarea>
                                            <div class="form-text text-danger"><?= $message['invalComment'] ?></div>
                                        </div>
                                        <button type="submit" name="commentSub" class="btn btn-dark">
                                            ارسال
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <hr class="mt-4" />
                            <!-- Comment Content -->
                            <?php
                            $commentQuery = "SELECT name , comment FROM comments WHERE post_id = :id AND status = '1'";
                            $comments = $conn->prepare($commentQuery);
                            $comments->execute([
                                "id" => $postId
                            ]);
                            ?>
                            <p class="fw-bold fs-6">تعداد کامنت : <span><?= $comments->rowCount() ?></span></p>

                            <?php if ($comments->rowCount() > 0) : ?>
                                <?php foreach ($comments as $comment) : ?>
                                    <div class="card bg-light-subtle mb-3">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <img src="./assets/images/profile.png" width="45" height="45" alt="user-profle"/>

                                                <h5 class="card-title me-2 mb-0">
                                                    <?= htmlspecialchars($comment['name']) ?>
                                                </h5>
                                            </div>

                                            <p class="card-text pt-3 pr-3">
                                                <?= htmlspecialchars($comment['comment']) ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            <?php else : ?>
                                <div class="alert alert-danger">
                                    کامنتی برای این پست وجود ندارد!
                                </div>
                            <?php endif ?>

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

<!-- Footer -->
<?php
    include './include/layout/footer.php';
?>