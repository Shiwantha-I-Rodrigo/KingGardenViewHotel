<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/common.php';
isset($_SESSION['user_id']) ? $user_id = $_SESSION['user_id'] : reDirect("/system/modules/login.php");
authorize($user_id, '7', 'system');
$extra_js = '<script src="' . SYSTEM_BASE_URL . 'js/list_reservations.js"></script>';
$extra_css = '';

$db = dbConn();

require_once $_SERVER['DOCUMENT_ROOT'] . '/system/sub/user_info.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    extract($_POST);
    if (isset($id)) {
        $sql = "DELETE FROM reservations WHERE ReservationId = $id";
        $result = $db->query($sql);
        if ($result) {
            echo '<div id="removed"></div>';
        }
    }
}

$sort_options = '<option value="r.ReservationId">Res. Id</option>
<option value="r.GuestId">Guest Id</option>
<option value="r.StaffId">Staff Id</option>
<option value="r.RoomId">Room Id</option>
<option value="r.TimeSlotStart">Check In</option>
<option value="r.TimeSlotEnd">Check Out</option>
<option value="r.ReservationStatus">Res. Status</option>
<option value="t.Price">Price</option>
<option value="t.Paid">Paid</option>';

$range_options = '<option value="r.ReservationId">Res. Id</option>
<option value="r.GuestId">Guest Id</option>
<option value="r.StaffId">Staff Id</option>
<option value="r.RoomId">Room Id</option>
<option value="r.TimeSlotStart">Check In</option>
<option value="r.TimeSlotEnd">Check Out</option>
<option value="t.Price">Price</option>
<option value="t.Paid">Paid</option>';

ob_start();
?>

<section style="background-color:var(--shadow);">
    <div class="row" style="height:10vh;"></div>
    <div class="row mx-5">
        <div class="col-3">
            <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/system/sub/list_tools.php'; ?>
        </div>
        <div class="col-7">
            <div class="card mb-4">
                <div class="card-body">
                <div class="d-flex justify-content-between">
                        <a class="success-btn p-2 m-0 align-items-center" id="print_btn"><i class="material-icons">print</i></a>
                        <a href="/system/modules/edit_reservations.php" class="success-btn p-2 m-0 align-items-center"><i class="material-icons">add</i></a>
                    </div>
                    <div class="my-4 text-center"><label class="my-1" style="font-size : 2vh;">RESERVATIONS</label></div>
                    <div id="print_page">
                        <div>
                            <img class="d-none" style="height:8vh;" src="<?= BASE_URL . '/img/common/logo_logo.png' ?>" alt="logo">
                            <p class="d-none" style="font-size:2vh;"> RESERVATION REPORT</p>
                            <p class="d-none" style="font-size:2vh;"> This report is generated on <?= getTimes(time()); ?> by user : <?= $user_id; ?> <nobr>on behalf of KING GARDEN VIEW HOTEL.</nobr></p>
                        </div>
                        <table id="tbl" name="tbl" class="table table-dark table-striped-columns table-hover">

                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center p-3" style="width:100%;">
                        <i class="material-icons" id="back">arrow_back</i>
                        <i class="material-icons" id="fwd">arrow_forward</i>
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