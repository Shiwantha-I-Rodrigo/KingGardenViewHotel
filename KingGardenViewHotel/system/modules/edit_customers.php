<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/common.php';
isset($_SESSION['user_id']) ? $user_id = $_SESSION['user_id'] : reDirect("/web/modules/login.php");
authorize($user_id, '3', 'system');
$extra_js = '<script src="' . SYSTEM_BASE_URL . 'js/edit_customers.js"></script>';
$extra_css = '';

$url =  basename($_SERVER['REQUEST_URI']);
$url_componenets = parse_url($url);
parse_str($url_componenets['query'], $params);
isset($params['id']) ? $customer_id = $params['id'] : $customer_id = 0;

$db = dbConn();

if ($customer_id != 0) {
    $sql = "SELECT * FROM customers c INNER JOIN users u ON c.UserId = u.UserId WHERE u.UserId = $customer_id";
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
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    extract($_POST);

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

            if ($customer_id != 0) {
                isset($change_pw) && $password != '' ? $pw_hash = password_hash($password, PASSWORD_BCRYPT) : $pw_hash = $current_password;
                $sql = "UPDATE users SET `UserName`='$user_name', `Password`='$pw_hash',`Email`='$email' , `Type`= 1 WHERE UserId=$customer_id";
                $db->query($sql);

                $reg_no = time() . "_" . $user_id;
                $token = md5(uniqid());

                $sql = "UPDATE customers SET `FirstName`='$first_name', `LastName`='$last_name', `AddressLine1`='$address_1', `AddressLine2`='$address_2', `AddressLine3`='$address_3', `Telephone`='$telephone', `Mobile`='$mobile', `Title`='$title', `RegNo`='$reg_no' $upload WHERE `UserId`='$customer_id'";
                $db->query($sql);

                $sql = "DELETE FROM user_modules WHERE UserId = $customer_id";
                $result = $db->query($sql);
            } else {
                $pw_hash = password_hash($password, PASSWORD_BCRYPT);
                $sql = "INSERT INTO users (`UserName`, `Password`, `Email`, `Type`, `UserStatus`) VALUES ('$user_name', '$pw_hash', '$email', 1, 0)";
                $db->query($sql);

                $customer_id = $db->insert_id;
                $reg_no = time() . "_" . $user_id;
                $token = md5(uniqid());

                $sql = "INSERT INTO customers (`FirstName`, `LastName`, `AddressLine1`, `AddressLine2`, `AddressLine3`, `Telephone`, `Mobile`, `Title`, `RegNo`, `ProfilePic`, `CustomerStatus`, `UserId`) VALUES
                 ('$first_name','$last_name','$address_1', '$address_2', '$address_3', '$telephone', '$mobile', '$title', '$reg_no', '$full_path', 0,$customer_id)";
                $db->query($sql);

            }

            $permissions = array();
            switch ($type) {
                case 1:
                    $permissions = [1, 2];
                    break;
                case 2:
                    $permissions = [1, 2, 6];
                    break;
                case 3:
                    $permissions = [1, 2, 3, 4, 6, 7, 8, 11];
                    break;
                case 4:
                    $permissions = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11];
                    break;
                case 5:
                    $permissions = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11];
                    break;
                default:
                    $permissions = [1, 2];
                    break;
            }

            foreach ($permissions as $key) {
                $sql = "INSERT INTO user_modules (UserId, ModuleId, User_ModuleStatus) VALUES ($customer_id, $key, 1)";
                $result = $db->query($sql);
            }

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
                <img src="<?= $profile_pic ?>" alt="_" class="rounded-circle img-fluid" style="width: 150px;">
            </div>
        </div>
        <h2 class="d-flex justify-content-center align-items-center my-4" style="font-size:3vh;">User Information</h2>
        <form id="reg_form" enctype="multipart/form-data" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $customer_id ; ?>" method="post" role="form" novalidate>

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
                    <label class="form-check-label ms-3 " id="change_pw_label" for="change_pw">Change Password</label>
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
                <button class="success-btn px-5 mx-4" name="submit_btn" id="submit_btn" data-bs-toggle="modal" data-bs-target="#EditConfirm" disabled>Submit</button>
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

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/system/sub/modals.php';
$page_content = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/system/layout.php';
?>