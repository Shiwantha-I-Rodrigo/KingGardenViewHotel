<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

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
