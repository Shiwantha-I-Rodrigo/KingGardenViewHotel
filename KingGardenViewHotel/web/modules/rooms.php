<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/common.php';
$extra_js = '<script src="' . WEB_BASE_URL . 'js/rooms.js"></script>';
$extra_css = '';
isset($_SESSION['user_id']) ? $user_id = $_SESSION['user_id'] : $user_id = 0;
isset($_SESSION['TimeSlotStart']) ? $TimeSlotStart = $_SESSION['TimeSlotStart'] : $TimeSlotStart = 1;
isset($_SESSION['TimeSlotEnd']) ? $TimeSlotEnd = $_SESSION['TimeSlotEnd'] : $TimeSlotEnd = 2;
isset($_SESSION['guests']) ? $guests = $_SESSION['guests'] : $guests = 1;
isset($_SESSION['rooms']) ? $rooms = $_SESSION['rooms'] : $rooms = 1;
isset($_SESSION['discounted']) ? $discounted = $_SESSION['discounted'] : $discounted = 0;
isset($_SESSION['ac']) ? $ac = $_SESSION['ac'] : $ac = 0;
isset($_SESSION['wifi']) ? $wifi = $_SESSION['wifi'] : $wifi = 0;

$total_days = ceil(abs($TimeSlotStart - $TimeSlotEnd) / 60 / 60 / 24);
$columns = 3;
$extra_args = "";
$rooms_list = array();
$rooms_list2 = array();
$db = dbConn();

//construct filter arguments
if ($discounted == 1 || $ac == 1 || $wifi == 1) {
    $extra_args = " WHERE ";
    $discounted == 1 ? (($ac == 1 || $wifi == 1) ? $extra_args .= " RoomStatus = 6 AND " : $extra_args .= " RoomStatus = 6 ") : $extra_args = $extra_args;
    $ac == 1 ? (($wifi == 1) ? $extra_args .= " RoomAC = 1 AND " : $extra_args .= " RoomAC = 1 ") : $extra_args = $extra_args;
    $wifi == 1 ?  $extra_args .= " RoomWIFI = 1 " : $extra_args = $extra_args;
}

// get rooms with conflicting reservations
$sql = "SELECT * FROM rooms r JOIN reservations s ON r.RoomId = s.RoomId WHERE ( TimeSlotStart BETWEEN $TimeSlotStart AND $TimeSlotEnd ) OR ( TimeSlotEnd BETWEEN $TimeSlotStart AND $TimeSlotEnd)";
$result = $db->query($sql);
while ($row = $result->fetch_assoc()) {
    $rooms_list[] = $row['RoomId'];
}
// get all rooms except conflicting rooms
$sql = "SELECT * FROM rooms $extra_args";
$result = $db->query($sql);
while ($row = $result->fetch_assoc()) {
    if (!in_array($row['RoomId'], $rooms_list)) {

        $RoomId = $row['RoomId'];
        $sql2 = "SELECT * FROM reservations r JOIN rooms m ON r.RoomId = m.RoomId WHERE r.RoomId = $RoomId LIMIT 1 OFFSET 0";
        $result2 = $db->query($sql2);
        $row2 = $result2->fetch_assoc();

        $rooms_list2[] = array(
            "RoomId" => $row['RoomId'],
            "RoomName" => $row['RoomName'],
            "RoomPrice" => $row['RoomPrice'],
            "RoomAC" => $row['RoomAC'],
            "RoomWIFI" => $row['RoomWIFI'],
            "RoomCapacity" => $row['RoomCapacity'],
            "RoomPicture" => $row['RoomPicture'],
            "RoomStatus" => $row['RoomStatus'],
            "ReservationId" => $row2['ReservationId']
        );
    }
}

