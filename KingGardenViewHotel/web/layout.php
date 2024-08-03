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
    <?= $extra_css; ?>
    <script src="<?= WEB_BASE_URL ?>js/jquery-3.7.1.min.js"></script>
</head>

<body>
    <!-- Spinner Start -->
    <div id="overlay">
        <div class="d-flex justify-content-center" id="spinner">
            <div class="spinner-border" role="status">
            </div>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- Nav Start -->
    <div class="row" id="navbar" style="z-index:99;">
        <div class="col-3" id="logo"><img src="<?= BASE_URL . '/img/common/logo_white.png' ?>" alt="logo" style="width:50%;"></div>
        <div class="col-1 nav-item" id="home">Home</div>
        <div class="col-1 nav-item" id="about">About Us</div>
        <div class="col-1 nav-item" id="rooms">Rooms</div>
        <div class="col-1 nav-item" id="blog">Blog</div>
        <div class="col-1 nav-item" id="contact">Contact Us</div>
        <div class="col-2"></div>

        <?php
        session_start();
        if (!isset($_SESSION['user_id'])) {
            echo '<div class="col-1 success-btn" id="login">Login</div>';
            echo '<div class="col-1 nav-item" id="register">Register</div>';
        } else {
            echo '<div class="col-1 fail-btn" id="logout">Logout</div>';
            echo '<div class="col-1 nav-item" id="dashboard">Dashboard</div>';
        }
        ?>

    </div>
    <!-- Nav End -->

    <!-- Page Start -->
    <?= $page_content; ?>
    <!-- Page End -->

    <!-- Footer Start -->
    <div class="row" id="footer" style="z-index:99;">
        <div class="col-2 nav-item" id="call"><i class="material-icons">call</i>+94-35-22-34654</div>
        <div class="col-3 nav-item" id="emails"><i class="material-icons">alternate_email</i>kinggardenviewhotel@mail.com</div>
        <div class="col-4 nav-item" id="address"><i class="material-icons">home</i>No45, Nuwaraeliya Road, Keppetipola, Sri Lanka</div>
        <div class="col-3 nav-item" id="design"><i class="material-icons">palette</i>Developed By Shiwantha_Rodrigo</div>
    </div>
    <!-- Footer End -->

    <!-- JavaScript -->
    <script src="<?= WEB_BASE_URL ?>js/bootstrap.min.js"></script>
    <script src="<?= WEB_BASE_URL ?>js/sweetalert2@11.js"></script>
    <script src="<?= WEB_BASE_URL ?>js/common.js"></script>
    <?= $extra_js; ?>

</body>

</html>