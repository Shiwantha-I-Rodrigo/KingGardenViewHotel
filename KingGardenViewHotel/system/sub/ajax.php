<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/common.php';
isset($_SESSION['user_id']) ? $user_id = $_SESSION['user_id'] : reDirect("/system/modules/login.php");

if (isset($_POST['req'])) {
    $req = $_POST['req'];
    $content = '';
    $per_page = 5;
    $item_count = 0;
    isset($_POST['opt']) ? $opt = $_POST['opt'] : $opt = '';
    isset($_SESSION['msg_offset']) ? $msg_offset = $_SESSION['msg_offset'] : $_SESSION['msg_offset'] = 0;
    isset($_SESSION['past_offset']) ? $past_offset = $_SESSION['past_offset'] : $_SESSION['past_offset'] = 0;
    isset($_SESSION['comming_offset']) ? $comming_offset = $_SESSION['comming_offset'] : $_SESSION['comming_offset'] = 0;
    isset($_SESSION['blog_offset']) ? $blog_offset = $_SESSION['blog_offset'] : $_SESSION['blog_offset'] = 0;
    $db = dbConn();

    switch ($req) {

        case "msg_back":
            $_SESSION['msg_offset'] >= 5 ? $_SESSION['msg_offset'] -= 5 : $_SESSION['msg_offset'] = 0;
            $content = msg($_SESSION['msg_offset'], $user_id, $item_count, $per_page, $db);
            break;

        case "msg_fwd":
            $_SESSION['msg_offset'] < 0 ? $_SESSION['msg_offset'] = 0 : $_SESSION['msg_offset'] += 5;
            $content = msg($_SESSION['msg_offset'], $user_id, $item_count, $per_page, $db);
            break;

        case "past_back":
            $_SESSION['past_offset'] >= 5 ? $_SESSION['past_offset'] -= 5 : $_SESSION['past_offset'] = 0;
            $content = res_p($_SESSION['past_offset'], $per_page, $user_id, $db);
            break;

        case "past_fwd":
            $_SESSION['past_offset'] < 0 ? $_SESSION['past_offset'] = 0 : $_SESSION['past_offset'] += 5;
            $content = res_p($_SESSION['past_offset'], $per_page, $user_id, $db);
            break;

        case "comming_back":
            $_SESSION['comming_offset'] >= 5 ? $_SESSION['comming_offset'] -= 5 : $_SESSION['comming_offset'] = 0;
            $content = res_f($_SESSION['comming_offset'], $per_page, $user_id, $db);
            break;

        case "comming_fwd":
            $_SESSION['comming_offset'] < 0 ? $_SESSION['comming_offset'] = 0 : $_SESSION['comming_offset'] += 5;
            $content = res_f($_SESSION['comming_offset'], $per_page, $user_id, $db);
            break;

        case "past_backa":
            $_SESSION['past_offset'] >= 5 ? $_SESSION['past_offset'] -= 5 : $_SESSION['past_offset'] = 0;
            $content = res_pa($_SESSION['past_offset'], $per_page, $user_id, $db);
            break;

        case "past_fwda":
            $_SESSION['past_offset'] < 0 ? $_SESSION['past_offset'] = 0 : $_SESSION['past_offset'] += 5;
            $content = res_pa($_SESSION['past_offset'], $per_page, $user_id, $db);
            break;

        case "comming_backa":
            $_SESSION['comming_offset'] >= 5 ? $_SESSION['comming_offset'] -= 5 : $_SESSION['comming_offset'] = 0;
            $content = res_fa($_SESSION['comming_offset'], $per_page, $user_id, $db);
            break;

        case "comming_fwda":
            $_SESSION['comming_offset'] < 0 ? $_SESSION['comming_offset'] = 0 : $_SESSION['comming_offset'] += 5;
            $content = res_fa($_SESSION['comming_offset'], $per_page, $user_id, $db);
            break;

        case "blog_back":
            $_SESSION['blog_offset'] >= 5 ? $_SESSION['blog_offset'] -= 5 : $_SESSION['blog_offset'] = 0;
            $content = blog($_SESSION['blog_offset'], $per_page, $db);
            break;

        case "blog_fwd":
            $_SESSION['blog_offset'] < 0 ? $_SESSION['blog_offset'] = 0 : $_SESSION['blog_offset'] += 5;
            $content = blog($_SESSION['blog_offset'], $per_page, $db);
            break;

        case "msg_li":
            $id = $_POST['inf'];
            $sql = "SELECT * FROM messages WHERE FromId = " . $id . " AND ToID = " . $user_id . " UNION SELECT * FROM messages WHERE FromId = " . $user_id . " AND ToID = "
                . $id . " ORDER BY MessageTime DESC";
            $result = $db->query($sql);
            while ($row = $result->fetch_assoc()) {
                $MessageText = $row['MessageText'];
                $FromId = $row['FromId'];
                $MessageTime = getTimes($row['MessageTime']);
                if ($FromId == $id) {
                    $content .= "<li class='chat' ><div class='chat-right-text'>" . $MessageText . "</div><div class='chat-right-time'>" . $MessageTime . "</div></li>";
                } else {
                    $content .= "<li class='chat' ><div class='chat-left-text'>" . $MessageText . "</div><div class='chat-left-time'>" . $MessageTime . "</div></li>";
                }
            }
            break;

        case "res_li":
            $id = $_POST['inf'];
            $sql = "SELECT * FROM reservations s INNER JOIN rooms r ON s.RoomId = r.RoomId WHERE ReservationId = " . $id;
            $result = $db->query($sql);
            while ($row = $result->fetch_assoc()) {
                $RoomId = $row['RoomId'];
                $RoomName = $row['RoomName'];
                $RoomAC = $row['RoomAC'];
                $RoomWIFI = $row['RoomWIFI'];
                $RoomPicture = $row['RoomPicture'];
                $RoomCapacity = $row['RoomCapacity'];
                $TimeSlotStart = getTime($row['TimeSlotStart']);
                $TimeSlotEnd = getTime($row['TimeSlotEnd']);
                $ReservationId = $row['ReservationId'];
                $Status = getStatus($row['ReservationStatus']);
                $CancelTime = $row['TimeSlotEnd'];
            }
            $content .= '<li><img src=\"' . $RoomPicture . '\" alt=\"\" style=\"width:95%; border-radius: 1vh;\"/></li>';
            $content .= "<li class='reservation-name' >Room : " . $RoomName . " " . $RoomId . "</li><br/><li class='reservation-time'>From : " . $TimeSlotStart . " To : "
                . $TimeSlotEnd . "</li><li class='reservation-time' >Reservation No : " . $ReservationId . "</li><li class='reservation-time' >Status : " . $Status . "</li>";
            if ($CancelTime > time()) {
                $content .= "<br/><li><button data-id = " . $ReservationId . " id='cancel' class='fail-btn px-3'>Cancel Reservation</button></li>";
            }
            $req = "SELECT * FROM items WHERE ReservationId = " . $id;
            $reply = $db->query($req);
            $content .= "<br/><li>Invoice :</li>";
            while ($row = $reply->fetch_assoc()) {
                $ItemName = $row['ItemName'];
                $ItemPrice = $row['ItemPrice'];
                $ItemPaid = $row['ItemPaid'];
                $ItemStatus = getStatus($row['ItemStatus']);
                $content .= "<li><ul><li> " . $ItemName . " : Rs." . $ItemPrice . " ( " . $ItemStatus . " ) </li></ul></li>";
            }
            break;

        default:
    }
    echo '{"content":"' . $content . '"}';
}


