<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/common.php';
isset($_SESSION['user_id']) ? $user_id = $_SESSION['user_id'] : reDirect("/system/modules/login.php");
authorize($user_id, '8', 'system');
$extra_js = '<script src="' . SYSTEM_BASE_URL . 'js/edit_reservations.js"></script>';
$extra_css = '';

$db = dbConn();

require_once $_SERVER['DOCUMENT_ROOT'] . '/system/sub/user_info.php';

$url =  basename($_SERVER['REQUEST_URI']);
$url_componenets = parse_url($url);
parse_str($url_componenets['query'], $params);
isset($params['id']) ? $res_id = $params['id'] :  $res_id = 0;

$reservations = array();
if ($res_id != 0) {
    $sql = "SELECT * FROM (SELECT x.GuestId, x.TimeSlotStart FROM reservations x WHERE x.ReservationId = $res_id) AS r JOIN reservations m ON r.GuestId = m.GuestId WHERE r.TimeSlotStart = m.TimeSlotStart";
    $result = $db->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            $reservations[] = array(
                "ReservationId" => $row['ReservationId'],
                "StaffId" => $row['StaffId'],
                "GuestId" => $row['GuestId'],
                "RoomId" => $row['RoomId'],
                "TimeSlotStart" => $row['TimeSlotStart'],
                "TimeSlotEnd" => $row['TimeSlotEnd'],
                "Guests" => $row['Guests'],
                "ReservationStatus" => $row['ReservationStatus']
            );
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    extract($_POST);

    for ($i = 0; $i < count($reservations); $i++) {
        $sql = "UPDATE reservations SET `ReservationStatus`=${'reservation_status' .$i}  WHERE ReservationId=${'reservation_id' .$i}";
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
            <div class="card mb-4 pt-5">
                <div class="row mx-5 mt-3">
                    <div class="col-3 d-flex justify-content-start align-items-bottom">
                        <label>Start date</label>
                    </div>
                    <div class="col-3 d-flex justify-content-start align-items-bottom">
                        <label><?= $reservations[0]["TimeSlotStart"] ?></label>
                    </div>
                    <div class="col-3 d-flex justify-content-start align-items-bottom">
                        <label>End date</label>
                    </div>
                    <div class="col-3 d-flex justify-content-start align-items-bottom">
                        <label><?= $reservations[0]["TimeSlotEnd"] ?></label>
                    </div>
                </div>
                <div class="row mx-5 mt-3">
                    <div class="col-3 d-flex justify-content-start align-items-bottom">
                        <label>Reserved User</label>
                    </div>
                    <div class="col-3 d-flex justify-content-start align-items-bottom">
                        <label><?= $reservations[0]["StaffId"] ?></label>
                    </div>
                    <div class="col-3 d-flex justify-content-start align-items-bottom">
                        <label>Guest Id</label>
                    </div>
                    <div class="col-3 d-flex justify-content-start align-items-bottom">
                        <label><?= $reservations[0]["GuestId"] ?></label>
                    </div>
                </div>
                <div class="card-body">
                    <form id="reg_form" enctype="multipart/form-data" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $res_id; ?>" method="post" role="form" novalidate>
                        <?php
                        for ($i = 0; $i < count($reservations); $i++) {

                            $reservations[$i]["ReservationStatus"] == 0 ? $checkin = 'selected' : $checkin = '';
                            $reservations[$i]["ReservationStatus"] == 5 ? $checkout = 'selected' : $checkout = '';
                            $reservations[$i]["ReservationStatus"] == 7 ? $cancelled = 'selected' : $cancelled = '';
                            $reservations[$i]["ReservationStatus"] == 8 ? $noshow = 'selected' : $noshow = '';
                            $reservations[$i]["ReservationStatus"] == 1 ? $active = 'selected' : $active = '';

                            echo '
                        <div class="my-4 text-center"><label class="my-1" style="font-size : 2vh; color : var(--fail)">RESERVATION NO.' . $reservations[$i]["ReservationId"] . '</label></div>
                        <input type="text" name="reservation_id' . $i . '" id="reservation_id' . $i . '" value="' . $reservations[$i]["ReservationId"] . '" class="d-none" readonly />
                        <div class="row mx-5">
                            <div class="col-3 d-flex justify-content-start align-items-bottom">
                                <label>Room Id</label>
                            </div>
                            <div class="col-3 d-flex justify-content-start align-items-bottom">
                                <label>' . $reservations[$i]["RoomId"] . '</label>
                            </div>
                            <div class="col-3 d-flex justify-content-start align-items-bottom">
                                <label>Reservation Status</label>
                            </div>
                            <div class="col-3 d-flex justify-content-start align-items-center">
                                <select class="w-50" name="reservation_status' . $i . '" id="reservation_status' . $i . '">
                                    <option ' . $checkin . 'value="0">Check out</option>
                                    <option ' . $checkout . ' value="5">Check in</option>
                                    <option ' . $cancelled . ' value="7">Cancelled</option>
                                    <option ' . $noshow . '  value="8">No Show</option>
                                    <option ' . $active . '  value="1">Active</option>
                                </select>
                            </div>
                        </div>
                        <input type="text" name="x" id="x" class="" readonly />
                            ';
                        }
                        ?>
                        <div class="my-4 text-center"><label class="my-1" style="font-size : 2vh;">This room reservation has [ <?= count($reservations) ?> ] associated reservations. intended is for [ <?= $reservations[0]["Guests"] ?> ] guests.</label></div>
                    </form>
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