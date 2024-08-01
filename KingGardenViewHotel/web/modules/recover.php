<!DOCTYPE html>
<html lang="en">

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/common.php';
session_start();
$url =  basename($_SERVER['REQUEST_URI']);
$url_componenets = parse_url($url);
parse_str($url_componenets['query'], $params);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    extract($_POST);

    $db = dbConn();

    if (!isset($params['id']) && !isset($params['token']) && $password == '') {
        $id = $params['id'];
        $token = $params['id'];
        $sql = "SELECT * FROM users u JOIN customers c ON u.UserId=c.UserId WHERE u.Email='$email'";
        $result = $db->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user_id = $row['UserId'];
            $token = $row['Token'];
            $first_name = $row['FirstName'];
            $msg = "<h2>SUCCESS</h2>";
            $msg .= "<p>Hi, " . $first_name . "Your request has been successfully submitted</p>";
            $msg .= "Click the following link to reset your password:\n";
            $msg .= $_SERVER['SERVER_NAME'] . "/web/modules/recover.php?id=$user_id&token=$token";
            sendEmail($email, $first_name, "Password Recovery", $msg);
            $_SESSION['alert_color'] = "var(--primary)";
            $_SESSION['alert_icon'] = "task_alt";
            $_SESSION['alert_title'] = "Confirmation Email Sent !";
            $_SESSION['alert_msg'] = "Hi, " . $first_name . " your request was submitted succesfully,<br>please complete the password reset by using<br>instructions sent to the provided email address";
            reDirect('/web/sub/alert.php');
        } else {
            $_SESSION['alert_color'] = "var(--fail)";
            $_SESSION['alert_icon'] = "error";
            $_SESSION['alert_title'] = "Error";
            $_SESSION['alert_msg'] =  'The information provided does not have an account associated';
            reDirect('/web/sub/alert.php');
        }
    } else {
        echo $id .  "---" . $token;
        $sql = "SELECT * FROM users u JOIN customers c ON u.UserId=c.UserId WHERE u.UserId=$id";
        $result = $db->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['Token'] == $token) {
                $pw_hash = password_hash($password, PASSWORD_BCRYPT);
                $req = "UPDATE users SET Password='$pw_hash' WHERE UserId = $id";
                $res = $db->query($req);
                if ($res) {
                    $_SESSION['alert_color'] = "var(--primary)";
                    $_SESSION['alert_icon'] = "task_alt";
                    $_SESSION['alert_title'] = "Success !";
                    $_SESSION['alert_msg'] = "your password was succesfully updated !";
                    reDirect('/web/sub/alert.php');
                } else {
                    $_SESSION['alert_color'] = "var(--fail)";
                    $_SESSION['alert_icon'] = "error";
                    $_SESSION['alert_title'] = "Failed !";
                    $_SESSION['alert_msg'] = "password reset was unsuccesful !<br>please contact the hotel for further assistance.<br>Tel : +94-35-22-34654";
                    reDirect('/web/sub/alert.php');
                }
            }
        } else {
            $_SESSION['alert_color'] = "var(--fail)";
            $_SESSION['alert_icon'] = "error";
            $_SESSION['alert_title'] = "Error";
            $_SESSION['alert_msg'] =  'Unable to find the account associated';
            //reDirect('/web/sub/alert.php');
        }
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
            </div>
            <h2 class="d-flex justify-content-center align-items-center my-3">Password Recovery</h2>
            <form id="reg_form" enctype="multipart/form-data" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" role="form" novalidate>
                <div class="row mx-5 <?php if (isset($params['id'])) {
                                            echo 'd-none';
                                        } ?>">
                    <div class="col-6 d-flex justify-content-start align-items-bottom">
                        <label>Email</label>
                    </div>
                    <div class="col-6 d-flex justify-content-start align-items-bottom">
                        <label>Confirm Email</label>
                    </div>
                </div>
                <div class="row mx-5 <?php if (isset($params['id'])) {
                                            echo 'd-none';
                                        } ?>">
                    <div class="col-6 d-flex justify-content-end align-items-center">
                        <input type="email" class="fail-glow" name="email" id="email" placeholder="Email" required />
                    </div>
                    <div class="col-6 d-flex justify-content-end align-items-center">
                        <input type="email" class="fail-glow" name="confirm_email" id="confirm_email" placeholder="Confirm Email" required />
                    </div>
                </div>
                <div class="row mx-5 <?php if (!isset($params['id'])) {
                                            echo 'd-none';
                                        } ?>">
                    <div class="col-6 d-flex justify-content-start align-items-bottom">
                        <label>Password</label>
                    </div>
                    <div class="col-6 d-flex justify-content-start align-items-bottom">
                        <label>Confirm Password</label>
                    </div>
                </div>
                <div class="row mx-5 <?php if (!isset($params['id'])) {
                                            echo 'd-none';
                                        } ?>">
                    <div class="col-6 d-flex justify-content-end align-items-center">
                        <input type="password" class="fail-glow" name="password" id="password" placeholder="Password" required />
                    </div>
                    <div class="col-6 d-flex justify-content-end align-items-center">
                        <input type="password" class="fail-glow" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required />
                    </div>
                </div>
                <input type="text" class="d-none" name="token" id="token" value='<?php if (isset($params['token'])) {
                                                                                        echo $params['token'];
                                                                                    } ?>' required />
                <input type="text" class="d-none" name="id" id="id" value='<?php if (isset($params['id'])) {
                                                                                echo $params['id'];
                                                                            } ?>' required />
                <div class="row mx-5">
                    <div class="alert col-12 d-none" role="alert" id="password_meter">
                        <ul class="list-unstyled">
                            <li class="requirements">
                                <i class="material-icons success" style="font-size: 24px;" id="length_tick">check_circle</i>
                                <i class="material-icons fail" style="font-size: 24px;" id="length_cross">cancel</i>
                                <label>must have at least 8 chars</label>
                            </li>
                            <li class="requirements">
                                <i class="material-icons success" style="font-size: 24px;" id="upper_tick">check_circle</i>
                                <i class="material-icons fail" style="font-size: 24px;" id="upper_cross">cancel</i>
                                <label>must have a uppercase letter</label>
                            </li>
                            <li class="requirements">
                                <i class="material-icons success" style="font-size: 24px;" id="lower_tick">check_circle</i>
                                <i class="material-icons fail" style="font-size: 24px;" id="lower_cross">cancel</i>
                                <label>must have a lowercase letter</label>
                            </li>
                            <li class="requirements">
                                <i class="material-icons success" style="font-size: 24px;" id="number_tick">check_circle</i>
                                <i class="material-icons fail" style="font-size: 24px;" id="number_cross">cancel</i>
                                <label>must have a number</label>
                            </li>
                            <li class="requirements">
                                <i class="material-icons success" style="font-size: 24px;" id="char_tick">check_circle</i>
                                <i class="material-icons fail" style="font-size: 24px;" id="char_cross">cancel</i>
                                <label>must have a special character</label>
                            </li>
                        </ul>
                    </div>
                </div>
            </form>
            <div class="row mt-4 mx-5">
                <div class="col-12 d-flex justify-content-end">
                    <button class="success-btn px-5 mx-4 <?php if (isset($params['id'])) {
                                                                echo 'd-none';
                                                            } ?>" name="send_btn" id="send_btn" type="submit" form="reg_form" formmethod="post" disabled>Send Mail</button>
                    <button class="success-btn px-5 mx-4 <?php if (!isset($params['id'])) {
                                                                echo 'd-none';
                                                            } ?>" name="submit_btn" id="submit_btn" data-bs-toggle="modal" data-bs-target="#Confirm" disabled>Submit</button>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <img src="<?= BASE_URL . '/img/common/logo_white_outline.png' ?>" alt="logo" style="height:70px;position:absolute;bottom:10px;left:10px;z-index: 2;">
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <img src="<?= BASE_URL . '/img/common/mountains_4.png' ?>" alt="mountains_1" style="width:100%; border-radius: 10px;">
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="Confirm" tabindex="-1" aria-labelledby="Confirm" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color:var(--background);">
                <div class="modal-header d-flex justify-content-between">
                    <img src="<?= BASE_URL . '/img/common/logo_logo.png' ?>" alt="" style="width: 3vw; height: 5vh; object-fit: cover;">
                    <label class="mx-3" style="font-size:3vh;">Confirmation</label>
                    <button type="button" class="clear_btn" data-bs-dismiss="modal"><i class="material-icons">cancel</i></button>
                </div>
                <div class="modal-body" style="font-weight: normal; color:var(--primary_font); text-align: justify; text-justify: inter-word;">
                    <p>Are you sure you want to set the new password ?</p>
                </div>
                <div class="modal-footer">
                    <button class="success-btn px-3" type="submit" form="reg_form" formmethod="post">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="<?= WEB_BASE_URL ?>js/bootstrap.min.js"></script>
    <script src="<?= WEB_BASE_URL ?>js/jquery-3.7.1.min.js"></script>
    <script src="<?= WEB_BASE_URL ?>js/sweetalert2@11.js"></script>
    <script src="<?= WEB_BASE_URL ?>js/common.js"></script>
    <script src="<?= WEB_BASE_URL ?>js/recover.js"></script>

</body>

</html>