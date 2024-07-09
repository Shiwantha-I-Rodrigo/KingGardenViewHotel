<!DOCTYPE html>
<html lang="en">

<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
$icon = $_SESSION['alert_icon'];
$title = $_SESSION['alert_title'];
$msg = $_SESSION['alert_msg'];
$color = $_SESSION['alert_color'];
?>

<head>
    <title>kgvh</title>
    <meta charset="utf-8">
    <link href="<?= WEB_BASE_URL ?>images/favicon.ico" rel="icon">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <!-- Style Sheets -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="<?= WEB_BASE_URL ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= WEB_BASE_URL ?>css/common.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-1 p-5">
        <div class="card" style="background-color:<?= $color ?>;">
            <a href="/index.php" class="pt-3 ps-3"><i class="material-icons">home</i>Back Home</a>
            <div class="row m-2">
                <div class="col-12 d-flex justify-content-center">
                    <div class="col-2 m-0 p-0 d-flex justify-content-center" id="call"><i class="material-icons" style="font-size: 120px;"><?= $icon ?></i></div>
                </div>
            </div>
            <div class="row m-1">
                <div class="col-12 d-flex justify-content-center">
                    <h2 style="font-size:5vh;"><?= $title ?></h2>
                </div>
            </div>
            <div class="row m-1">
                <div class="col-12 d-flex justify-content-center">
                    <p style="font-size:2vh; text-align:center;"><?= $msg ?></p>
                </div>
            </div>
            <div class="row">
                <div class="col-12 d-flex justify-content-center">
                    <img src="<?= BASE_URL . '/img/common/mountains_4.png' ?>" alt="mountains_1" style="width:100%; border-radius: 10px;">
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="<?= WEB_BASE_URL ?>js/bootstrap.min.js"></script>
    <script src="<?= WEB_BASE_URL ?>js/jquery-3.7.1.min.js"></script>
    <script src="<?= WEB_BASE_URL ?>js/sweetalert2@11.js"></script>
    <script src="<?= WEB_BASE_URL ?>js/common.js"></script>

</body>

</html>