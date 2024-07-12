<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/common.php';
isset($_SESSION['user_id']) ? $user_id = $_SESSION['user_id'] : reDirect("/web/modules/login.php");
authorize($user_id, '1', 'web');

if (isset($_POST['req'])) {
    $req = $_POST['req'];
    $content = '';
    $per_page = 5;
    $item_count = 0;
    isset($_SESSION['msg_offset']) ? $msg_offset = $_SESSION['msg_offset'] : $_SESSION['msg_offset'] = 0;
    isset($_SESSION['past_offset']) ? $past_offset = $_SESSION['past_offset'] : $_SESSION['past_offset'] = 0;
    isset($_SESSION['comming_offset']) ? $comming_offset = $_SESSION['comming_offset'] : $_SESSION['comming_offset'] = 0;
    $db = dbConn();

    switch ($req) {

        case "msg_back":
            $_SESSION['msg_offset'] >= 5 ? $_SESSION['msg_offset'] -= 5 : $_SESSION['msg_offset'] = 0;
            $msg_offset = $_SESSION['msg_offset'];
            $sql = "SELECT DISTINCT FromId FROM messages WHERE ToId = " . $user_id . " LIMIT 5 OFFSET " . $msg_offset;
            $result = $db->query($sql);
            while ($row = $result->fetch_assoc()) {
                $from = $row['FromId'];
                $req = "SELECT MessageText, FromName FROM messages WHERE ToId = " . $user_id . " AND FromId = " . $from . " ORDER BY Time DESC LIMIT 1";
                $res = $db->query($req);
                while ($rec = $res->fetch_assoc() and ($item_count < $per_page)) {
                    $MessageText = $rec['MessageText'];
                    $FromName = $rec['FromName'];
                    $content .= "<li id=" . $from . ">( " . $FromName . " ) " . $MessageText . "</li>";
                    $item_count++;
                }
            }
            break;

        case "msg_fwd":
            $_SESSION['msg_offset'] < 0 ? $_SESSION['msg_offset'] = 0 : $_SESSION['msg_offset'] += 5;
            $msg_offset = $_SESSION['msg_offset'];
            $sql = "SELECT DISTINCT FromId FROM messages WHERE ToId = " . $user_id . " LIMIT 5 OFFSET " . $msg_offset;
            $result = $db->query($sql);
            while ($row = $result->fetch_assoc()) {
                $from = $row['FromId'];
                $req = "SELECT MessageText, FromName FROM messages WHERE ToId = " . $user_id . " AND FromId = " . $from . " ORDER BY Time DESC LIMIT 1";
                $res = $db->query($req);
                while ($rec = $res->fetch_assoc() and ($item_count < $per_page)) {
                    $MessageText = $rec['MessageText'];
                    $FromName = $rec['FromName'];
                    $content .= "<li id=" . $from . ">( " . $FromName . " ) " . $MessageText . "</li>";
                    $item_count++;
                }
            }
            break;

        case "past_back":
            $_SESSION['past_offset'] >= 5 ? $_SESSION['past_offset'] -= 5 : $_SESSION['past_offset'] = 0;
            $past_offset = $_SESSION['past_offset'];
            $sql = "SELECT * FROM (SELECT * FROM reservations WHERE GuestId = " . $user_id . " AND TimeSlotEnd <= " . time() . " LIMIT 5 OFFSET " . $past_offset . ") s INNER JOIN rooms r ON s.RoomId = r.RoomId";
            $result = $db->query($sql);
            while ($row = $result->fetch_assoc()) {
                $RoomName = $row['RoomName'];
                $TimeSlotStart = $row['TimeSlotStart'];
                $TimeSlotEnd = $row['TimeSlotEnd'];
                $Status = getStatus($row['Status']);
                $content .= "<li>( " . $RoomName . " ) <ul><li> From : " . $TimeSlotStart . "</li><li> To : " . $TimeSlotStart . "</li><li> Status : " . $Status . "</li></ul></li>";
            }
            break;

        case "past_fwd":
            $_SESSION['past_offset'] < 0 ? $_SESSION['past_offset'] = 0 : $_SESSION['past_offset'] += 5;
            $past_offset = $_SESSION['past_offset'];
            $sql = "SELECT * FROM (SELECT * FROM reservations WHERE GuestId = " . $user_id . " AND TimeSlotEnd <= " . time() . " LIMIT 5 OFFSET " . $past_offset . ") s INNER JOIN rooms r ON s.RoomId = r.RoomId";
            $result = $db->query($sql);
            while ($row = $result->fetch_assoc()) {
                $RoomName = $row['RoomName'];
                $TimeSlotStart = $row['TimeSlotStart'];
                $TimeSlotEnd = $row['TimeSlotEnd'];
                $Status = getStatus($row['Status']);
                $content .= "<li>( " . $RoomName . " ) <ul><li> From : " . $TimeSlotStart . "</li><li> To : " . $TimeSlotStart . "</li><li> Status : " . $Status . "</li></ul></li>";
            }
            break;

        case "comming_back":
            $_SESSION['comming_offset'] >= 5 ? $_SESSION['comming_offset'] -= 5 : $_SESSION['comming_offset'] = 0;
            $past_offset = $_SESSION['comming_offset'];
            $sql = "SELECT * FROM (SELECT * FROM reservations WHERE GuestId = " . $user_id . " AND TimeSlotEnd > " . time() . " LIMIT 5 OFFSET " . $comming_offset . ") s INNER JOIN rooms r ON s.RoomId = r.RoomId";
            $result = $db->query($sql);
            while ($row = $result->fetch_assoc()) {
                $RoomName = $row['RoomName'];
                $TimeSlotStart = $row['TimeSlotStart'];
                $TimeSlotEnd = $row['TimeSlotEnd'];
                $Status = getStatus($row['Status']);
                $content .= "<li>( " . $RoomName . " ) <ul><li> From : " . $TimeSlotStart . "</li><li> To : " . $TimeSlotStart . "</li><li> Status : " . $Status . "</li></ul></li>";
            }
            break;

        case "comming_fwd":
            $_SESSION['comming_offset'] < 0 ? $_SESSION['comming_offset'] = 0 : $_SESSION['comming_offset'] += 5;
            $past_offset = $_SESSION['comming_offset'];
            $sql = "SELECT * FROM (SELECT * FROM reservations WHERE GuestId = " . $user_id . " AND TimeSlotEnd > " . time() . " LIMIT 5 OFFSET " . $comming_offset . ") s INNER JOIN rooms r ON s.RoomId = r.RoomId";
            $result = $db->query($sql);
            while ($row = $result->fetch_assoc()) {
                $RoomName = $row['RoomName'];
                $TimeSlotStart = $row['TimeSlotStart'];
                $TimeSlotEnd = $row['TimeSlotEnd'];
                $Status = getStatus($row['Status']);
                $content .= "<li>( " . $RoomName . " ) <ul><li> From : " . $TimeSlotStart . "</li><li> To : " . $TimeSlotStart . "</li><li> Status : " . $Status . "</li></ul></li>";
            }
            break;

        case "msg_li":
            $id = $_POST['inf'];
            $sql = "SELECT * FROM messages WHERE FromId = " . $id . " AND ToID = " . $user_id . " UNION SELECT * FROM messages WHERE FromId = " . $user_id . " AND ToID = " . $id . " ORDER BY Time DESC";
            $result = $db->query($sql);
            while ($row = $result->fetch_assoc()) {
                $MessageText = $row['MessageText'];
                $FromId = $row['FromId'];
                if ($FromId == $id) {
                    $content .= '<li style=\"color:var(--secondary_font);text-align: right;list-style-type: none;\">' . $MessageText . '</li>';
                }else{
                    $content .= '<li style=\"color:var(--primary_font);list-style-type: none;\">' . $MessageText . '</li>';
                }
            }
            break;

        default:
    }

    echo '{"content":"' . $content . '"}';
}
