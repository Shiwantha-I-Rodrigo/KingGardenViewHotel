<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/common.php';
isset($_SESSION['user_id']) ? $user_id = $_SESSION['user_id'] : reDirect("/web/modules/login.php");
authorize($user_id, '1', 'web');
$extra_js = '<script src="' . SYSTEM_BASE_URL . 'js/edit_employees.js"></script>';
$extra_css = '';

$url =  basename($_SERVER['REQUEST_URI']);
$url_componenets = parse_url($url);
parse_str($url_componenets['query'], $params);
$employee_id = $params['id'];

$db = dbConn();
$sql = "SELECT * FROM employees c INNER JOIN users u ON c.UserId = u.UserId WHERE u.UserId = $employee_id";
$result = $db->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['ProfilePic'] != "" ? $profile_pic = $row['ProfilePic'] : $profile_pic = "/img/users/default.png";
        $title = $row['Title'];
        $first_name = $row['FirstName'];
        $last_name = $row['LastName'];
        $telephone = $row['Telephone'];
        $mobile = $row['Mobile'];
        $address_1 = $row['AddressLine1'];
        $address_2 =  $row['AddressLine2'];
        $address_3 =  $row['AddressLine3'];
        $reg_no = $row['RegNo'];
        $status = $row['UserStatus'];
        $email = $row['Email'];
        $user_name = $row['UserName'];
        $current_password = $row['Password'];
        $type = $row['Type'];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    extract($_POST);

    $db = dbConn();
    $sql = "SELECT * FROM users WHERE Email='$email'";
    $result = $db->query($sql);
    $row = $result->fetch_assoc();
    if ($result->num_rows > 0 && $email != $row['Email']) {
        $_SESSION['alert_color'] = "var(--fail)";
        $_SESSION['alert_icon'] = "error";
        $_SESSION['alert_title'] = "Error";
        $_SESSION['alert_msg'] = 'The email address provided has an account associated,<br> please <a href="/web/modules/login.php">log in</a> to continue, or use another email address.';
        reDirect('/web/sub/alert.php');
    } else {
        $sql = "SELECT * FROM users WHERE UserName='$user_name'";
        $result = $db->query($sql);
        $row = $result->fetch_assoc();
        if ($result->num_rows > 0 && $user_name != $row['UserName']) {
            $_SESSION['alert_color'] = "var(--fail)";
            $_SESSION['alert_icon'] = "error";
            $_SESSION['alert_title'] = "Error";
            $_SESSION['alert_msg'] =  'The username provided has an account associated,<br> please <a href="/web/modules/login.php">log in</a> to continue, or use another username.';
            reDirect('/web/sub/alert.php');
        } else {
            $upload = "";

            if (!empty($_FILES['file_upload']['name'])) {
                $path =  $_SERVER['DOCUMENT_ROOT'] . '/img/users/';
                $file = uploadFile($path, $_FILES, "system");
                $full_path = '/img/users/' . $file;
                $upload = ",`ProfilePic`='$full_path'";
            }

            isset($change_pw) && $password != '' ? $pw_hash = password_hash($password, PASSWORD_BCRYPT) : $pw_hash = $current_password;
            $sql = "UPDATE users SET `UserName`='$user_name', `Password`='$pw_hash',`Email`='$email' , `Type`=$type WHERE UserId=$employee_id";
            $db->query($sql);

            $reg_no = time(). "_" .$user_id;
            $token = md5(uniqid());

            $sql = "UPDATE employees SET `FirstName`='$first_name', `LastName`='$last_name', `AddressLine1`='$address_1', `AddressLine2`='$address_2', `AddressLine3`='$address_3', `Telephone`='$telephone', `Mobile`='$mobile', `Title`='$title', `RegNo`='$reg_no' $upload WHERE `UserId`='$employee_id'";
            $db->query($sql);

            $_SESSION['alert_color'] = "var(--primary)";
            $_SESSION['alert_icon'] = "task_alt";
            $_SESSION['alert_title'] = "Success !";
            $_SESSION['alert_msg'] = "The information was updated succesfully";
            reDirect('/system/sub/alert.php');
        }
    }
}

ob_start();
?>

