<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/common.php';
$extra_js = '<script src="' . WEB_BASE_URL . 'js/rooms.js"></script>';
$extra_css = '';
$rooms_list = array();
$rooms_details = array();
$db = dbConn();
$sql = "SELECT * FROM rooms";
$result = $db->query($sql);
while ($row = $result->fetch_assoc()) {
    $rooms_list[] = $row['RoomId'];
}
isset($_SESSION['user_id']) ? $user_id = $_SESSION['user_id'] : $user_id = 0;
isset($_SESSION['TimeSlotStart']) ? $TimeSlotStart = $_SESSION['TimeSlotStart'] : $TimeSlotStart = 0;
isset($_SESSION['TimeSlotEnd']) ? $TimeSlotEnd = $_SESSION['TimeSlotEnd'] : $TimeSlotEnd = 0;
isset($_SESSION['ItemPrice']) ? $ItemPrice = $_SESSION['ItemPrice'] : $ItemPrice = 0;
isset($_SESSION['rooms']) ? $rooms = $_SESSION['rooms'] : $rooms = $rooms_list;
foreach ($rooms as $id) {
    $req = "SELECT * FROM rooms where RoomId = " . $id;
    $reply = $db->query($req);
    while ($row = $reply->fetch_assoc()) {
        $rooms_details[] = array(
            "RoomId" => $row['RoomId'], "RoomName" => $row['RoomName'], "RoomPrice" => $row['RoomPrice'], "RoomAC" => $row['RoomAC'], "RoomWIFI" => $row['RoomWIFI'],
            "RoomCapacity" => $row['RoomCapacity'], "RoomPicture" => $row['RoomPicture'], "RoomStatus" => $row['RoomStatus']
        );
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($user_id == 0) {
        reDirect("/web/modules/login.php");
    } elseif ($TimeSlotStart == 0 || $TimeSlotEnd == 0 || $ItemPrice == 0) {
        setHome();
        reDirect("/web/index.php");
    } else {
        extract($_POST);
        $db = dbConn();
        $sql = "INSERT INTO reservations (GuestId, StaffId, RoomId, TimeSlotStart, TimeSlotEnd, ReservationStatus) VALUES ($user_id, $user_id, $room_id , $TimeSlotStart, $TimeSlotEnd, 1)";
        $db->query($sql);
        $ReservationId = $db->insert_id;
        $_SESSION['alert_color'] = "var(--primary)";
        $_SESSION['alert_icon'] = "task_alt";
        $_SESSION['alert_title'] = "Reservation Succesful !";
        $_SESSION['alert_msg'] = "Accomadation Reserved Succesfully <br>Reservation ID : " . $ReservationId;
        reDirect('/web/sub/alert.php');
    }

}

ob_start();
?>

<div style="position:absolute; top:10vh; background-image: var(--background_img_03);">
    <?php
    $count_items = count($rooms_list);
    $columns = 3;
    echo '<div class="row my-5 px-5 d-flex justify-content-around" style="width:100vw;">';
    for ($i = 0, $k = 0; $i < $count_items; $i++) {
        if ($k % $columns === 0 && $k > 0) {
            echo '</div><div class="row my-5 px-5 d-flex justify-content-around" style="width:100vw;">';
        }
        if ($rooms_details[$i]["RoomStatus"] == 1) {
            $k++;
            echo '<div name="' . $rooms_details[$i]["RoomId"] . '" id="' . $rooms_details[$i]["RoomId"] . '" class="col-3 m-0 p-0 room" style="background-color:var(--background);border: 0.5vh solid var(--background);border-radius: 2vh;">
                <div class="row">
                    <img class="m-0 p-0" src="' . $rooms_details[$i]["RoomPicture"] . '" alt="" style="height: 25vh; object-fit: cover; border-radius: 2vh 2vh 0 0;">
                </div>
                <div class="p-2">
                    <label>' . $rooms_details[$i]["RoomName"] . '</label>
                    <p>Price : Rs ' . $rooms_details[$i]["RoomPrice"] . ' per person a night.<br>
                        Discounted price Rs.' . $ItemPrice . ' for your entire stay<br>
                        occupancy : ' . $rooms_details[$i]["RoomCapacity"] . '<br>';
            if ($rooms_details[$i]["RoomWIFI"] == 1) {
                echo '<i class="material-icons">wifi</i>';
            }
            if ($rooms_details[$i]["RoomAC"] == 1) {
                echo '<i class="material-icons">ac_unit</i>';
            }
            if ($rooms_details[$i]["RoomPrice"] >= 4000) {
                echo '<i class="material-icons">favorite</i>';
            }
            if ($rooms_details[$i]["RoomPrice"] < 4000) {
                echo '<i class="material-icons">attach_money</i>';
            }
            echo '</p></div></div>';
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
                Are you sure you want to confirm the reservation for <div id="room-id" style="display:inline-block"></div> ?
                <div id="room-details"></div>
            </div>
            <div class="modal-footer">
                <form id="room_form" enctype="multipart/form-data" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" role="form" novalidate>
                    <input type="text" class="d-none" name="room_id" id="room_id" required />
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