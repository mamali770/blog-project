<?php

$categories = $conn->query("SELECT title FROM categories");

?>
<div class="col-lg-4">
    <!-- Sesrch Section -->
    <div class="card">
        <div class="card-body">
            <p class="fw-bold fs-6">جستجو در وبلاگ</p>
            <form action="search.php" method="GET">
                <div class="input-group mb-3">
                    <input type="text" name="search" class="form-control" placeholder="جستجو ..."/>
                    <button class="btn btn-secondary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Categories Section -->
    <div class="card mt-4">
        <div class="fw-bold fs-6 card-header">دسته بندی ها</div>
        <ul class="list-group list-group-flush p-0">
            <?php if ($categories->rowCount() > 0) : ?>
                <?php foreach ($categories as $category) : ?>
                    <li class="list-group-item">
                        <a class="link-body-emphasis text-decoration-none" href="index.php?category=<?= $category['title'] ?>"><?= $category['title'] ?></a>
                    </li>
                <?php endforeach ?>
            <?php endif ?>
        </ul>
    </div>

    <!-- Subscribue Section -->
    <div class="card mt-4">
        <div class="card-body">
            <p class="fw-bold fs-6">عضویت در خبرنامه</p>
            <?php
                $message = [
                    "invalName" => "",
                    "invalEmail" => "",
                    "successfully" => ""
                ];
                if (isset($_POST['subscriberSub'])) {
                    $name = trim($_POST['name']);
                    $email = trim($_POST['email']);
                    if (empty($name) || strlen($name) < 3) {
                        $message['invalName'] = 'فیلد نام ضروری است.';
                    } elseif (empty($email) || strlen($email) < 7) {
                        $message['invalEmail'] = 'فیلد ایمیل ضروری است.';
                    } else {
                        $insertSubscriber = $conn->prepare("INSERT INTO subscribers (name , email) VALUES (:name , :email)");
                        $insertSubscriber->execute([
                            "name" => $name,
                            "email" => $email
                        ]);
                        $message['successfully'] = 'عضویت در خبرنامه با موفقیت انجام شد.';
                    }
                }
            ?>

            <form method="POST">
                <div class="text-success"><?= $message['successfully'] ?></div>
                <div class="mb-3">
                    <label class="form-label">نام</label>
                    <input type="text" name="name" class="form-control"/>
                    <div class="form-text text-danger"><?= $message['invalName'] ?></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">ایمیل</label>
                    <input type="email" name="email" class="form-control"/>
                    <div class="form-text text-danger"><?= $message['invalEmail'] ?></div>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" name="subscriberSub" class="btn btn-secondary">ارسال</button>
                </div>
            </form>
        </div>
    </div>

    <!-- About Section -->
    <div class="card mt-4">
        <div class="card-body">
            <p class="fw-bold fs-6">درباره ما</p>
            <p class="text-justify"><?= $siteData['about_us'] ?></p>
        </div>
    </div>
</div>