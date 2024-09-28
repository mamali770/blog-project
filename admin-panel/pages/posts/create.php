<?php
include '../../include/layout/header.php';
$categories = $conn->query("SELECT * FROM categories");
// form validation
// 0 => desabele message & 1 => enabele message
$errMessage = [
    "title" => ['فیلد عنوان نمی تواند خالی باشد.' , 0],
    "author" => ['فیلد نویسنده نمی تواند خالی باشد.' , 0],
    "category" => ['فیلد دسته بندی نمی تواند خالی باشد.' , 0],
    "image" => ['فیلد تصویر مقاله نمی تواند خالی باشد.' , 0],
    "body" => ['فیلد متن مقاله نمی تواند خالی باشد.' , 0]
];
if (isset($_POST['addPost'])) {
    $isDataCorrect = true;
    $postData = [
        "title" => trim($_POST['title']),
        "author" => trim($_POST['author']),
        "category" => trim($_POST['category']),
        "image" => $_FILES['image']['name'],
        "body" => trim($_POST['body'])
    ];
    foreach ($postData as $key => $data) {
        if (empty($data)) {
            $errMessage[$key][1] = 1;
            $isDataCorrect = false;
        }
    }
    // insert post to data base
    if ($isDataCorrect) {
        $imageName = time() . "_" . $postData['image'];
        if (move_uploaded_file($_FILES['image']['tmp_name'] , "../../../upload/$imageName")) {
            $addPost = $conn->prepare("INSERT INTO posts (title , body , category_id , author , image) VALUES ( :title , :body , :category_id , :author , :image)");
            $addPost->execute([
                "title" => $postData['title'],
                "body" => $postData['body'],
                "category_id" => $postData['category'],
                "author" => $postData['author'],
                "image" => $imageName,
            ]);
            $postId = $conn->query("SELECT LAST_INSERT_ID() as `id` FROM posts")->fetch();
            header("Location:edit.php?id={$postId['id']}");
        }
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
                <h1 class="fs-3 fw-bold">ایجاد مقاله</h1>
            </div>

            <!-- Posts -->
            <div class="mt-4">
                <form class="row g-4" method="POST" enctype="multipart/form-data">
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">عنوان مقاله</label>
                        <input type="text" name="title" class="form-control" />
                        <div class="form-text text-danger"><?= ($errMessage['title'][1] == 1) ? $errMessage['title'][0] : '' ?></div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">نویسنده مقاله</label>
                        <input type="text" name="author" class="form-control" />
                        <div class="form-text text-danger"><?= ($errMessage['author'][1] == 1) ? $errMessage['author'][0] : '' ?></div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">دسته بندی مقاله</label>
                        <select name="category" class="form-select">
                            <?php if($categories->rowCount() > 0) : ?>
                                <?php foreach ($categories as $category) : ?>
                                <option value="<?= $category['id'] ?>"><?= $category['title'] ?></option>
                                <?php endforeach ?>
                            <?php endif ?>
                        </select>
                        <div class="form-text text-danger"><?= ($errMessage['category'][1] == 1) ? $errMessage['category'][0] : '' ?></div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <label for="formFile" class="form-label">تصویر مقاله</label>
                        <input class="form-control" name="image" type="file"/>
                        <div class="form-text text-danger"><?= ($errMessage['image'][1] == 1) ? $errMessage['image'][0] : '' ?></div>
                    </div>

                    <div class="col-12">
                        <label for="formFile" class="form-label">متن مقاله</label>
                        <textarea class="form-control" name="body" rows="6"></textarea>
                        <div class="form-text text-danger"><?= ($errMessage['body'][1] == 1) ? $errMessage['body'][0] : '' ?></div>
                    </div>

                    <div class="col-12">
                        <button type="submit" name="addPost" class="btn btn-dark">ایجاد</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<?php
include '../../include/layout/footer.php';
?>
