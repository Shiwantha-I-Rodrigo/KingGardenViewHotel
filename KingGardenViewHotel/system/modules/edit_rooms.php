<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/common.php';
isset($_SESSION['user_id']) ? $user_id = $_SESSION['user_id'] : reDirect("/system/modules/login.php");
authorize($user_id, '5', 'system');
$extra_js = '<script src="' . SYSTEM_BASE_URL . 'js/edit_rooms.js"></script>';
$extra_css = '';

$db = dbConn();

require_once $_SERVER['DOCUMENT_ROOT'] . '/system/sub/user_info.php';

$url =  basename($_SERVER['REQUEST_URI']);
$url_componenets = parse_url($url);
parse_str($url_componenets['query'], $params);
isset($params['id']) ? $room_id = $params['id'] :  $room_id = 0;

if ($room_id != 0) {
    $sql = "SELECT * FROM rooms WHERE RoomId = $room_id";
    $result = $db->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $room_picture = $row['RoomPicture'];
            $room_name = $row['RoomName'];
            $room_capacity = $row['RoomCapacity'];
            $room_price = $row['RoomPrice'];
            $room_ac = $row['RoomAC'];
            $room_wifi = $row['RoomWIFI'];
            $room_status = $row['RoomStatus'];
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    extract($_POST);

    $upload = "";
    if (!empty($_FILES['file_upload']['name'])) {
        $path =  $_SERVER['DOCUMENT_ROOT'] . '/img/rooms/';
        $file = uploadFile($path, $_FILES, "system");
        $full_path = '/img/rooms/' . $file;
        $upload = ",`RoomPicture`='$full_path'";
    }

    if ($room_id != 0) {
        $sql = "UPDATE rooms SET `RoomName`='$room_name',`RoomCapacity`='$room_capacity', `RoomPrice`='$room_price',`RoomAC`='$room_ac', `RoomWIFI`='$room_wifi',`RoomStatus`='$room_status'  $upload WHERE RoomId=$room_id";
        $db->query($sql);
    } else {
        $sql = "INSERT INTO rooms (RoomName, RoomPrice, RoomAC, RoomWIFI, RoomCapacity, RoomPicture, RoomStatus) VALUES ('$room_name', $room_price, $room_ac, $room_wifi, $room_capacity, '$full_path', $room_status)";
        $db->query($sql);
    }

    $_SESSION['alert_color'] = "var(--primary)";
    $_SESSION['alert_icon'] = "task_alt";
    $_SESSION['alert_title'] = "Success !";
    $_SESSION['alert_msg'] = "The information was updated succesfully";
    reDirect('/system/sub/alert.php');
}

ob_start();
?>

<section style="background-color:var(--shadow);">
    <div class="row" style="height:10vh;"></div>
    <div class="row mx-5">
        <div class="col-3">
            <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/system/sub/edit_tools.php'; ?>
        </div>
        <div class="col-7">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="my-4 text-center"><label class="my-1" style="font-size : 2vh;">ROOM NO. <?= $room_id ?></label></div>

                    <form id="reg_form" enctype="multipart/form-data" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $room_id; ?>" method="post" role="form" novalidate>

                        <div class="row mx-5">
                            <div class="col-6 d-flex justify-content-start align-items-bottom">
                                <label>Room Name</label>
                            </div>
                            <div class="col-6 d-flex justify-content-start align-items-bottom">
                                <label>Room Price</label>
                            </div>
                        </div>
                        <div class="row mx-5">
                            <div class="col-6 d-flex justify-content-end align-items-center">
                                <input type="text" name="room_name" id="room_name" value="<?= $room_name ?>" placeholder="Room Name" required />
                            </div>
                            <div class="col-6 d-flex justify-content-start align-items-center">
                                <input type="text" name="room_price" id="room_price" value="<?= $room_price ?>" placeholder="Room Price" required />
                            </div>
                        </div>
                        <div class="row mx-5 mt-3">
                            <div class="col-6 d-flex justify-content-start align-items-bottom">
                                <label>Room Capacity</label>
                            </div>
                            <div class="col-6 d-flex justify-content-start align-items-bottom">
                                <label>Room Status</label>
                            </div>
                        </div>
                        <div class="row mx-5">
                            <div class="col-6 d-flex justify-content-end align-items-center">
                                <input type="text" name="room_capacity" id="room_capacity" value="<?= $room_capacity ?>" placeholder="Room Capacity" required />
                            </div>
                            <div class="col-6 d-flex justify-content-start">
                                <select class="" name="room_status" id="room_status">
                                    <option <?php echo ($room_status == 0) ? 'selected' : ''; ?> value="0">Inactive</option>
                                    <option <?php echo ($room_status == 1) ? 'selected' : ''; ?> value="1">Active</option>
                                    <option <?php echo ($room_status == 2) ? 'selected' : ''; ?> value="2">Unavailable</option>
                                    <option <?php echo ($room_status == 3) ? 'selected' : ''; ?> value="3">Unauthorized</option>
                                    <option <?php echo ($room_status == 4) ? 'selected' : ''; ?> value="4">Invalid</option>
                                    <option <?php echo ($room_status == 5) ? 'selected' : ''; ?> value="5">Reserved</option>
                                    <option <?php echo ($room_status == 6) ? 'selected' : ''; ?> value="6">Discounted</option>
                                    <option <?php echo ($room_status == 9) ? 'selected' : ''; ?> value="6">Forbidden</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mx-5 mt-3">
                            <div class="col-6 d-flex justify-content-start align-items-bottom">
                                <label>Room Ac</label>
                            </div>
                            <div class="col-6 d-flex justify-content-start align-items-bottom">
                                <label>Room WiFi</label>
                            </div>
                        </div>
                        <div class="row mx-5 mt-3">
                            <div class="col-6 d-flex justify-content-start">
                                <select class="" name="room_ac" id="room_ac">
                                    <option <?php echo ($room_ac == 0) ? 'selected' : ''; ?> value="0">Unavailable</option>
                                    <option <?php echo ($room_ac == 1) ? 'selected' : ''; ?> value="1">Available</option>
                                </select>
                            </div>
                            <div class="col-6 d-flex justify-content-start">
                                <select class="" name="room_wifi" id="room_wifi">
                                    <option <?php echo ($room_wifi == 0) ? 'selected' : ''; ?> value="0">Unavailable</option>
                                    <option <?php echo ($room_wifi == 1) ? 'selected' : ''; ?> value="1">Available</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mx-5 mt-3">
                            <div class="col-6 d-flex justify-content-start align-items-bottom">
                                <label>Profile Picture</label>
                            </div>
                        </div>
                        <div class="row mx-5 mt-2">
                            <div class="col-6 d-flex justify-content-end align-items-center">
                                <input type="file" id="file_upload" name="file_upload" accept="image/*" />
                            </div>
                        </div>

                    </form>

                    <div class="row mx-5 my-5">
                        <div class="col-12 d-flex justify-content-start">
                            <img class="m-0 p-0" src="<?= $room_picture ?>" alt="" style="width: 100%; object-fit: cover; border-radius: 1vh 1vh 1vh 1vh;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/system/sub/sidebar.php'; ?>
    </div>
    <div class="row" style="height:10vh;"></div>
</section>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/system/sub/modals.php';
$page_content = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/system/layout.php';
?>