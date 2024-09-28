<?php
include '../../include/layout/header.php';
$comments = $conn->query("SELECT id , name , comment , status FROM comments ORDER BY id DESC");

// delete and approve button hadeler
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $typeId = $_GET['id'];
    switch ($action) {
        case 'delete':
            $actionQuery = $conn->prepare("DELETE FROM comments WHERE id = :id");
            break;

        case 'approve':
            $actionQuery = $conn->prepare("UPDATE comments SET status = '1' WHERE id = :id");
            break;
    }
    $actionQuery->execute(["id" => "$typeId"]);
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
                <h1 class="fs-3 fw-bold">کامنت ها</h1>
            </div>

            <!-- Comments -->
            <div class="mt-4">
                <div class="table-responsive small">
                    <?php if ($comments->rowCount() > 0) : ?>
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
                                        <a href="index.php?action=approve&id=<?= $comment['id'] ?>" class="btn btn-sm btn-outline-info">درانتظار تایید</a>
                                    <?php endif ?>
                                    <a href="index.php?action=delete&id=<?= $comment['id'] ?>" class="btn btn-sm btn-outline-danger">حذف</a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                    <?php else : ?>
                        <div class="col">
                            <div class="alert alert-danger">
                                کامنتی یافت نشد ....
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