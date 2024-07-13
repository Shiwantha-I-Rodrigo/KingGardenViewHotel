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
            $sql = "SELECT * FROM (SELECT * FROM reservations WHERE GuestId = " . $user_id . " AND TimeSlotEnd <= " . time() . " ORDER BY TimeSlotEnd DESC LIMIT 5 OFFSET "
             . $past_offset . " ) s INNER JOIN rooms r ON s.RoomId = r.RoomId ORDER BY TimeSlotEnd DESC";
            $result = $db->query($sql);
            while ($row = $result->fetch_assoc()) {
                $res_id = $row['ReservationId'];
                $RoomName = $row['RoomName'];
                $TimeSlotStart = getTime($row['TimeSlotStart']);
                $TimeSlotEnd = getTime($row['TimeSlotEnd']);
                $Status = getStatus($row['ReservationStatus']);
                $content .= "<li id=" . $res_id . ">( " . $RoomName . " ) <ul><li> From : " . $TimeSlotStart . "</li><li> To : " . $TimeSlotEnd . "</li><li> Status : "
                 . $Status . "</li></ul></li>";
            }
            break;

        case "past_fwd":
            $_SESSION['past_offset'] < 0 ? $_SESSION['past_offset'] = 0 : $_SESSION['past_offset'] += 5;
            $past_offset = $_SESSION['past_offset'];
            $sql = "SELECT * FROM (SELECT * FROM reservations WHERE GuestId = " . $user_id . " AND TimeSlotEnd <= " . time() . " ORDER BY TimeSlotEnd DESC LIMIT 5 OFFSET "
             . $past_offset . " ) s INNER JOIN rooms r ON s.RoomId = r.RoomId ORDER BY TimeSlotEnd DESC";
            $result = $db->query($sql);
            while ($row = $result->fetch_assoc()) {
                $res_id = $row['ReservationId'];
                $RoomName = $row['RoomName'];
                $TimeSlotStart = getTime($row['TimeSlotStart']);
                $TimeSlotEnd = getTime($row['TimeSlotEnd']);
                $Status = getStatus($row['ReservationStatus']);
                $content .= "<li id=" . $res_id . ">( " . $RoomName . " ) <ul><li> From : " . $TimeSlotStart . "</li><li> To : " . $TimeSlotEnd . "</li><li> Status : "
                 . $Status . "</li></ul></li>";
            }
            break;

        case "comming_back":
            $_SESSION['comming_offset'] >= 5 ? $_SESSION['comming_offset'] -= 5 : $_SESSION['comming_offset'] = 0;
            $comming_offset = $_SESSION['comming_offset'];
            $sql = "SELECT * FROM (SELECT * FROM reservations WHERE GuestId = " . $user_id . " AND TimeSlotEnd > " . time() . " ORDER BY TimeSlotEnd LIMIT 5 OFFSET "
             . $comming_offset . ") s INNER JOIN rooms r ON s.RoomId = r.RoomId ORDER BY TimeSlotEnd";
            $result = $db->query($sql);
            while ($row = $result->fetch_assoc()) {
                $res_id = $row['ReservationId'];
                $RoomName = $row['RoomName'];
                $TimeSlotStart = getTime($row['TimeSlotStart']);
                $TimeSlotEnd = getTime($row['TimeSlotEnd']);
                $Status = getStatus($row['ReservationStatus']);
                $content .= "<li id=" . $res_id . ">( " . $RoomName . " ) <ul><li> From : " . $TimeSlotStart . "</li><li> To : " . $TimeSlotEnd . "</li><li> Status : "
                 . $Status . "</li></ul></li>";
            }
            break;

        case "comming_fwd":
            $_SESSION['comming_offset'] < 0 ? $_SESSION['comming_offset'] = 0 : $_SESSION['comming_offset'] += 5;
            $comming_offset = $_SESSION['comming_offset'];
            $sql = "SELECT * FROM (SELECT * FROM reservations WHERE GuestId = " . $user_id . " AND TimeSlotEnd > " . time() . " ORDER BY TimeSlotEnd LIMIT 5 OFFSET "
             . $comming_offset . ") s INNER JOIN rooms r ON s.RoomId = r.RoomId ORDER BY TimeSlotEnd";
            $result = $db->query($sql);
            while ($row = $result->fetch_assoc()) {
                $res_id = $row['ReservationId'];
                $RoomName = $row['RoomName'];
                $TimeSlotStart = getTime($row['TimeSlotStart']);
                $TimeSlotEnd = getTime($row['TimeSlotEnd']);
                $Status = getStatus($row['ReservationStatus']);
                $content .= "<li id=" . $res_id . ">( " . $RoomName . " ) <ul><li> From : " . $TimeSlotStart . "</li><li> To : " . $TimeSlotEnd . "</li><li> Status : "
                 . $Status . "</li></ul></li>";
            }
            break;

        case "msg_li":
            $id = $_POST['inf'];
            $sql = "SELECT * FROM messages WHERE FromId = " . $id . " AND ToID = " . $user_id . " UNION SELECT * FROM messages WHERE FromId = " . $user_id . " AND ToID = "
             . $id . " ORDER BY Time";
            $result = $db->query($sql);
            while ($row = $result->fetch_assoc()) {
                $MessageText = $row['MessageText'];
                $FromId = $row['FromId'];
                if ($FromId == $id) {
                    $content .= '<li style=\"color:var(--secondary_font); text-align: right;list-style-type: none; padding-right:2vw;\">' . $MessageText . '</li>';
                } else {
                    $content .= '<li style=\"color:var(--primary_font); list-style-type: none;\">' . $MessageText . '</li>';
                }
            }
            break;

        case "res_li":
            $id = $_POST['inf'];
            $sql = "SELECT * FROM reservations s INNER JOIN rooms r ON s.RoomId = r.RoomId WHERE ReservationId = " . $id ;
            $result = $db->query($sql);
            while ($row = $result->fetch_assoc()) {
                $RoomId = $row['RoomId'];
                $RoomName = $row['RoomName'];
                $RoomAC = $row['RoomAC'];
                $RoomWIFI = $row['RoomWIFI'];
                $RoomPicture = $row['RoomPicture'];
                $RoomBalcony = $row['RoomBalcony'];
                $TimeSlotStart = getTime($row['TimeSlotStart']);
                $TimeSlotEnd = getTime($row['TimeSlotEnd']);
                $ReservationId = $row['ReservationId'];
                $Status = getStatus($row['ReservationStatus']);
            }
            $content .= '<li><img src=\"' . $RoomPicture . '\" alt=\"\" style=\"width:95%; border-radius: 1vh;\"/></li>';
            $content .= "<li>Room : " . $RoomName . " " . $RoomId ."</li><li>From : " . $TimeSlotStart . " To : "
             . $TimeSlotEnd . "</li><li>Reservation No : " . $ReservationId . "</li><li>Status : " . $Status . "</li>";
            $req = "SELECT * FROM items WHERE ReservationId = " . $id ;
            $reply = $db->query($req);
            $content .= "<li>Invoice :</li>";
            while ($row = $reply->fetch_assoc()) {
                $ItemName = $row['ItemName'];
                $ItemPrice = $row['ItemPrice'];
                $ItemPaid = $row['ItemPaid'];
                $ItemStatus = getStatus($row['ItemStatus']);
                $content .= "<li><ul><li> " . $ItemName . " : " . $ItemPrice . " ( " . $ItemStatus . " ) </li></ul></li>";
            }
            break;

        default:
    }

    echo '{"content":"' . $content . '"}';
}
