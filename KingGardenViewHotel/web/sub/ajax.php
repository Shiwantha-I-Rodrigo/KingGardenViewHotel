<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/common.php';
isset($_SESSION['user_id']) ? $user_id = $_SESSION['user_id'] : $user_id = 0;
//authorize($user_id, '1', 'web');

if (isset($_POST['req'])) {
    $req = $_POST['req'];
    $content = '';
    $per_page = 5;
    $item_count = 0;
    isset($_SESSION['msg_offset']) ? $msg_offset = $_SESSION['msg_offset'] : $_SESSION['msg_offset'] = 0;
    isset($_SESSION['past_offset']) ? $past_offset = $_SESSION['past_offset'] : $_SESSION['past_offset'] = 0;
    isset($_SESSION['comming_offset']) ? $comming_offset = $_SESSION['comming_offset'] : $_SESSION['comming_offset'] = 0;
    isset($_SESSION['blog_offset']) ? $blog_offset = $_SESSION['blog_offset'] : $_SESSION['blog_offset'] = 0;
    isset($_SESSION['dest_offset']) ? $dest_offset = $_SESSION['dest_offset'] : $_SESSION['dest_offset'] = 0;
    isset($_SESSION['room_offset']) ? $dest_offset = $_SESSION['room_offset'] : $_SESSION['room_offset'] = 0;
    isset($_SESSION['rev_offset']) ? $rev_offset = $_SESSION['rev_offset'] : $_SESSION['rev_offset'] = 0;
    $db = dbConn();

    switch ($req) {

        case "msg_back":
            $_SESSION['msg_offset'] >= 5 ? $_SESSION['msg_offset'] -= 5 : $_SESSION['msg_offset'] = 0;
            $msg_offset = $_SESSION['msg_offset'];
            $sql = "SELECT * FROM messages WHERE ToId = $user_id OR FromId = $user_id ORDER BY MessageTime DESC";
            $result = $db->query($sql);
            $list = array();
            while ($row = $result->fetch_assoc()) {
                $row['FromId'] == $user_id ? $contact = $row['ToId'] : $contact =  $row['FromId'];
                if (!in_array($contact, $list)) {
                    array_push($list, $contact);
                }
            }
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
                        $content .= "<li class='message' id=$item ><div class='message-name'> $FromName : </div><div class='message-text'> $MessageText </div><div class='message-time'> $from  <br/> $MessageTime </div></li>";
                    }
                }
            }
            break;

        case "msg_fwd":
            $_SESSION['msg_offset'] < 0 ? $_SESSION['msg_offset'] = 0 : $_SESSION['msg_offset'] += 5;
            $msg_offset = $_SESSION['msg_offset'];
            $sql = "SELECT * FROM messages WHERE ToId = $user_id OR FromId = $user_id ORDER BY MessageTime DESC";
            $result = $db->query($sql);
            $list = array();
            while ($row = $result->fetch_assoc()) {
                $row['FromId'] == $user_id ? $contact = $row['ToId'] : $contact = $row['FromId'];
                if (!in_array($contact, $list)) {
                    array_push($list, $contact);
                }
            }
            $i = 0;
            foreach ($list as $item) {
                if ($i++ < $msg_offset) continue;
                $sql2 = "SELECT * FROM messages WHERE ( ToId = $item AND FromId = $user_id ) OR ( ToId = $user_id AND FromId = $item ) ORDER BY MessageTime DESC LIMIT 1 offset 0";
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
                        $content .= "<li class='message' id=$item ><div class='message-name'> $FromName : </div><div class='message-text'> $MessageText </div><div class='message-time'> $from  <br/> $MessageTime </div></li>";
                    }
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
                $content .= "<li class='reservation' id=" . $res_id . "><div class='reservation-name'>" . $RoomName . " - " . $res_id . "</div><div class='reservation-time'> From : " . $TimeSlotStart
                    . "</div><div class='reservation-time'> To : " . $TimeSlotEnd . "</div><div class='reservation-status'> Status : " . $Status . "</li>";
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
                $content .= "<li class='reservation' id=" . $res_id . "><div class='reservation-name'>" . $RoomName . " - " . $res_id . "</div><div class='reservation-time'> From : " . $TimeSlotStart
                    . "</div><div class='reservation-time'> To : " . $TimeSlotEnd . "</div><div class='reservation-status'> Status : " . $Status . "</li>";
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
                $content .= "<li class='reservation' id=" . $res_id . "><div class='reservation-name'>" . $RoomName . " - " . $res_id . "</div><div class='reservation-time'> From : " . $TimeSlotStart
                    . "</div><div class='reservation-time'> To : " . $TimeSlotEnd . "</div><div class='reservation-status'> Status : " . $Status . "</li>";
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
                $content .= "<li class='reservation' id=" . $res_id . "><div class='reservation-name'>" . $RoomName . " - " . $res_id . "</div><div class='reservation-time'> From : " . $TimeSlotStart
                    . "</div><div class='reservation-time'> To : " . $TimeSlotEnd . "</div><div class='reservation-status'> Status : " . $Status . "</li>";
            }
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
                $Guests = $row['Guests'];
                $ReservationStatus = $row['ReservationStatus'];
            }
            $content .= '<li><img src=\"' . $RoomPicture . '\" alt=\"\" style=\"width:95%; border-radius: 1vh;\"/></li>';
            $content .= "<li class='reservation-name' >Room : " . $RoomName . " " . $RoomId . "</li><br/><li class='reservation-time'>From : " . $TimeSlotStart . " To : "
                . $TimeSlotEnd . "</li><li class='reservation-time' >Reservation No : " . $ReservationId . "</li><li class='reservation-time' >Status : " . $Status . "</li>";
            if ($CancelTime > time()) {
                $content .= "<br/><li><button data-id = " . $ReservationId . " id='cancel' class='fail-btn px-3'>Cancel Reservation</button></li>";
            } else if ($ReservationStatus != 7) {
                $content .= "<br/><li><button data-id = " . $ReservationId . " id='review' class='success-btn px-3'>Review Reservation</button></li>";
            }
            $req = "SELECT * FROM items WHERE ReservationId = " . $id;
            $reply = $db->query($req);
            $content .= "<br/><li>Invoice :</li>";
            while ($row = $reply->fetch_assoc()) {
                $ItemName = $row['ItemName'];
                $ItemPrice = $row['ItemPrice'];
                $ItemPaid = $row['ItemPaid'];
                $ItemStatus = getItemStatus($row['ItemStatus']);
                $content .= "<li><ul><li> " . $ItemName . " : Rs." . $ItemPrice . " ( " . $ItemStatus . " ) </li></ul></li>";
            }

            break;

        case "blog_back":
            $_SESSION['blog_offset'] >= 5 ? $_SESSION['blog_offset'] -= 5 : $_SESSION['blog_offset'] = 0;
            $blog_offset = $_SESSION['blog_offset'];
            $sql = "SELECT * FROM blogs LIMIT 5 OFFSET " . $blog_offset;
            $result = $db->query($sql);
            while ($row = $result->fetch_assoc()) {
                $BlogText = $row['BlogText'];
                $BlogTitle = $row['BlogTitle'];
                $BlogPicture1 = $row['BlogPicture1'];
                $BlogPicture2 = $row['BlogPicture2'];
                $BlogPicture3 = $row['BlogPicture3'];
                $BlogPicture4 = $row['BlogPicture4'];
                $BlogPicture5 = $row['BlogPicture5'];
                $content .= '<div class="row my-5 ps-5" style="width:100vw;">
                    <div class="col-11 m-0 p-0" style="background-color:var(--background);border: 0.5vh solid var(--background);border-radius: 2vh;">
                        <div class="row m-0 p-0">
                            <div class="col-4 m-0 p-0" style="overflow: hidden;">
                                <img class="m-0 p-0" src="' . $BlogPicture1 . '" alt="" style="height:100%; object-fit: cover; border-radius: 2vh 0 0 2vh;">
                            </div>
                            <div class="col-8 m-0 p-0">
                                <div class="row m-0 p-0">
                                    <h3 style="font-size: 3vh; text-align:center;" class="my-3">' . $BlogTitle . '</h3>
                                    <p class="me-3 px-5" style="font-size:2vh; text-align: justify; text-justify: inter-word;">' . $BlogText . '</p>
                                </div>
                                <div class="row m-0 p-0 mt-3">
                                    <div class="col-3 m-0 p-3 d-flex justify-content-center popup" style="height:10vh;">
                                        <img class="m-0 p-0" src="' . $BlogPicture2 . '" alt="" style="height:100%; width:100%; object-fit: cover;">
                                    </div>
                                    <div class="col-3 m-0 p-3 d-flex justify-content-center popup" style="height:10vh;">
                                        <img class="m-0 p-0" src="' . $BlogPicture3 . '" alt="" style="height:100%; width:100%; object-fit: cover; ">
                                    </div>
                                    <div class="col-3 m-0 p-3 d-flex justify-content-center popup" style="height:10vh;">
                                        <img class="m-0 p-0" src="' . $BlogPicture4 . '" alt="" style="height:100%; width:100%; object-fit: cover; ">
                                    </div>
                                    <div class="col-3 m-0 p-3 d-flex justify-content-center popup" style="height:10vh;">
                                        <img class="m-0 p-0" src="' . $BlogPicture5 . '" alt="" style="height:100%; width:100%; object-fit: cover; ">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
            }
            $content = json_encode($content, JSON_UNESCAPED_SLASHES);
            $content = trim($content, "\"");
            break;

        case "blog_fwd":
            $_SESSION['blog_offset'] < 0 ? $_SESSION['blog_offset'] = 0 : $_SESSION['blog_offset'] += 5;
            $blog_offset = $_SESSION['blog_offset'];
            $sql = "SELECT * FROM blogs LIMIT 5 OFFSET " . $blog_offset;
            $result = $db->query($sql);
            while ($row = $result->fetch_assoc()) {
                $BlogText = $row['BlogText'];
                $BlogTitle = $row['BlogTitle'];
                $BlogPicture1 = $row['BlogPicture1'];
                $BlogPicture2 = $row['BlogPicture2'];
                $BlogPicture3 = $row['BlogPicture3'];
                $BlogPicture4 = $row['BlogPicture4'];
                $BlogPicture5 = $row['BlogPicture5'];
                $content .= '<div class="row my-5 ps-5" style="width:100vw;">
                    <div class="col-11 m-0 p-0" style="background-color:var(--background);border: 0.5vh solid var(--background);border-radius: 2vh;">
                        <div class="row m-0 p-0">
                            <div class="col-4 m-0 p-0" style="overflow: hidden;">
                                <img class="m-0 p-0" src="' . $BlogPicture1 . '" alt="" style="height:100%; object-fit: cover; border-radius: 2vh 0 0 2vh;">
                            </div>
                            <div class="col-8 m-0 p-0">
                                <div class="row m-0 p-0">
                                    <h3 style="font-size: 3vh; text-align:center;" class="my-3">' . $BlogTitle . '</h3>
                                    <p class="me-3 px-5" style="font-size:2vh; text-align: justify; text-justify: inter-word;">' . $BlogText . '</p>
                                </div>
                                <div class="row m-0 p-0 mt-3">
                                    <div class="col-3 m-0 p-3 d-flex justify-content-center popup" style="height:10vh;">
                                        <img class="m-0 p-0" src="' . $BlogPicture2 . '" alt="" style="height:100%; width:100%; object-fit: cover;">
                                    </div>
                                    <div class="col-3 m-0 p-3 d-flex justify-content-center popup" style="height:10vh;">
                                        <img class="m-0 p-0" src="' . $BlogPicture3 . '" alt="" style="height:100%; width:100%; object-fit: cover; ">
                                    </div>
                                    <div class="col-3 m-0 p-3 d-flex justify-content-center popup" style="height:10vh;">
                                        <img class="m-0 p-0" src="' . $BlogPicture4 . '" alt="" style="height:100%; width:100%; object-fit: cover; ">
                                    </div>
                                    <div class="col-3 m-0 p-3 d-flex justify-content-center popup" style="height:10vh;">
                                        <img class="m-0 p-0" src="' . $BlogPicture5 . '" alt="" style="height:100%; width:100%; object-fit: cover; ">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
            }
            $content = json_encode($content, JSON_UNESCAPED_SLASHES);
            $content = trim($content, "\"");
            break;

        case "dest_back":
            $_SESSION['dest_offset'] >= 5 ? $_SESSION['dest_offset'] -= 5 : $_SESSION['dest_offset'] = 0;
            $dest_offset = $_SESSION['dest_offset'];
            $sql = "SELECT * FROM destinations LIMIT 5 OFFSET $dest_offset";
            $result = $db->query($sql);
            while ($row = $result->fetch_assoc()) {
                $DestinationText = $row['DestinationText'];
                $DestinationTitle = $row['DestinationTitle'];
                $DestinationPicture = $row['DestinationPicture'];
                $DestinationStatus = $row['DestinationStatus'];
                $DestinationId = $row['DestinationId'];

                $content .= '<div class="row my-5 ps-5" style="width:100vw;">
                        <div class="col-11 m-0 p-0" style="background-color:var(--background);border: 0.5vh solid var(--background);border-radius: 2vh;">
                            <div class="row m-0 p-0">
                                <div class="col-4 m-0 p-0 popup" style="overflow: hidden;">
                                    <img class="m-0 p-0" src="' . $DestinationPicture . '" alt="" style="height:100%; object-fit: cover; border-radius: 2vh 0 0 2vh;">
                                </div>
                                <div class="col-8 m-0 p-0">
                                    <div class="row m-0 p-0">
                                        <h3 style="font-size: 3vh; text-align:center;" class="my-3">' . $DestinationTitle . '</h3>
                                        <p class="me-3 px-5" style="font-size:2vh; text-align: justify; text-justify: inter-word;">' . $DestinationText . '</p>
                                        <p class="me-3 px-5" style="font-size:2vh;" id="list_toggle"><u>Transportaion options available</u></p>';

                $sql2 = "SELECT * FROM user_destinations WHERE DestinationId=$DestinationId";
                $result2 = $db->query($sql2);
                while ($row2 = $result2->fetch_assoc()) {
                    $UserId = $row2['UserId'];
                    $TransportPrice = $row2['TransportPrice'];
                    $Telephone = $row2['Telephone'];
                    $Chat = $row2['Chat'];
                    $Capacity = $row2['Capacity'];
                    $EntryTime = getTime($row2['EntryTime']);

                    $content .= "<p class='d-none transport mb-5 ms-5' style='font-size:2vh;'>
                    <i class='material-icons'>paid</i> User $UserId offsers transportaion at <em>Rs.$TransportPrice.00</em> 
                    <br/><i class='material-icons'>groups</i> $Capacity People
                    &nbsp &nbsp<i class='material-icons'>call</i> $Telephone
                    &nbsp &nbsp<i class='material-icons'>sms</i> $Chat 
                    &nbsp &nbsp<i class='material-icons'>schedule</i> Updated On : $EntryTime</p>";
                }

                $content .= '</div></div></div></div></div>';
            }
            $content = json_encode($content, JSON_UNESCAPED_SLASHES);
            $content = trim($content, "\"");
            break;

        case "dest_fwd":
            $_SESSION['dest_offset'] < 0 ? $_SESSION['dest_offset'] = 0 : $_SESSION['dest_offset'] += 5;
            $dest_offset = $_SESSION['dest_offset'];
            $sql = "SELECT * FROM destinations LIMIT 5 OFFSET $dest_offset";
            $result = $db->query($sql);
            while ($row = $result->fetch_assoc()) {
                $DestinationText = $row['DestinationText'];
                $DestinationTitle = $row['DestinationTitle'];
                $DestinationPicture = $row['DestinationPicture'];
                $DestinationStatus = $row['DestinationStatus'];
                $DestinationId = $row['DestinationId'];

                $content .= '<div class="row my-5 ps-5" style="width:100vw;">
                        <div class="col-11 m-0 p-0" style="background-color:var(--background);border: 0.5vh solid var(--background);border-radius: 2vh;">
                            <div class="row m-0 p-0">
                                <div class="col-4 m-0 p-0 popup" style="overflow: hidden;">
                                    <img class="m-0 p-0" src="' . $DestinationPicture . '" alt="" style="height:100%; object-fit: cover; border-radius: 2vh 0 0 2vh;">
                                </div>
                                <div class="col-8 m-0 p-0">
                                    <div class="row m-0 p-0">
                                        <h3 style="font-size: 3vh; text-align:center;" class="my-3">' . $DestinationTitle . '</h3>
                                        <p class="me-3 px-5" style="font-size:2vh; text-align: justify; text-justify: inter-word;">' . $DestinationText . '</p>
                                        <p class="me-3 px-5" style="font-size:2vh;" id="list_toggle"><u>Transportaion options available</u></p>';

                $sql2 = "SELECT * FROM user_destinations WHERE DestinationId=$DestinationId";
                $result2 = $db->query($sql2);
                while ($row2 = $result2->fetch_assoc()) {
                    $UserId = $row2['UserId'];
                    $TransportPrice = $row2['TransportPrice'];
                    $Telephone = $row2['Telephone'];
                    $Chat = $row2['Chat'];
                    $Capacity = $row2['Capacity'];
                    $EntryTime = getTime($row2['EntryTime']);

                    $content .= "<p class='d-none transport mb-5 ms-5' style='font-size:2vh;'>
                    <i class='material-icons'>paid</i> User $UserId offsers transportaion at <em>Rs.$TransportPrice.00</em> 
                    <br/><i class='material-icons'>groups</i> $Capacity People
                    &nbsp &nbsp<i class='material-icons'>call</i> $Telephone
                    &nbsp &nbsp<i class='material-icons'>sms</i> $Chat 
                    &nbsp &nbsp<i class='material-icons'>schedule</i> Updated On : $EntryTime</p>";
                }

                $content .= '</div></div></div></div></div>';
            }
            $content = json_encode($content, JSON_UNESCAPED_SLASHES);
            $content = trim($content, "\"");
            break;

        case "rev_back":
            $room_id = $_POST['inf'];
            $_SESSION['rev_offset'] >= 5 ? $_SESSION['rev_offset'] -= 5 : $_SESSION['rev_offset'] = 0;
            $rev_offset = $_SESSION['rev_offset'];
            $sql = "SELECT * FROM (SELECT r.ReservationId FROM reservations r JOIN rooms c ON r.RoomId=c.RoomId WHERE r.RoomId = $room_id) as x JOIN reviews w ON x.ReservationId = w.ReservationId LIMIT 5 OFFSET $rev_offset";
            $result = $db->query($sql);
            while ($row = $result->fetch_assoc()) {
                $ReviewTitle = $row['ReviewTitle'];
                $ReviewText = $row['ReviewText'];
                $ReservationId = $row['ReservationId'];
                $ReviewId = $row['ReviewId'];
                $ReviewStatus = $row['ReviewStatus'];
                $ReviewPicture = $row['ReviewPicture'];

                $sql2 = "SELECT * FROM reservations r JOIN customers c ON r.GuestId=c.UserId WHERE r.ReservationId = $ReservationId ";
                $result2 = $db->query($sql2);
                $row2 = $result2->fetch_assoc();
                $Name = $row2['FirstName'] . " " . $row2['LastName'];

                $content .= '<div class="row my-2 ps-5" style="width:100vw;">
                        <div class="col-11 m-0 p-0">
                            <div class="row m-0 p-0">
                                <div class="col-1 m-0 p-0 popup" style="height:15vh; width:15vh; overflow: hidden;">
                                    <img class="m-0 p-0" src="' . $ReviewPicture . '" alt="" style="height:100%;object-fit: cover;">
                                </div>
                                <div class="col-10 m-0 p-0">
                                    <div class="row m-0 p-0">
                                        <h2 class="m-2" style="color:var(--primary);font-size: 3vh;">' . $ReviewTitle . '</h2>
                                        <p class="m-2" style="color:var(--primary);font-size:2vh; text-align: justify; text-justify: inter-word;">' . $ReviewText . '</p>
                                        <p class="m-2" style="color:var(--primary);font-size:2vh; font-style:italic;">Review By : ' . $Name . '</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
            }
            $content = json_encode($content, JSON_UNESCAPED_SLASHES);
            $content = trim($content, "\"");
            break;

        case "rev_fwd":
            $room_id = $_POST['inf'];
            $_SESSION['rev_offset'] < 0 ? $_SESSION['rev_offset'] = 0 : $_SESSION['rev_offset'] += 5;
            $rev_offset = $_SESSION['rev_offset'];
            $sql = "SELECT * FROM (SELECT r.ReservationId FROM reservations r JOIN rooms c ON r.RoomId=c.RoomId WHERE r.RoomId = $room_id) as x JOIN reviews w ON x.ReservationId = w.ReservationId LIMIT 5 OFFSET $rev_offset";
            $result = $db->query($sql);
            while ($row = $result->fetch_assoc()) {
                $ReviewTitle = $row['ReviewTitle'];
                $ReviewText = $row['ReviewText'];
                $ReservationId = $row['ReservationId'];
                $ReviewId = $row['ReviewId'];
                $ReviewStatus = $row['ReviewStatus'];
                $ReviewPicture = $row['ReviewPicture'];

                $sql2 = "SELECT * FROM reservations r JOIN customers c ON r.GuestId=c.UserId WHERE r.ReservationId = $ReservationId ";
                $result2 = $db->query($sql2);
                $row2 = $result2->fetch_assoc();
                $Name = $row2['FirstName'] . " " . $row2['LastName'];

                $content .= '<div class="row my-2 ps-5" style="width:100vw;">
                        <div class="col-11 m-0 p-0">
                            <div class="row m-0 p-0">
                                <div class="col-1 m-0 p-0 popup" style="height:15vh; width:15vh; overflow: hidden;">
                                    <img class="m-0 p-0" src="' . $ReviewPicture . '" alt="" style="height:100%;object-fit: cover;">
                                </div>
                                <div class="col-10 m-0 p-0">
                                    <div class="row m-0 p-0">
                                        <h2 class="m-2" style="color:var(--primary);font-size: 3vh;">' . $ReviewTitle . '</h2>
                                        <p class="m-2" style="color:var(--primary);font-size:2vh; text-align: justify; text-justify: inter-word;">' . $ReviewText . '</p>
                                        <p class="m-2" style="color:var(--primary);font-size:2vh; font-style:italic;">Review By : ' . $Name . '</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
            }
            $content = json_encode($content, JSON_UNESCAPED_SLASHES);
            $content = trim($content, "\"");
            break;

        default:
    }

    echo '{"content":"' . $content . '"}';
}
