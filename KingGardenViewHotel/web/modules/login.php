<!DOCTYPE html>
<html lang="en">

<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/common.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    extract($_POST);
    $message = array();

    $db = dbConn();
    $sql = "SELECT * FROM users u INNER JOIN customers c ON u.UserId = c.UserId WHERE u.UserName='$user_name' OR u.Email='$user_name'";
    $result = $db->query($sql);
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['Password']) && $row['UserStatus'] == 1) {
            $_SESSION['user_id'] = $row['UserId'];
            $_SESSION['user_name'] = $row['UserName'];
            reDirect("/web/modules/dashboard.php");
        } else {
            $message['message'] = "Invalid User Name or Password...!";
        }
    } else {
        $message['message'] = "Invalid User Name or Password...!";
    }
}

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
            <div class="row">
                <div class="col-2 p-4">
                    <a href="/index.php"><i class="material-icons">home</i>Back Home</a>
                </div>
                <div class="col-8 d-flex justify-content-center mt-4">
                    <h2 class="d-flex justify-content-center align-items-center my-5">Log In</h2>
                </div>
            </div>
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" role="form" name="login_form" style="z-index:1;" novalidate>
                <div class="row my-2">
                    <div class="col-4 d-flex justify-content-end align-items-center">
                        <label>Username</label>
                    </div>
                    <div class="col-6 d-flex">
                        <input type="text" name="user_name" id="user_name" placeholder="Username or Email" required />
                    </div>
                </div>
                <div class="row my-4">
                    <div class="col-4 d-flex justify-content-end align-items-center">
                        <label>Password</label>
                    </div>
                    <div class="col-6 d-flex">
                        <input type="password" name="password" id="password" placeholder="Password" required />
                    </div>
                </div>
                <div class="row my-3">
                    <div class="col-12 d-flex justify-content-center">
                        <p style="color:var(--fail)"><?= @$message['message'] ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-10 d-flex justify-content-end">
                        <p class="text-muted"> Don't have an account ? <a href="register.php"> Register here </a></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-10 d-flex justify-content-end">
                        <p class="text-muted"> Forgot Password ? <a href="recover.php"> Recover password </a></p>
                    </div>
                </div>
                <div class="row mt-3 mb-5">
                    <div class="col-10 d-flex justify-content-end">
                        <button id="submit_btn" class="success-btn px-5 mx-4">Login</button>
                        <button id="clear_btn" class="fail-btn px-5">Clear</button>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-12 d-flex justify-content-center">
                    <img src="<?= BASE_URL . '/img/common/logo_white_outline.png' ?>" alt="logo" style="height:8vh;position:absolute;bottom:10px;left:10px;z-index: 2;">
                </div>
            </div>
            <div class="row">
                <div class="col-12 d-flex justify-content-center">
                    <img src="<?= BASE_URL . '/img/common/mountains_4.png' ?>" alt="mountains_1" style="width:100%; border-radius: 10px;position:absolute;bottom:0;left:0;z-index: 0;">
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="<?= WEB_BASE_URL ?>js/bootstrap.min.js"></script>
    <script src="<?= WEB_BASE_URL ?>js/jquery-3.7.1.min.js"></script>
    <script src="<?= WEB_BASE_URL ?>js/sweetalert2@11.js"></script>
    <script src="<?= WEB_BASE_URL ?>js/common.js"></script>
    <script src="<?= WEB_BASE_URL ?>js/login.js"></script>

</body>

</html>