// handle form post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($user_id == 0) {
        reDirect("/web/modules/login.php");
    } elseif ($TimeSlotStart < 99 || $TimeSlotEnd < 99) {
        setHome();
        reDirect("/web/index.php");
    } else {
        extract($_POST);

        $sql = "SELECT * FROM users u JOIN customers c ON c.UserId=c.UserId WHERE u.UserId=$user_id";
        $result = $db->query($sql);
        $row = $result->fetch_assoc();

        $iter = array($room_id1, $room_id2, $room_id3);
        $res_ids = array();
        $n = 0;
        while ($n < $rooms) {
            $sql = "INSERT INTO reservations (GuestId, StaffId, RoomId, TimeSlotStart, TimeSlotEnd, ReservationStatus, Guests) VALUES ($user_id, $user_id, $iter[$n] , $TimeSlotStart, $TimeSlotEnd, 1, $guests)";
            $db->query($sql);
            $res_id = $db->insert_id;
            $room_array = $rooms_list2[array_search($iter[$n], array_column($rooms_list2, 'RoomId'))];
            $price = $room_array['RoomPrice'];
            $sql = "INSERT INTO items ( ReservationId ,ItemName ,ItemPrice ,ItemPaid ,ItemStatus ,ItemDiscount ,ItemComments ,Status ) values ($res_id,'room rent',$price,0,0,0,'',1)";
            $db->query($sql);
            array_push($res_ids, $res_id);
            $n++;
        }

        $start = getTime($TimeSlotStart);
        $end = getTime($TimeSlotEnd);
        $rooms_string = $room_id1;
        $room_id2 != 0 ? $rooms_string .= " / " . $room_id2 : null;
        $room_id3 != 0 ? $rooms_string .= " / " . $room_id3 : null;
        $res_string = $res_ids[0];
        isset($res_ids[1]) ? $res_string .= " / " . $res_ids[1] : null;
        isset($res_ids[2]) ? $res_string .= " / " . $res_ids[2] : null;

        $msg = "Dear " . $row['FirstName'] . ",<br/>Thank you for choosing King Garden View Hotel! Your reservation for " . $start . " is confirmed.<br/>" .
            "We're thrilled to host you for a memorable stay. Please review your booking details below. If you have any questions or special requests, feel free to reach out.<br/>" .
            "start date : " . $start . "<br/>" .
            "end date : " . $end . "<br/>" .
            "Room/s : " . $rooms_string . "<br/>" .
            "Guests : " . $guests . "<br/>" .
            "Reservation Id/s : " . $res_string . "<br/>" .
            "We look forward to providing you with an exceptional experience. Safe travels!<br/>" .
            "Warm regards,<br/>" .
            "Managing Director,<br/>" .
            "King garden View Hotel";

        sendEmail($row['Email'], $row['FirstName'], "Your Reservation Is Confirmed!", $msg);
        $_SESSION['alert_color'] = "var(--primary)";
        $_SESSION['alert_icon'] = "task_alt";
        $_SESSION['alert_title'] = "Reservation Succesful !";
        $_SESSION['alert_msg'] = "Accomadation Reserved Succesfully <br>Reservation ID 1 : " . $res_ids[0];
        $rooms > 1 ? $_SESSION['alert_msg'] .= "<br>Reservation ID 2 : " . $res_ids[1] : $_SESSION['alert_msg'] = $_SESSION['alert_msg'];
        $rooms > 2 ? $_SESSION['alert_msg'] .= "<br>Reservation ID 3 : " . $res_ids[2] : $_SESSION['alert_msg'] = $_SESSION['alert_msg'];
        reDirect('/web/sub/alert.php');
    }
}

ob_start();
?>

<div class="d-flex justify-content-around align-items-center text-center p-3 row" style="position:fixed; top:10vh; background-color:var(--secondary); z-index:95; width:100vw; ">
    <div class="col-5">
        <h4 style="font-size:3vh;">BOOK A ROOM</h4>
    </div>
</div>

