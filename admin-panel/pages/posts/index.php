<?php
include '../../include/layout/header.php';
$posts = $conn->query("SELECT id , title , author FROM posts ORDER BY id DESC");

if (isset($_GET['action']) && isset($_GET['id'])) {
    $deleteQuery = $conn->prepare("DELETE FROM posts WHERE id = :id");
    $deleteQuery->execute(["id" => $_GET['id']]);
    header("Location:index.php");
}
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Section -->
        <?php
        include '../../include/layout/sidebar.php';
        ?>

        <!-- Main Section -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="fs-3 fw-bold">مقالات</h1>

                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="./create.php" class="btn btn-sm btn-dark">
                        ایجاد مقاله
                    </a>
                </div>
            </div>

                <!-- Posts -->
                <div class="mt-4">
                <div class="table-responsive small">
                <?php if($posts->rowCount() > 0) : ?>
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>عنوان</th>
                                <th>نویسنده</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($posts as $post) : ?>
                            <tr>
                                <th><?= $post['id'] ?></th>
                                <td><?= $post['title'] ?></td>
                                <td><?= $post['author'] ?></td>
                                <td>
                                    <a href="./edit.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-outline-dark">ویرایش</a>
                                    <a href="index.php?action=delete&id=<?= $post['id'] ?>" class="btn btn-sm btn-outline-danger">حذف</a>
                                </td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <div class="col">
                        <div class="alert alert-danger">
                            مقاله ای یافت نشد ....
                        </div>
                    </div>
                <?php endif ?>
                </div>
            </div>
        </main>
    </div>
</div>

<?php
include '../../include/layout/footer.php';
?>