function msg($msg_offset, $user_id, $item_count, $per_page, $db)
{
    $content = "";
    $sql = "SELECT * FROM messages WHERE ToId = $user_id OR FromId = $user_id ORDER BY MessageTime DESC";
    $result = $db->query($sql);
    $list = array();
    while ($row = $result->fetch_assoc()) {
        $row['FromId'] == $user_id ? $contact = $row['ToId'] : $contact = $row['FromId'];
        if (!in_array($contact, $list)) {
            array_push($list, $contact);
        }
    }
    count($list) <= 5 ? $end = " id='end' " : $end = "";
    $i = 0;
    foreach ($list as $item) {
        if ($i++ < $msg_offset) continue;
        $sql2 = "SELECT * FROM messages WHERE ( ToId = $item AND FromId = $user_id ) OR ( ToId = $user_id AND FromId = $item ) ORDER BY MessageTime DESC LIMIT 1 offset $msg_offset";
        $result2 = $db->query($sql2);
        while ($row2 = $result2->fetch_assoc()) {
            if ($item_count < $per_page) {
                $sql3 = "SELECT * FROM users WHERE UserId = $item";
                $result3 = $db->query($sql3);
                $row3 = $result3->fetch_assoc();
                $MessageText = $row2['MessageText'];
                $FromName = $row3['UserName'];
                $MessageTime = getTimes($row2['MessageTime']);
                $from = $row2['FromName'];
                $item_count++;
                $content .= "<li class='message' id=$item ><div $end class='message-name'> $FromName : </div><div class='message-text'> $MessageText </div><div class='message-time'> $from  <br/> $MessageTime </div></li>";
            }
        }
    }
    return $content;
}

