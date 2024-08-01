<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/common.php';
isset($_SESSION['user_id']) ? $user_id = $_SESSION['user_id'] : reDirect("/web/modules/login.php");
authorize($user_id, '1', 'web');
$extra_js = '<script src="' . WEB_BASE_URL . 'js/edit_user.js"></script>';
$extra_css = '';
$db = dbConn();
$sql = "SELECT * FROM customers c INNER JOIN users u ON c.UserId = u.UserId WHERE u.UserId = $user_id";
$result = $db->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['ProfilePic'] != "" ? $profile_pic = $row['ProfilePic'] : $profile_pic = "/img/users/default.png";
        $title = getTitle($row['Title']);
        $name = $title . $row['FirstName'] . " " . $row['LastName'];
        $telephone = $row['Telephone'];
        $mobile = $row['Mobile'];
        $address = $row['AddressLine1'] . ", " . $row['AddressLine2'] . ", " . $row['AddressLine3'];
        $reg_no = $row['RegNo'];
        $status = getStatus($row['UserStatus']);
        $email = $row['Email'];
        $username = $row['UserName'];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    extract($_POST);

    $db = dbConn();
    $sql = "SELECT * FROM users WHERE Email='$email'";
    $result = $db->query($sql);
    if ($result->num_rows > 0) {
        $_SESSION['alert_color'] = "var(--fail)";
        $_SESSION['alert_icon'] = "error";
        $_SESSION['alert_title'] = "Error";
        $_SESSION['alert_msg'] = 'The email address provided has an account associated,<br> please <a href="/web/modules/login.php">log in</a> to continue, or use another email address.';
        reDirect('/web/sub/alert.php');
    } else {
        $db = dbConn();
        $sql = "SELECT * FROM users WHERE UserName='$user_name'";
        $result = $db->query($sql);
        if ($result->num_rows > 0) {
            $_SESSION['alert_color'] = "var(--fail)";
            $_SESSION['alert_icon'] = "error";
            $_SESSION['alert_title'] = "Error";
            $_SESSION['alert_msg'] =  'The username provided has an account associated,<br> please <a href="/web/modules/login.php">log in</a> to continue, or use another username.';
            reDirect('/web/sub/alert.php');
        } else {
            $full_path = "";

            if (isset($_FILES['file_upload'])) {
                $path =  $_SERVER['DOCUMENT_ROOT'] . '/img/users/';
                $file = uploadFile($path, $_FILES, "web");
                $full_path = '/img/users/' . $file;
            }

            $pw_hash = password_hash($password, PASSWORD_BCRYPT);
            $db = dbConn();
            $sql = "INSERT INTO `users`(`UserName`, `Password`,`Email`,`Type`,`UserStatus`) VALUES ('$user_name','$pw_hash','$email',1,0)";
            $db->query($sql);

            $user_id = $db->insert_id;
            $reg_no = date('Y') . date('m') . $user_id;
            $token = md5(uniqid());

            $sql = "INSERT INTO `customers`(`FirstName`, `LastName`, `AddressLine1`, `AddressLine2`, `AddressLine3`, `Telephone`, `Mobile`, `Title`, `RegNo`,`ProfilePic`, `UserId`, `Token`, `CustomerStatus`) VALUES ('$first_name','$last_name','$address_1','$address_2','$address_3','$telephone','$mobile','$title','$reg_no','$file','$user_id','$token',0)";
            $db->query($sql);

            $msg = "<h1>SUCCESS</h1>";
            $msg .= "<h2>Congratulations</h2>";
            $msg .= "<p>Your account has been successfully created</p>";
            $msg .= "Click the following link to verify your email:\n";
            $msg .= $_SERVER['SERVER_NAME'] . "/web/sub/verify.php?id=$user_id&token=$token";
            sendEmail($email, $first_name, "Account Verification", $msg);
            $_SESSION['alert_color'] = "var(--primary)";
            $_SESSION['alert_icon'] = "task_alt";
            $_SESSION['alert_title'] = "Registration Succesful !";
            $_SESSION['alert_msg'] = "Hi, " . $user_name . " your registration was submitted succesfully,<br>please complete account verification using<br>instructions sent to the provided email address.<br>Registration no : " . $reg_no;
            reDirect('/web/sub/alert.php');
        }
    }
}

ob_start();
?>

