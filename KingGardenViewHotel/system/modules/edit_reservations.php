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

            $items = array();
            $res_id = $row['ReservationId'];
            $sql2 = "SELECT * FROM items Where ReservationId = $res_id";
            $result2 = $db->query($sql2);
            while ($row2 = $result2->fetch_assoc()) {
                $items[] = array(
                    "ItemId" => $row2['ItemId'],
                    "ItemName" => $row2['ItemName'],
                    "ItemPrice" => $row2['ItemPrice'],
                    "ItemPaid" => $row2['ItemPaid'],
                    "ItemStatus" => $row2['ItemStatus'],
                    "ItemDiscount" => $row2['ItemDiscount']
                );
            }

            $reservations[] = array(
                "ReservationId" => $row['ReservationId'],
                "StaffId" => $row['StaffId'],
                "GuestId" => $row['GuestId'],
                "RoomId" => $row['RoomId'],
                "TimeSlotStart" => $row['TimeSlotStart'],
                "TimeSlotEnd" => $row['TimeSlotEnd'],
                "Guests" => $row['Guests'],
                "ReservationStatus" => $row['ReservationStatus'],
                "Items" => $items
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
            <div class="card">
                <div class="d-flex justify-content-between">
                    <a class="success-btn p-2 m-2 align-items-center" id="print_btn"><i class="material-icons">print</i></a>
                </div>
                <div class="card-body">
                    <div id="print_page" class="d-flex justify-content-center">
                        <table>

                            <tr>
                                <th align="center" colspan="4"><img class="d-none" style="height:8vh;" src="<?= BASE_URL . '/img/common/logo_logo.png' ?>" alt="logo"></th>
                            </tr>
                            <tr>
                                <th align="center" colspan="4">
                                    <p class="d-none" style="font-size:4vh;"> KING GARDEN VIEW HOTEL </p>
                                </th>
                            </tr>
                            <tr>
                                <th align="center" colspan="4">
                                    <p class="d-none" style="font-size:3vh;"> INVOICE</p>
                                </th>
                            </tr>
                            <tr>
                                <th align="center" colspan="4">
                                    <p class="d-none" style="font-size:2vh;"> This invoice is generated on <?= getTimes(time()); ?> by user : <?= $user_id; ?></p>
                                </th>
                            </tr>
                            <tr>
                                <th align="center" colspan="4">
                                    <div class="my-4 text-center"><label class="my-1" style="font-size : 2vh;">The reservation group contains [ <?= count($reservations) ?> ] associated reservations for a total of [ <?= $reservations[0]["Guests"] ?> ] guests.</label></div>
                                </th>
                            </tr>

                            <tr>
                                <td>
                                    <label>Start date</label>
                                </td>
                                <td>

                                    <label><?= getTime($reservations[0]["TimeSlotStart"]) ?></label>
                                </td>
                                <td>
                                    <label>End date</label>
                                </td>
                                <td>
                                    <label><?= getTime($reservations[0]["TimeSlotEnd"]) ?></label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Booking User</label>
                                </td>
                                <td>
                                    <label><?= $reservations[0]["StaffId"] ?></label>
                                </td>
                                <td>
                                    <label>Guest Id</label>
                                </td>
                                <td>
                                    <label><?= $reservations[0]["GuestId"] ?></label>
                                </td>
                            </tr>


                            <form id="reg_form" enctype="multipart/form-data" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $res_id; ?>" method="post" role="form" novalidate>
                                <?php
                                for ($i = 0; $i < count($reservations); $i++) {

                                    $reservations[$i]["ReservationStatus"] == 0 ? $checkin = 'selected' : $checkin = '';
                                    $reservations[$i]["ReservationStatus"] == 5 ? $checkout = 'selected' : $checkout = '';
                                    $reservations[$i]["ReservationStatus"] == 7 ? $cancelled = 'selected' : $cancelled = '';
                                    $reservations[$i]["ReservationStatus"] == 8 ? $noshow = 'selected' : $noshow = '';
                                    $reservations[$i]["ReservationStatus"] == 1 ? $active = 'selected' : $active = '';

                                    echo '
                                    <tr><th colspan="4">.</th></tr>
                                    <tr><th colspan="4">.</th></tr>
                                    <tr><th align="center" colspan="4">
                                    <div class="my-4 text-center"><label class="my-1" style="font-size : 2vh; color : var(--fail)">RESERVATION NO.' . $reservations[$i]["ReservationId"] . '</label></div>
                                    </th></tr>

                                    <input type="text" name="reservation_id' . $i . '" id="reservation_id' . $i . '" value="' . $reservations[$i]["ReservationId"] . '" class="d-none" readonly />

                                    <tr><th colspan="2">
                                    <label>Room Id</label>
                                    </th><th colspan="2">
                                    <label>' . $reservations[$i]["RoomId"] . '</label>
                                    </th></tr>
                                    
                                    <tr><th colspan="2">
                                    <label>Reservation Status</label>
                                    </th><th colspan="2">
                                    <select class="w-50" name="reservation_status' . $i . '" id="reservation_status' . $i . '">
                                        <option ' . $checkin . 'value="0">Check out</option>
                                        <option ' . $checkout . ' value="5">Check in</option>
                                        <option ' . $cancelled . ' value="7">Cancelled</option>
                                        <option ' . $noshow . '  value="8">No Show</option>
                                        <option ' . $active . '  value="1">Active</option>
                                    </select>
                                    </th></tr>
                                    <tr><th colspan="4">.</th></tr>';

                                    for ($j = 0; $j < count($reservations[$i]["Items"]); $j++) {

                                        echo '
                                        <tr><td>
                                        <label>Item Name</label>
                                        </td><td>
                                        <label>' . $reservations[$i]["Items"][$j]["ItemName"] . '</label>
                                        </td><td>
                                        <label>Item Status</label>
                                        </td><td>
                                        <label>' . getItemStatus($reservations[$i]["Items"][$j]["ItemStatus"]) . '</label>
                                        </td></tr>

                                        <tr><td>
                                        <label>Item Price</label>
                                        </td><td>
                                        <label>' . $reservations[$i]["Items"][$j]["ItemPrice"] . '</label>
                                        </td><td>
                                        <label>Item Paid</label>
                                        </td><td>
                                        <label>' . $reservations[$i]["Items"][$j]["ItemPaid"] . '</label>
                                        </td></tr>';
                                    }
                                }
                                ?>
                            </form>
                        </table>
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