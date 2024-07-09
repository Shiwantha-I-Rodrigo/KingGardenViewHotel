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
function authorize($user_id = null, $module_id = null)
{
    $db = dbConn();
    $sql = "SELECT * FROM user_modules WHERE UserId='$user_id' AND ModuleId ='$module_id'";
    $result = $db->query($sql);
    if ($result->num_rows < 1) {
        reDirect(SYSTEM_BASE_URL . "401.php");
    };
}

//upload---------------------------------------------

function uploadFile($path = null, $files = null)
{
    $extensions = ['jpg', 'jpeg', 'png', 'gif'];

    $file_name = $files['file_upload']['name'];
    $file_tmp = $files['file_upload']['tmp_name'];
    $file_size = $files['file_upload']['size'];
    $file_ext = strtolower(end(explode('.', $files['file_upload']['name'])));
    $random = substr(md5(time() . $file_name), 0, 128);
    $file = $path . $random . "." . $file_ext;

    if (!in_array($file_ext, $extensions)) {
        reDirect('/web/sub/alert.php');
    }

    if ($file_size > 1500000) {
        reDirect('/web/sub/alert.php');
    }

    move_uploaded_file($file_tmp, $file);

    return $file;
}