<div class="container mt-5 p-5">
    <div class="card">
        <div class="row">
            <div class="col-2 p-4">
                <a href="/index.php"><i class="material-icons">home</i>Back Home</a>
            </div>
            <div class="col-8 d-flex justify-content-center mt-4">
                <img src="<?= BASE_URL . '/img/common/logo_logo.png' ?>" alt="logo" style="height:75px;">
            </div>
        </div>
        <h2 class="d-flex justify-content-center align-items-center my-5">Registration</h2>
        <form id="reg_form" enctype="multipart/form-data" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" role="form" novalidate>

            <div class="row mx-5">
                <div class="col-6 d-flex justify-content-start align-items-bottom">
                    <label>First Name</label>
                </div>
                <div class="col-6 d-flex justify-content-start align-items-bottom">
                    <label>Last Name</label>
                </div>
            </div>
            <div class="row mx-5">
                <div class="col-1 d-flex justify-content-start align-items-center">
                    <select name="title" id="title">
                        <option selected value="0">Title</option>
                        <option value="1">Mr.</option>
                        <option value="2">Mrs.</option>
                        <option value="3">Ms.</option>
                        <option value="4">Dr.</option>
                        <option value="5">Ven.</option>
                        <option value="6">Other.</option>
                    </select>
                </div>
                <div class="col-5 d-flex justify-content-end align-items-center">
                    <input type="text" class="fail-glow" name="first_name" id="first_name" placeholder="First Name" required />
                </div>
                <div class="col-6 d-flex justify-content-end align-items-center">
                    <input type="text" class="fail-glow" name="last_name" id="last_name" placeholder="Last Name" required />
                </div>
            </div>
            <div class="row mx-5">
                <div class="col-6 d-flex justify-content-start align-items-bottom">
                    <label>Username</label>
                </div>
                <div class="col-6 d-flex justify-content-start align-items-bottom">
                    <label>Email</label>
                </div>
            </div>
            <div class="row mx-5">
                <div class="col-6 d-flex justify-content-end align-items-center">
                    <input type="text" class="fail-glow" name="user_name" id="user_name" placeholder="Username (at least 4 characters long)" required />
                </div>
                <div class="col-6 d-flex justify-content-end align-items-center">
                    <input type="text" class="fail-glow" name="email" id="email" placeholder="Email" required />
                </div>
            </div>
            <div class="row mx-5">
                <div class="col-6 d-flex justify-content-start align-items-bottom">
                    <label>Password</label>
                </div>
                <div class="col-6 d-flex justify-content-start align-items-bottom">
                    <label>Confirm Password</label>
                </div>
            </div>
            <div class="row mx-5">
                <div class="col-6 d-flex justify-content-end align-items-center">
                    <input type="password" class="fail-glow" name="password" id="password" placeholder="Password" required />
                </div>
                <div class="col-6 d-flex justify-content-end align-items-center">
                    <input type="password" class="fail-glow" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required />
                </div>
            </div>
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
            <div class="row mx-5">
                <div class="col-6 d-flex justify-content-start align-items-bottom">
                    <label>Address</label>
                </div>
                <div class="col-6 d-flex justify-content-start align-items-bottom">
                    <label>Profile Picture</label>
                </div>
            </div>
            <div class="row mx-5">
                <div class="col-6 d-flex justify-content-end align-items-center">
                    <input type="text" name="address_1" id="address_1" placeholder="House No. & Street" />
                </div>
                <div class="col-6 d-flex justify-content-end align-items-center">
                    <input type="file" id="file_upload" name="file_upload" accept="image/*" />
                </div>
            </div>
            <div class="row mx-5">
                <div class="col-6 d-flex justify-content-end align-items-center">
                    <input type="text" name="address_2" id="address_2" placeholder="City" />
                </div>
            </div>
            <div class="row mx-5">
                <div class="col-6 d-flex justify-content-end align-items-center">
                    <input type="text" name="address_3" id="address_3" placeholder="Province" />
                </div>
            </div>
            <div class="row mx-5">
                <div class="col-6 d-flex justify-content-start align-items-bottom">
                    <label>Telephone</label>
                </div>
                <div class="col-6 d-flex justify-content-start align-items-bottom">
                    <label>Mobile</label>
                </div>
            </div>
            <div class="row mx-5">
                <div class="col-6 d-flex justify-content-end align-items-center">
                    <input type="text" name="telephone" id="telephone" placeholder="Telephone" />
                </div>
                <div class="col-6 d-flex justify-content-end align-items-center">
                    <input type="text" name="mobile" id="mobile" placeholder="Mobile" />
                </div>
            </div>
        </form>
        <div class="row my-4 mx-5">
            <div class="col-12 d-flex justify-content-end">
                <button class="success-btn px-5 mx-4" name="submit_btn" id="submit_btn" data-bs-toggle="modal" data-bs-target="#Confirm" disabled>Submit</button>
                <button class="fail-btn px-5" id="cancel_btn">Cancel</button>
            </div>
        </div>
        <div class="row my-4 mx-5">
            <div class="col-12">
                <p class="text-muted"> Already have an account ? <a href="login.php"> Login here </a></p>
                <p class="text-muted"> Required fields are indicated by red color </p>
                <a href="index.php" class="small text-muted">Terms of use.</a>
                <a href="index.php" class="small text-muted">Privacy policy</a>
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
                <p>By submitting the registration, you are agreeing to the terms and conditions of registration !</p>
                <p>Are you sure you want to submit the registration ?</p>
                <a href="index.php" class="small">Terms of use.</a>
                <a href="index.php" class="small">Privacy policy.</a>
            </div>
            <div class="modal-footer">
                <button class="success-btn px-3" type="submit" form="reg_form" formmethod="post">Confirm</button>
            </div>
        </div>
    </div>
</div>

<?php
$page_content = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/web/layout.php';
?>