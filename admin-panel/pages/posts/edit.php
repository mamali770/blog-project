<?php
include '../../include/layout/header.php';
$post = [];
if (isset($_GET['id'])) {
    $categories = $conn->query("SELECT * FROM categories");
    $post = $conn->prepare("SELECT * FROM posts WHERE id = :id");
    $post->execute(["id" => $_GET['id']]);
    $post = $post->fetch();
}
// form validation
// 0 => desabele message & 1 => enabele message
$errMessage = [
    "title" => ['فیلد عنوان نمی تواند خالی باشد.' , 0],
    "author" => ['فیلد نویسنده نمی تواند خالی باشد.' , 0],
    "category" => ['فیلد دسته بندی نمی تواند خالی باشد.' , 0],
    "body" => ['فیلد متن مقاله نمی تواند خالی باشد.' , 0]
];
if (isset($_POST['editPost'])) {
    $isDataCorrect = true;
    $postData = [
        "title" => trim($_POST['title']),
        "author" => trim($_POST['author']),
        "category" => trim($_POST['category']),
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
        if(!empty($_FILES['image']['name'])) {
            $imageName = time() . "_" . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'] , "../../../upload/$imageName");
            $editPost = $conn->prepare("UPDATE posts SET title = :title , body = :body , category_id = :category_id , author = :author , image = :image WHERE id = :id");
            $editPost->execute([
                "title" => $postData['title'],
                "body" => $postData['body'],
                "category_id" => $postData['category'],
                "author" => $postData['author'],
                "image" => $imageName,
                "id" => $_GET['id']
            ]);
        } else {
            $editPost = $conn->prepare("UPDATE posts SET title = :title , body = :body , category_id = :category_id , author = :author WHERE id = :id");
            $editPost->execute([
                "title" => $postData['title'],
                "body" => $postData['body'],
                "category_id" => $postData['category'],
                "author" => $postData['author'],
                "id" => $_GET['id']
            ]);
        }
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
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-5">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="fs-3 fw-bold">ویرایش مقاله</h1>
                    </div>

                    <!-- Posts -->
                    <div class="mt-4">
                        <?php if (count($post) > 0) : ?>
                        <form class="row g-4" method="POST" enctype="multipart/form-data">
                            <div class="col-12 col-sm-6 col-md-4">
                                <label class="form-label">عنوان مقاله</label>
                                <input type="text" name="title" class="form-control" value="<?= $post['title'] ?>"/>
                                <div class="form-text text-danger"><?= ($errMessage['title'][1] == 1) ? $errMessage['title'][0] : '' ?></div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4">
                                <label class="form-label">نویسنده مقاله</label>
                                <input type="text" name="author" class="form-control" value="<?= $post['author'] ?>"/>
                                <div class="form-text text-danger"><?= ($errMessage['author'][1] == 1) ? $errMessage['author'][0] : '' ?></div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4">
                                <label class="form-label">دسته بندی مقاله</label>
                                <select name="category" class="form-select">
                                <?php if($categories->rowCount() > 0) : ?>
                                    <?php foreach ($categories as $category) : ?>
                                    <option <?= ($post['category_id'] == $category['id']) ? 'selected' : '' ?> value="<?= $category['id'] ?>"><?= $category['title'] ?></option>
                                    <?php endforeach ?>
                                <?php endif ?>
                                </select>
                                <div class="form-text text-danger"><?= ($errMessage['category'][1] == 1) ? $errMessage['category'][0] : '' ?></div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4">
                                <label for="formFile" class="form-label">تصویر مقاله</label>
                                <input name="image" class="form-control" type="file"/>
                            </div>

                            <div class="col-12">
                                <label for="formFile" class="form-label">متن مقاله</label>
                                <textarea name="body" class="form-control" rows="8"><?= $post['body'] ?></textarea>
                                <div class="form-text text-danger"><?= ($errMessage['body'][1] == 1) ? $errMessage['body'][0] : '' ?></div>
                            </div>

                            
                            <div class="col-12 col-sm-6 col-md-4">
                                <img class="rounded" src="../../../upload/<?= $post['image'] ?>" width="300"/>
                            </div>

                            <div class="col-12">
                                <button name="editPost" type="submit" class="btn btn-dark">
                                    ویرایش
                                </button>
                            </div>
                        </form>
                        <?php else : ?>
                            <div class="col">
                                <div class="alert alert-danger">
                                    مقاله ای یافت نشد ....
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
