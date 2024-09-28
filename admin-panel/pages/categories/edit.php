<?php
include '../../include/layout/header.php';
$category = [];
if (isset($_GET['id'])) {
    $category = $conn->prepare("SELECT * FROM categories WHERE id = :id");
    $category->execute(["id" => $_GET['id']]);
    $category = $category->fetch();
}
$errMessage = "";
if (isset($_POST['editCategory'])) {
    if (empty(trim($_POST['title']))) {
        $errMessage = "فیلد عنوان دسته بندی نمی تواند خالی باشد.";
    } else {
        $editPost = $conn->prepare("UPDATE categories SET title = :title WHERE id = :id");
        $editPost->execute([
            "title" => trim($_POST['title']),
            "id" => $category['id']
        ]);
        header("Location:index.php");
    }
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
                <h1 class="fs-3 fw-bold">ویرایش دسته بندی</h1>
            </div>

            <!-- Posts -->
            <div class="mt-4">
            <?php if (count($category) > 0) : ?>
                <form class="row g-4" method="POST">
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">عنوان دسته بندی</label>
                        <input type="text" name="title" class="form-control" value="<?= $category['title'] ?>" />
                        <div class="form-text text-danger"><?= $errMessage ?></div>
                    </div>

                    <div class="col-12">
                        <button type="submit" name="editCategory" class="btn btn-dark">ویرایش</button>
                    </div>
                </form>
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
include '../../include/layout/footer.php';
?>
