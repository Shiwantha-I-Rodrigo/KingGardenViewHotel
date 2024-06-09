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
                    <img src="<?= BASE_URL . '/img/common/logo_big_embosed.png' ?>" alt="logo" style="height:75px;">
                </div>
            </div>
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" role="form" novalidate>
                <div class="row my-2">
                    <div class="col-4 d-flex justify-content-end align-items-center">
                        <label>Username</label>
                    </div>
                    <div class="col-6 d-flex">
                        <input type="text" class="form-control inputs" name="user_name" id="user_name" placeholder="Username or Email" required />
                    </div>
                </div>
                <div class="row my-4">
                    <div class="col-4 d-flex justify-content-end align-items-center">
                        <label>Password</label>
                    </div>
                    <div class="col-6 d-flex">
                        <input type="text" class="form-control inputs" name="user_name" id="user_name" placeholder="Password" required />
                    </div>
                </div>
                <div class="row my-4">
                    <div class="col-10 d-flex justify-content-end">
                        <button class="success-btn px-5 mx-4">Submit</button>
                        <button class="fail-btn px-5">Cancel</button>
                    </div>
                </div>
            </form>
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