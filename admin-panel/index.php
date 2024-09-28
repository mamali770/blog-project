<?php
include './include/layout/header.php';

// delete and approve button hadeler
if (isset($_GET['type']) && isset($_GET['action']) && isset($_GET['id'])) {
    $type = $_GET['type'];
    $action = $_GET['action'];
    $typeId = $_GET['id'];
    switch ($type) {
        case 'post':
            $tableDb = 'posts';
            break;
        
        case 'category':
            $tableDb = 'categories';
            break;

        case 'comment':
            $tableDb = 'comments';
            break;
    }
    switch ($action) {
        case 'delete':
            $actionQuery = $conn->prepare("DELETE FROM $tableDb WHERE id = :id");
            break;

        case 'approve':
            $actionQuery = $conn->prepare("UPDATE $tableDb SET status = '1' WHERE id = :id");
            break;
    }
    $actionQuery->execute(["id" => "$typeId"]);
    header("Location:index.php");
}

$posts = $conn->query("SELECT id , title , author FROM posts ORDER BY id DESC LIMIT 5");
$categories = $conn->query("SELECT id , title FROM categories ORDER BY id DESC LIMIT 5");
$comments = $conn->query("SELECT id , name , comment , status FROM comments ORDER BY id DESC LIMIT 5");
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Section -->
        <?php
        include './include/layout/sidebar.php';
        ?>

        <!-- Main Section -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="fs-3 fw-bold">داشبورد</h1>
            </div>

            <!-- Recently Posts -->
            <div class="mt-4">
                <h4 class="text-secondary fw-bold">مقالات اخیر</h4>
                <?php if ($posts->rowCount() > 0) : ?>
                    <div class="table-responsive small">
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
                                        <a href="./pages/posts/edit.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-outline-dark">ویرایش</a>
                                        <a href="index.php?type=post&action=delete&id=<?= $post['id'] ?>" class="btn btn-sm btn-outline-danger">حذف</a>
                                    </td>
                                </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <div class="col">
                        <div class="alert alert-danger">
                            مقاله ای یافت نشد ....
                        </div>
                    </div>
                <?php endif ?>
            </div>

            <!-- Recently Comments -->
            <div class="mt-4">
                <h4 class="text-secondary fw-bold">کامنت های اخیر</h4>
                <?php if ($comments->rowCount() > 0) : ?>
                <div class="table-responsive small">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>نام</th>
                                <th>متن کامنت</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($comments as $comment) : ?>
                            <tr>
                                <th><?= $comment['id'] ?></th>
                                <td><?= htmlspecialchars($comment['name']) ?></td>
                                <td><?= htmlspecialchars($comment['comment']) ?></td>
                                <td>
                                    <?php if ($comment['status'] == '1') : ?>
                                        <button class="btn btn-sm btn-outline-dark disabled">تایید شده</button>
                                    <?php else : ?>
                                        <a href="index.php?type=comment&action=approve&id=<?= $comment['id'] ?>" class="btn btn-sm btn-outline-info">درانتظار تایید</a>
                                    <?php endif ?>
                                    <a href="index.php?type=comment&action=delete&id=<?= $comment['id'] ?>" class="btn btn-sm btn-outline-danger">حذف</a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
                <?php else : ?>
                    <div class="col">
                        <div class="alert alert-danger">
                            کامنتی یافت نشد ....
                        </div>
                    </div>
                <?php endif ?>
            </div>

            <!-- Categories -->
            <div class="mt-4">
                <h4 class="text-secondary fw-bold">دسته بندی</h4>
                <?php if ($categories->rowCount() > 0) : ?>
                <div class="table-responsive small">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>عنوان</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($categories as $category) : ?>
                            <tr>
                                <th><?= $category['id'] ?></th>
                                <td><?= $category['title'] ?></td>
                                <td>
                                    <a href="./pages/categories/edit.php?id=<?= $category['id'] ?>" class="btn btn-sm btn-outline-dark">ویرایش</a>
                                    <a href="index.php?type=category&action=delete&id=<?= $category['id'] ?>" class="btn btn-sm btn-outline-danger">حذف</a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
                <?php else : ?>
                    <div class="col">
                        <div class="alert alert-danger">
                            دسته بندی ای یافت نشد ....
                        </div>
                    </div>
                <?php endif ?>
            </div>
        </main>
    </div>
</div>

<?php
include './include/layout/footer.php';
?>