<div class="container mt-5 p-5">
    <div class="card">
        <div class="row">
            <div class="col-12 d-flex justify-content-center mt-5">
                <img src="<?= $profile_pic ?>" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
            </div>
        </div>
        <h2 class="d-flex justify-content-center align-items-center my-4" style="font-size:3vh;">Update Employee Information</h2>
        <form id="reg_form" enctype="multipart/form-data" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $employee_id ; ?>" method="post" role="form" novalidate>

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
                        <option <?php echo ($title == 0) ? 'selected' : ''; ?> value="0">Title</option>
                        <option <?php echo ($title == 1) ? 'selected' : ''; ?> value="1">Mr.</option>
                        <option <?php echo ($title == 2) ? 'selected' : ''; ?> value="2">Mrs.</option>
                        <option <?php echo ($title == 3) ? 'selected' : ''; ?> value="3">Ms.</option>
                        <option <?php echo ($title == 4) ? 'selected' : ''; ?> value="4">Dr.</option>
                        <option <?php echo ($title == 5) ? 'selected' : ''; ?> value="5">Ven.</option>
                        <option <?php echo ($title == 6) ? 'selected' : ''; ?> value="6">Other.</option>
                    </select>
                </div>
                <div class="col-5 d-flex justify-content-end align-items-center">
                    <input type="text" name="first_name" id="first_name" value="<?= $first_name ?>" placeholder="First Name" required />
                </div>
                <div class="col-6 d-flex justify-content-end align-items-center">
                    <input type="text" name="last_name" id="last_name" value="<?= $last_name ?>" placeholder="Last Name" required />
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
                    <input type="text" name="user_name" id="user_name" value="<?= $user_name ?>" placeholder="Username (at least 4 characters long)" required />
                </div>
                <div class="col-6 d-flex justify-content-end align-items-center">
                    <input type="text" name="email" id="email" value="<?= $email ?>" placeholder="Email" required />
                </div>
            </div>
            <div class="row mx-5">
                <div class="col-6 d-flex justify-content-start align-items-bottom">
                    <label class="d-none" id="password_label">Password</label>
                </div>
                <div class="col-6 d-flex justify-content-start align-items-bottom">
                    <label class="d-none" id="confirm_password_label">Confirm Password</label>
                </div>
            </div>
            <div class="row mx-5">
                <div class="col-6 d-flex justify-content-end align-items-center">
                    <input class="d-none" type="password" class="fail-glow" name="password" id="password" placeholder="Password" required />
                </div>
                <div class="col-6 d-flex justify-content-end align-items-center">
                    <input class="d-none" type="password" class="fail-glow" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required />
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
                <div class="col-3 ms-3 my-3 d-flex justify-content-start align-items-bottom form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="change_pw">
                    <label class="form-check-label ms-3 " for="change_pw">Change Password</label>
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
                    <input type="text" name="address_1" id="address_1" value="<?= $address_1 ?>" placeholder="House No. & Street" />
                </div>
                <div class="col-6 d-flex justify-content-end align-items-center">
                    <input type="file" id="file_upload" name="file_upload" accept="image/*" />
                </div>
            </div>
            <div class="row mx-5">
                <div class="col-6 d-flex justify-content-end align-items-center">
                    <input type="text" name="address_2" id="address_2" value="<?= $address_2 ?>" placeholder="City" />
                </div>
                <div class="col-1 d-flex justify-content-start align-items-center">
                    <select name="type" id="type">
                        <option <?php echo ($type == 0) ? 'selected' : ''; ?> value="0">Guest</option>
                        <option <?php echo ($type == 1) ? 'selected' : ''; ?> value="1">Customer</option>
                        <option <?php echo ($type == 2) ? 'selected' : ''; ?> value="2">Taxi</option>
                        <option <?php echo ($type == 3) ? 'selected' : ''; ?> value="3">Receptionist</option>
                        <option <?php echo ($type == 4) ? 'selected' : ''; ?> value="4">Manager</option>
                        <option <?php echo ($type == 5) ? 'selected' : ''; ?> value="5">Admin</option>
                    </select>
                </div>
            </div>
            <div class="row mx-5">
                <div class="col-6 d-flex justify-content-end align-items-center">
                    <input type="text" name="address_3" id="address_3" value="<?= $address_3 ?>" placeholder="Province" />
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
                    <input type="text" name="telephone" id="telephone" value="<?= $telephone ?>" placeholder="Telephone" />
                </div>
                <div class="col-6 d-flex justify-content-end align-items-center">
                    <input type="text" name="mobile" id="mobile" value="<?= $mobile ?>" placeholder="Mobile" />
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
                <p> Required fields are indicated by red color </p>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <img src="<?= BASE_URL . '/img/common/logo_white_outline.png' ?>" alt="logo" style="height:70px;position:absolute;bottom:10px;left:10px;z-index: 2;">
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <img src="<?= BASE_URL . '/img/common/mountains_5.png' ?>" alt="mountains_1" style="width:100%; border-radius: 10px;">
            </div>
        </div>
    </div>
</div>
<div class="row" style="height:10vh;"></div>

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
                <p>Are you sure you want to submit the changes ?</p>
            </div>
            <div class="modal-footer">
                <button class="success-btn px-3" type="submit" form="reg_form" formmethod="post">Confirm</button>
            </div>
        </div>
    </div>
</div>

<?php
$page_content = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/system/layout.php';
?>