<?php
session_start();
$path = $_SERVER['REQUEST_URI'];
if (str_contains($path , 'pages')) {
    include '../../include/connection.php';
} else {
    include './include/connection.php';
}
$siteData = $conn->query("SELECT url , blog_name FROM site_data");
$siteData = $siteData->fetch();
if (!isset($_SESSION['email'])) {
    header("Location:{$siteData['url']}admin-panel/pages/auth/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html dir="rtl" lang="fa">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title><?= $siteData['blog_name'] ?></title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous"/>
        <link rel="stylesheet" href="<?= $siteData['url'] ?>admin-panel/assets/css/style.css" />
    </head>

    <body>
        <header class="navbar sticky-top bg-secondary flex-md-nowrap p-0 shadow-sm">
            <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-5 text-white" href="index.html">پنل ادمین</a>

            <button class="ms-2 nav-link px-3 text-white d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
                <i class="bi bi-justify-left fs-2"></i>
            </button>
        </header>