<!DOCTYPE html>
<html lang="en">

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
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

    <div class="container mt-5 p-5">
        <div class="card">
            <div class="row m-5">
                <div class="col-12 d-flex justify-content-center">
                    <div class="col-2 nav-item" id="call"><i class="material-icons" style="font-size: 96px;">not_listed_location</i></div>
                </div>
            </div>
            <div class="row m-1">
                <div class="col-12 d-flex justify-content-center">
                    <h2>Page Not Found</h2>
                </div>
            </div>
            <div class="row m-1">
                <div class="col-12 d-flex justify-content-center">
                    <h3>Can't seem to find the page you're looking for.</h3>
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