<div style="position:absolute; top:18vh; background-image: var(--background_img_03); min-height:100vh;">
    <?php
    echo '<div class="row my-5 px-5 d-flex justify-content-around" style="width:100vw;">';
    switch ($rooms) {

        case 1:
            for ($i = 0, $k = 0; $i < count($rooms_list2); $i++) {
                if ($k % $columns === 0 && $k > 0) {
                    echo '</div><div class="row my-5 px-5 d-flex justify-content-around" style="width:100vw;">';
                }
                if ($rooms_list2[$i]["RoomStatus"] == 1 && ($rooms_list2[$i]["RoomCapacity"]) >= $guests) {
                    $k++;
                    echo '<div name="' . $rooms_list2[$i]["RoomId"] . '" id="' . $rooms_list2[$i]["RoomId"] . '" class="col-3 m-0 p-0 room" style="background-color:var(--background);border: 0.5vh solid var(--background);border-radius: 2vh;">
                        <div class="row">
                            <img class="m-0 p-0" src="' . $rooms_list2[$i]["RoomPicture"] . '" alt="" style="height: 25vh; object-fit: cover; border-radius: 2vh 2vh 0 0;">
                        </div>
                        <div class="p-2">
                            <label>' . $rooms_list2[$i]["RoomName"] . '</label>
                            <p>Price : Rs ' . $rooms_list2[$i]["RoomPrice"] . ' per person a night.<br>
                                Discounted price : Rs.' . ($rooms_list2[$i]["RoomPrice"]) * $total_days . ' for your entire stay<br>
                                Occupancy : ' . $rooms_list2[$i]["RoomCapacity"] . '<br>';
                    if ($rooms_list2[$i]["RoomWIFI"] == 1) {
                        echo '<i class="material-icons">wifi</i>';
                    }
                    if ($rooms_list2[$i]["RoomAC"] == 1) {
                        echo '<i class="material-icons">ac_unit</i>';
                    }
                    if ($rooms_list2[$i]["RoomPrice"] >= 4000) {
                        echo '<i class="material-icons">favorite</i>';
                    }
                    if ($rooms_list2[$i]["RoomPrice"] < 4000) {
                        echo '<i class="material-icons">attach_money</i>';
                    }
                    echo '<br/><a href="/web/modules/reviews.php?id='.$rooms_list2[$i]["ReservationId"].'">Read Reviews</a></p></div></div>';
                }
            }
            break;

        case 2:
            for ($i = 0, $k = 0; $i < count($rooms_list2); $i++) {
                for ($j = 0; $j < count($rooms_list2); $j++) {
                    if ($i != $j && $i < $j && ($rooms_list2[$i]["RoomCapacity"] + $rooms_list2[$j]["RoomCapacity"]) >= $guests) {
                        if ($k % $columns === 0 && $k > 0) {
                            echo '</div><div class="row my-5 px-5 d-flex justify-content-around" style="width:100vw;">';
                        }
                        $k++;
                        echo '<div name="' . $rooms_list2[$i]["RoomId"] . '_' . $rooms_list2[$j]["RoomId"] . '" id="' . $rooms_list2[$i]["RoomId"] . '_' . $rooms_list2[$j]["RoomId"] . '" class="col-3 m-0 p-0 room" style="background-color:var(--background);border: 0.5vh solid var(--background);border-radius: 2vh;">
                                <div class="row">
                                    <div class="col-6 m-0 p-0">
                                        <img class="m-0 p-0" src="' . $rooms_list2[$i]["RoomPicture"] . '" alt="" style="height: 25vh; width: 100%; object-fit: cover; border-radius: 2vh 0vh 0 0;">
                                    </div>
                                    <div class="col-6 m-0 p-0">
                                        <img class="m-0 p-0" src="' . $rooms_list2[$j]["RoomPicture"] . '" alt="" style="height: 25vh; width: 100%; object-fit: cover; border-radius: 0vh 2vh 0 0;">
                                    </div>
                                </div>
                                <div class="p-2">
                                    <label>' . $rooms_list2[$i]["RoomName"] . ' & ' . $rooms_list2[$j]["RoomName"] . '</label>
                                    <p>Price : Rs ' . $rooms_list2[$i]["RoomPrice"] +  $rooms_list2[$j]["RoomPrice"] . ' per night.<br>
                                        Discounted price Rs.' . ($rooms_list2[$i]["RoomPrice"] +  $rooms_list2[$j]["RoomPrice"]) * $total_days . ' for your entire stay<br>
                                        Occupancy : ' . $rooms_list2[$i]["RoomCapacity"] . ' + ' . $rooms_list2[$j]["RoomCapacity"] . '</p><br>';
                        echo ' <label>' .  $rooms_list2[$i]["RoomName"] . '</label>';
                        if ($rooms_list2[$i]["RoomWIFI"] == 1) {
                            echo '<i class="material-icons">wifi</i>';
                        }
                        if ($rooms_list2[$i]["RoomAC"] == 1) {
                            echo '<i class="material-icons">ac_unit</i>';
                        }
                        if ($rooms_list2[$i]["RoomPrice"] >= 4000) {
                            echo '<i class="material-icons">favorite</i>';
                        }
                        if ($rooms_list2[$i]["RoomPrice"] < 4000) {
                            echo '<i class="material-icons">attach_money</i>';
                        }
                        echo '<br/><a href="/web/modules/reviews.php?id='.$rooms_list2[$i]["ReservationId"].'">Read Reviews</a><br/>';
                        echo '<br/><label>' .  $rooms_list2[$j]["RoomName"] . '</label>';
                        if ($rooms_list2[$j]["RoomWIFI"] == 1) {
                            echo '<i class="material-icons">wifi</i>';
                        }
                        if ($rooms_list2[$j]["RoomAC"] == 1) {
                            echo '<i class="material-icons">ac_unit</i>';
                        }
                        if ($rooms_list2[$j]["RoomPrice"] >= 4000) {
                            echo '<i class="material-icons">favorite</i>';
                        }
                        if ($rooms_list2[$j]["RoomPrice"] < 4000) {
                            echo '<i class="material-icons">attach_money</i>';
                        }
                        echo '<br/><a href="/web/modules/reviews.php?id='.$rooms_list2[$j]["ReservationId"].'">Read Reviews</a>';
                        echo '</div></div>';
                    }
                }
            }
            break;

        case 3:
            for ($i = 0, $z = 0; $i < count($rooms_list2); $i++) {
                for ($j = 0; $j < count($rooms_list2); $j++) {
                    for ($k = 0; $k < count($rooms_list2); $k++) {
                        if ($i != $j && $i != $k && $j != $k && $i < $j && $j < $k && ($rooms_list2[$i]["RoomCapacity"] + $rooms_list2[$j]["RoomCapacity"] + $rooms_list2[$k]["RoomCapacity"]) >= $guests) {
                            if ($z % $columns === 0 && $z > 0) {
                                echo '</div><div class="row my-5 px-5 d-flex justify-content-around" style="width:100vw;">';
                            }
                            $z++;
                            echo '<div name="' . $rooms_list2[$i]["RoomId"] . '_' . $rooms_list2[$j]["RoomId"] . '_' . $rooms_list2[$k]["RoomId"] . '" id="' . $rooms_list2[$i]["RoomId"] . '_' . $rooms_list2[$j]["RoomId"] . '_' . $rooms_list2[$k]["RoomId"] . '" class="col-3 m-0 p-0 room" style="background-color:var(--background);border: 0.5vh solid var(--background);border-radius: 2vh;">
                                    <div class="row">
                                        <div class="col-4 p-0 m-0">
                                            <img class="m-0 p-0" src="' . $rooms_list2[$i]["RoomPicture"] . '" alt="" style="height: 25vh; width: 100%; object-fit: cover; border-radius: 2vh 0 0 0;">
                                        </div>
                                        <div class="col-4 p-0 m-0">
                                            <img class="m-0 p-0" src="' . $rooms_list2[$j]["RoomPicture"] . '" alt="" style="height: 25vh; width: 100%; object-fit: cover; border-radius: 0 0 0 0;">
                                        </div>
                                        <div class="col-4 p-0 m-0">
                                            <img class="m-0 p-0" src="' . $rooms_list2[$k]["RoomPicture"] . '" alt="" style="height: 25vh; width: 100%; object-fit: cover; border-radius: 0 2vh 0 0;">
                                        </div>
                                    </div>
                                    <div class="p-2">
                                        <label>' .  $rooms_list2[$i]["RoomName"] . ' & ' . $rooms_list2[$j]["RoomName"] . ' & ' . $rooms_list2[$k]["RoomName"] . '</label>
                                        <p>Price : Rs ' . $rooms_list2[$i]["RoomPrice"] +  $rooms_list2[$j]["RoomPrice"] +  $rooms_list2[$k]["RoomPrice"] . ' per night.<br>
                                            Discounted price Rs.' . ($rooms_list2[$i]["RoomPrice"] +  $rooms_list2[$j]["RoomPrice"] +  $rooms_list2[$k]["RoomPrice"]) * $total_days . ' for your entire stay<br>
                                            Occupancy : ' . $rooms_list2[$i]["RoomCapacity"] . ' + ' . $rooms_list2[$j]["RoomCapacity"] . ' + ' . $rooms_list2[$k]["RoomCapacity"] . '</p><br>';
                            echo ' <label>' .  $rooms_list2[$i]["RoomName"] . '</label>';
                            if ($rooms_list2[$i]["RoomWIFI"] == 1) {
                                echo '<i class="material-icons">wifi</i>';
                            }
                            if ($rooms_list2[$i]["RoomAC"] == 1) {
                                echo '<i class="material-icons">ac_unit</i>';
                            }
                            if ($rooms_list2[$i]["RoomPrice"] >= 4000) {
                                echo '<i class="material-icons">favorite</i>';
                            }
                            if ($rooms_list2[$i]["RoomPrice"] < 4000) {
                                echo '<i class="material-icons">attach_money</i>';
                            }
                            echo '<br/><a href="/web/modules/reviews.php?id='.$rooms_list2[$i]["ReservationId"].'">Read Reviews</a><br/>';
                            echo '<br/><label>' .  $rooms_list2[$j]["RoomName"] . '</label>';
                            if ($rooms_list2[$j]["RoomWIFI"] == 1) {
                                echo '<i class="material-icons">wifi</i>';
                            }
                            if ($rooms_list2[$j]["RoomAC"] == 1) {
                                echo '<i class="material-icons">ac_unit</i>';
                            }
                            if ($rooms_list2[$j]["RoomPrice"] >= 4000) {
                                echo '<i class="material-icons">favorite</i>';
                            }
                            if ($rooms_list2[$j]["RoomPrice"] < 4000) {
                                echo '<i class="material-icons">attach_money</i>';
                            }
                            echo '<br/><a href="/web/modules/reviews.php?id='.$rooms_list2[$j]["ReservationId"].'">Read Reviews</a><br/>';
                            echo '<br/><label>' .  $rooms_list2[$k]["RoomName"] . '</label>';
                            if ($rooms_list2[$k]["RoomWIFI"] == 1) {
                                echo '<i class="material-icons">wifi</i>';
                            }
                            if ($rooms_list2[$k]["RoomAC"] == 1) {
                                echo '<i class="material-icons">ac_unit</i>';
                            }
                            if ($rooms_list2[$k]["RoomPrice"] >= 4000) {
                                echo '<i class="material-icons">favorite</i>';
                            }
                            if ($rooms_list2[$k]["RoomPrice"] < 4000) {
                                echo '<i class="material-icons">attach_money</i>';
                            }
                            echo '<br/><a href="/web/modules/reviews.php?id='.$rooms_list2[$k]["ReservationId"].'">Read Reviews</a><br/>';
                            echo '</div></div>';
                        }
                    }
                }
            }
    }
    echo '</div><div class="row" style="height:10vh;"></div>';
    ?>
