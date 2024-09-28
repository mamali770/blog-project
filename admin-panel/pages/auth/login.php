<?php
session_start();
include '../../include/connection.php';
$siteData = $conn->query("SELECT blog_name , url FROM site_data")->fetch();
$errMessage = "";
if (isset($_POST['submitLogin'])) {
    $email = trim($_POST['email']);
    $pass = trim($_POST['password']);
    if (empty($email) || empty($pass)) {
        $errMessage = "فیلد ایمیل و رمز عبور ضروری است.";
    } else {
        $searchQuery = $conn->prepare("SELECT id FROM users WHERE email = :email AND password = :password");
        $searchQuery->execute(["email" => $email , "password" => $pass]);
        if ($searchQuery->rowCount() == 1) {
            $_SESSION['email'] = $email;
            header("Location:../../index.php");
        } else {
            $errMessage = "ایمیل یا رمزعبور وارد شده صحیح نیست.";
        }
    }
}
?>
<!DOCTYPE html>
<html dir="rtl" lang="fa">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title><?= $siteData['blog_name'] ?></title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous"/>
        <link rel="stylesheet" href="../../assets/css/style.css" />
    </head>
    <body class="auth">
        <main class="form-signin w-100 m-auto">
            <form method="POST">
                <div class="fs-2 fw-bold text-center mb-4"><?= $siteData['blog_name'] ?></div>
                <?php if ($errMessage != '') : ?>
                    <div class="alert alert-sm alert-danger"><?= $errMessage ?></div>
                <?php endif ?>
                <div class="mb-3">
                    <label class="form-label">ایمیل</label>
                    <input type="email" name="email" class="form-control" />
                </div>

                <div class="mb-3">
                    <label class="form-label">رمز عبور</label>
                    <input type="password" name="password" class="form-control" />
                </div>
                <button class="w-100 btn btn-dark mt-4" name="submitLogin" type="submit">ورود</button>
            </form>
        </main>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    </body>
</html>
