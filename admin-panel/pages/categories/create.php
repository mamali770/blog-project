<?php
include '../../include/layout/header.php';
$errMessage = "";
if (isset($_POST['addCategory'])) {
    if (empty(trim($_POST['title']))) {
        $errMessage = "فیلد عنوان دسته بندی نمی تواند خالی باشد.";
    } else {
        $addCategory = $conn->prepare("INSERT INTO categories (title) VALUES (:title)");
        $addCategory->execute(["title" => trim($_POST['title'])]);
        $categoryId = $conn->query("SELECT LAST_INSERT_ID() as `id` FROM categories")->fetch();
        header("Location:edit.php?id={$categoryId['id']}");
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
                <h1 class="fs-3 fw-bold">ایجاد دسته بندی</h1>
            </div>

            <!-- Posts -->
            <div class="mt-4">
                <form class="row g-4" method="POST">
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">عنوان دسته بندی</label>
                        <input type="text" name="title" class="form-control"/>
                        <div class="form-text text-danger"><?= $errMessage ?></div>
                    </div>

                    <div class="col-12">
                        <button type="submit" name="addCategory" class="btn btn-dark">ایجاد</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<?php
include '../../include/layout/footer.php';
?>