function res_p($past_offset, $per_page, $user_id, $db)
{
    $content = "";
    $sql = "SELECT * FROM (SELECT * FROM reservations WHERE GuestId = " . $user_id . " AND TimeSlotEnd <= " . time() . " ORDER BY TimeSlotEnd DESC LIMIT 6 OFFSET "
        . $past_offset . " ) s INNER JOIN rooms r ON s.RoomId = r.RoomId ORDER BY TimeSlotEnd DESC";
    $result = $db->query($sql);
    $result->num_rows <= 5 ? $end = " id='end' " : $end = "";
    $i = 0;
    while ($row = $result->fetch_assoc()) {
        if ($i++ >= $per_page) break;
        $res_id = $row['ReservationId'];
        $RoomName = $row['RoomName'];
        $TimeSlotStart = getTime($row['TimeSlotStart']);
        $TimeSlotEnd = getTime($row['TimeSlotEnd']);
        $Status = getStatus($row['ReservationStatus']);
        $content .= "<li class='reservation' id=" . $res_id . "><div $end class='reservation-name'>" . $RoomName . " - " . $res_id . "</div><div class='reservation-time'> From : " . $TimeSlotStart
            . "</div><div class='reservation-time'> To : " . $TimeSlotEnd . "</div><div class='reservation-status'> Status : " . $Status . "</li>";
    }
    return $content;
}

function res_f($comming_offset, $per_page, $user_id, $db)
{
    $content = "";
    $sql = "SELECT * FROM (SELECT * FROM reservations WHERE GuestId = " . $user_id . " AND TimeSlotEnd > " . time() . " ORDER BY TimeSlotEnd LIMIT 6 OFFSET "
        . $comming_offset . ") s INNER JOIN rooms r ON s.RoomId = r.RoomId ORDER BY TimeSlotEnd";
    $result = $db->query($sql);
    $result->num_rows <= 5 ? $end = " id='end' " : $end = "";
    $i = 0;
    while ($row = $result->fetch_assoc()) {
        if ($i++ >= $per_page) break;
        $res_id = $row['ReservationId'];
        $RoomName = $row['RoomName'];
        $TimeSlotStart = getTime($row['TimeSlotStart']);
        $TimeSlotEnd = getTime($row['TimeSlotEnd']);
        $Status = getStatus($row['ReservationStatus']);
        $content .= "<li class='reservation' id=" . $res_id . "><div $end class='reservation-name'>" . $RoomName . " - " . $res_id . "</div><div class='reservation-time'> From : " . $TimeSlotStart
            . "</div><div class='reservation-time'> To : " . $TimeSlotEnd . "</div><div class='reservation-status'> Status : " . $Status . "</li>";
    }
    return $content;
}

