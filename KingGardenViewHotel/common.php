<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/mail.php';

//Create Database Conection-------------------
function dbConn()
{
    global $_DB_SERVER, $_DB_USERNAME, $_DB_PASSWORD, $_DB_NAME;
    $conn = new mysqli($_DB_SERVER, $_DB_USERNAME, $_DB_PASSWORD, $_DB_NAME);

    if ($conn->connect_error) {
        die("Database Error : " . $conn->connect_error);
    } else {
        return $conn;
    }
}

//Redirect---------------------------------------------
function reDirect($data = null)
{
    echo '<script type="text/javascript">window.location = "' . $data . '";</script>';
}

//Authorize---------------------------------------------
function authorize($user_id = null, $module_id = null, $sys = null)
{
    $db = dbConn();
    $sql = "SELECT * FROM user_modules WHERE UserId='$user_id' AND ModuleId ='$module_id'";
    $result = $db->query($sql);
    if ($result->num_rows < 1) {
        reDirect('/' . $sys . "/sub/401.php");
    };
}

//Upload---------------------------------------------
function uploadFile($path = null, $files = null, $sys = null)
{
    $extensions = ['jpg', 'jpeg', 'png', 'gif'];

    $file_name = $files['file_upload']['name'];
    $file_tmp = $files['file_upload']['tmp_name'];
    $file_size = $files['file_upload']['size'];
    $file_ext = strtolower(end(explode('.', $files['file_upload']['name'])));
    $random = substr(md5(time() . $file_name), 0, 128);
    $file = $path . $random . "." . $file_ext;

    if (!in_array($file_ext, $extensions)) {
        reDirect('/' . $sys . '/sub/alert.php');
    }

    if ($file_size > 1500000) {
        reDirect('/' . $sys . '/sub/alert.php');
    }

    move_uploaded_file($file_tmp, $file);

    return  $random . "." . $file_ext;
}

function uploadFiles($path = null, $files = null, $sys = null, $i = 0)
{
    $extensions = ['jpg', 'jpeg', 'png', 'gif'];

    $file_name = $files['file_upload']['name'][$i];
    $file_tmp = $files['file_upload']['tmp_name'][$i];
    $file_size = $files['file_upload']['size'][$i];
    $file_ext = strtolower(end(explode('.', $files['file_upload']['name'][$i])));
    $random = substr(md5(time() . $file_name), 0, 128);
    $file = $path . $random . "." . $file_ext;

    if (!in_array($file_ext, $extensions)) {
        reDirect('/' . $sys . '/sub/alert.php');
    }

    if ($file_size > 1500000) {
        reDirect('/' . $sys . '/sub/alert.php');
    }

    move_uploaded_file($file_tmp, $file);

    return  $random . "." . $file_ext;
}

//Title---------------------------------------------
function getTitle($data = null)
{
    $title = "";
    switch ($data) {
        case 0:
            $title = "";
            break;
        case 1:
            $title = "Mr. ";
            break;
        case 2:
            $title = "Mrs. ";
            break;
        case 3:
            $title = "Ms. ";
            break;
        case 4:
            $title = "Dr. ";
            break;
        case 5:
            $title = "Ven. ";
            break;
        case 6:
            $title = "";
            break;
    }

    return $title;
}

//Status---------------------------------------------
function getStatus($data = null)
{
    $status = "";
    switch ($data) {
        case 0:
            $status = "Checked Out";
            break;
        case 1:
            $status = "Active";
            break;
        case 2:
            $status = "Unavailable";
            break;
        case 3:
            $status = "Unauthorized";
            break;
        case 4:
            $status = "Invalid";
            break;
        case 5:
            $status = "Checked In";
            break;
        case 6:
            $status = "Discounted";
            break;
        case 7:
            $status = "Cancelled";
            break;
        case 8:
            $status = "No Show";
            break;
        case 9:
            $status = "Forbidden";
            break;
    }

    return $status;
}

function getItemStatus($data = null)
{
    $status = "";
    switch ($data) {
        case 0:
            $status = "Unpaid";
            break;
        case 1:
            $status = "Paid";
            break;
        case 2:
            $status = "pending";
            break;
        case 3:
            $status = "rejected";
            break;
        case 4:
            $status = "refunded";
            break;
        case 5:
            $status = "cancelled";
            break;
        case 6:
            $status = "partial";
            break;
    }

    return $status;
}

//Role---------------------------------------------
function getRole($data = null)
{
    $role = "";
    switch ($data) {
        case 0:
            $role = "Guest";
            break;
        case 1:
            $role = "Customer";
            break;
        case 2:
            $role = "Taxi";
            break;
        case 3:
            $role = "Receptionist";
            break;
        case 4:
            $role = "Manager";
            break;
        case 5:
            $role = "Admin";
            break;
    }

    return $role;
}

//Time---------------------------------------------
function getTime($data = null)
{
    return date("Y-m-d", substr((string)$data, 0, 10));
}

function getTimes($data = null)
{
    return date("jS \of F Y h:i:s A", substr((string)$data, 0, 10));
}

//SetHome---------------------------------------------
function setHome()
{
    echo '<script type="text/javascript">sessionStorage.setItem("current_page", "home");</script>';
}