</div>

<!-- Book Now Button -->
<div id="booknow">
    <div class="success-btn" id="book_btn">Book Now!</div>
</div>

<!-- Reservation Modal -->
<div class="modal fade" id="Reservation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background-color:var(--background);">
            <div class="modal-header d-flex justify-content-between">
                <img src="<?= BASE_URL . '/img/common/logo_logo.png' ?>" alt="" style="width: 3vw; height: 5vh; object-fit: cover;">
                <label class="mx-3" style="font-size:3vh;">Confirm Reservation</label>
                <button type="button" class="clear_btn" data-bs-dismiss="modal"><i class="material-icons">cancel</i></button>
            </div>
            <div class="modal-body" style="color:var(--primary_font); text-align: justify; text-justify: inter-word; display:inline-block">
                Are you sure you want to confirm the reservation for Room <div id="room-id" style="display:inline-block"></div> ?
                <div id="room-details"></div>
            </div>
            <div class="modal-footer">
                <form id="room_form" enctype="multipart/form-data" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" role="form" novalidate>
                    <input type="text" class="d-none" name="room_id1" id="room_id1" required />
                    <input type="text" class="d-none" name="room_id2" id="room_id2" required />
                    <input type="text" class="d-none" name="room_id3" id="room_id3" required />
                    <button class="success-btn px-3" type="submit" form="room_form" formmethod="post">Confirm</button>
                </form>
                <button type="button" class="fail-btn px-3" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<?php
$page_content = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/web/layout.php';
?>