function blog($blog_offset, $per_page, $db)
{
    $content = "";
    $sql = "SELECT * FROM blogs LIMIT 6 OFFSET " . $blog_offset;
    $result = $db->query($sql);
    $result->num_rows <= 5 ? $end = " id='end' " : $end = "";
    $i = 0;
    while ($row = $result->fetch_assoc()) {
        if ($i++ >= $per_page) break;
        $BlogText = $row['BlogText'];
        $BlogTitle = $row['BlogTitle'];
        $BlogPicture = $row['BlogPicture'];
        $content .= '<div ' . $end . 'class="row my-5 ps-5" style="width:100vw; height:30vh;">
                    <div class="col-11 m-0 p-0" style="background-color:var(--background);border: 0.5vh solid var(--background);border-radius: 2vh;">
                        <div class="row m-0 p-0">
                            <div class="col-4 m-0 p-0" style="overflow: hidden;">
                                <img class="m-0 p-0" src="' . $BlogPicture . '" alt="" style="height:30vh; object-fit: cover; border-radius: 2vh 0 0 2vh;">
                            </div>
                            <div class="col-8 m-0 p-0">
                                <h3 style="font-size: 3vh; text-align:center;" class="my-3">' . $BlogTitle . '</h3>
                                <p class="me-3 px-5" style="font-size:2vh; text-align: justify; text-justify: inter-word;">' . $BlogText . '</p>
                            </div>
                        </div>
                    </div>
                </div>';
    }
    $content = json_encode($content, JSON_UNESCAPED_SLASHES);
    $content = trim($content, "\"");
    return $content;
}

function res_pa($past_offset, $per_page, $user_id, $db)
{
    $content = "";
    $sql = "SELECT * FROM (SELECT * FROM reservations WHERE TimeSlotEnd <= " . time() . " ORDER BY TimeSlotEnd DESC LIMIT 6 OFFSET "
        . $past_offset . " ) s INNER JOIN rooms r ON s.RoomId = r.RoomId ORDER BY TimeSlotEnd DESC";
    $result = $db->query($sql);
    $result->num_rows <= 5 ? $end = " id='end' " : $end = "";
    $i = 0;
    while ($row = $result->fetch_assoc()) {
        if ($i++ >= $per_page) break;
        $res_id = $row['ReservationId'];
        $RoomName = $row['RoomName'];
        $TimeSlotStart = getTime($row['TimeSlotStart']);
        $TimeSlotEnd = getTime($row['TimeSlotEnd']);
        $Status = getStatus($row['ReservationStatus']);
        $content .= "<li class='reservation' id=" . $res_id . "><div $end class='reservation-name'>" . $RoomName . " - " . $res_id . "</div><div class='reservation-time'> From : " . $TimeSlotStart
            . "</div><div class='reservation-time'> To : " . $TimeSlotEnd . "</div><div class='reservation-status'> Status : " . $Status . "</li>";
    }
    return $content;
}

function res_fa($comming_offset, $per_page, $user_id, $db)
{
    $content = "";
    $sql = "SELECT * FROM (SELECT * FROM reservations WHERE  TimeSlotEnd > " . time() . " ORDER BY TimeSlotEnd LIMIT 6 OFFSET "
        . $comming_offset . ") s INNER JOIN rooms r ON s.RoomId = r.RoomId ORDER BY TimeSlotEnd";
    $result = $db->query($sql);
    $result->num_rows <= 5 ? $end = " id='end' " : $end = "";
    $i = 0;
    while ($row = $result->fetch_assoc()) {
        if ($i++ >= $per_page) break;
        $res_id = $row['ReservationId'];
        $RoomName = $row['RoomName'];
        $TimeSlotStart = getTime($row['TimeSlotStart']);
        $TimeSlotEnd = getTime($row['TimeSlotEnd']);
        $Status = getStatus($row['ReservationStatus']);
        $content .= "<li class='reservation' id=" . $res_id . "><div $end class='reservation-name'>" . $RoomName . " - " . $res_id . "</div><div class='reservation-time'> From : " . $TimeSlotStart
            . "</div><div class='reservation-time'> To : " . $TimeSlotEnd . "</div><div class='reservation-status'> Status : " . $Status . "</li>";
    }
    return $content;
}
