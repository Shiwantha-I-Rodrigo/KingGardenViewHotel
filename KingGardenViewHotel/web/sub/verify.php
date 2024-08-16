<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/common.php';
session_start();
$url =  basename($_SERVER['REQUEST_URI']);
$url_componenets = parse_url($url);
parse_str($url_componenets['query'], $params);

$id = $params['id'];
$token = $params['token'];

$db = dbConn();
$sql = "SELECT * FROM customers WHERE UserId=$id";
$result = $db->query($sql);
if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    if ($row['Token'] == $token) {
        $req = "UPDATE users SET UserStatus = 1 WHERE UserId = $id";
        $res = $db->query($req);
        if ($res) {
            $req = "INSERT INTO messages (MessageText, MessageTime, FromId, FromName, ToId, Thread, MessageStatus) VALUES ('Welcome to King garden View Hotel, please contact me for any inquiries.', time(), 9, 'anny',$id, 9, 1)";
            $res = $db->query($req);
            $_SESSION['alert_color'] = "var(--primary)";
            $_SESSION['alert_icon'] = "task_alt";
            $_SESSION['alert_title'] = "Verification Succesful !";
            $_SESSION['alert_msg'] = "your account verification was succesful !<br>please <a href='/web/modules/login.php'>login</a> to continue..";
            reDirect('/web/sub/alert.php');
        } else {
            $_SESSION['alert_color'] = "var(--fail)";
            $_SESSION['alert_icon'] = "error";
            $_SESSION['alert_title'] = "Verification Failed !";
            $_SESSION['alert_msg'] = "your account verification was unsuccesful !<br>please contact the hotel for further assistance.<br>Tel : +94-35-22-34654";
            reDirect('/web/sub/alert.php');
        }
    } else {
        $_SESSION['alert_color'] = "var(--fail)";
        $_SESSION['alert_icon'] = "error";
        $_SESSION['alert_title'] = "Verification Failed !";
        $_SESSION['alert_msg'] = "your account verification was unsuccesful !<br><strong>make sure to copy the entire link if you are copy pasting the link<strong> or please contact the hotel for further assistance.<br>Tel : +94-35-22-34654";
        reDirect('/web/sub/alert.php');
    }
} else {
    $_SESSION['alert_color'] = "var(--fail)";
    $_SESSION['alert_icon'] = "error";
    $_SESSION['alert_title'] = "Verification Failed !";
    $_SESSION['alert_msg'] = "your account verification was unsuccesful !<br>please contact the hotel for further assistance.<br>Tel : +94-35-22-34654";
    reDirect('/web/sub/alert.php');
}
