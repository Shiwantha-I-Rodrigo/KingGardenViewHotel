<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/common.php';
isset($_SESSION['user_id']) ? $user_id = $_SESSION['user_id'] : reDirect("/system/modules/login.php");
authorize($user_id, '8', 'system');
$extra_js = '<script src="' . SYSTEM_BASE_URL . 'js/edit_invoices.js"></script>';
$extra_css = '';

$db = dbConn();

require_once $_SERVER['DOCUMENT_ROOT'] . '/system/sub/user_info.php';

$url =  basename($_SERVER['REQUEST_URI']);
$url_componenets = parse_url($url);
parse_str($url_componenets['query'], $params);
isset($params['id']) ? $invoice_id = $params['id'] :  $invoice_id = 0;

if ($invoice_id != 0) {
    $sql = "SELECT * FROM items WHERE ItemId = $invoice_id";
    $result = $db->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $reservation_id = $row['ReservationId'];
            $item_status = $row['ItemStatus'];
            $item_name = $row['ItemName'];
            $item_discount = $row['ItemDiscount'];
            $item_price = $row['ItemPrice'];
            $item_paid = $row['ItemPaid'];
            $item_comments = $row['ItemComments'];
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    extract($_POST);

    if ($invoice_id != 0) {
        $sql = "UPDATE items SET `ReservationId`='$reservation_id', `ItemStatus`='$item_status',`ItemName`='$item_name', `ItemDiscount`='$item_discount',`ItemPrice`='$item_price', `ItemPaid`='$item_paid',`ItemComments`='$item_comments'  WHERE ItemId=$invoice_id";
        $db->query($sql);
    } else {
        $sql = "INSERT INTO items ( ReservationId ,ItemName ,ItemPrice ,ItemPaid ,ItemStatus ,ItemDiscount ,ItemComments ,Status ) VALUES ($reservation_id, $item_price, $item_paid, $item_status, $item_discount, '$item_comments' )";
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
                    <div class="my-4 text-center"><label class="my-1" style="font-size : 2vh;">INVOICE NO. <?= $invoice_id ?></label></div>

                    <form id="reg_form" enctype="multipart/form-data" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $invoice_id; ?>" method="post" role="form" novalidate>

                        <div class="row mx-5">
                            <div class="col-6 d-flex justify-content-start align-items-bottom">
                                <label>Reservation Id</label>
                            </div>
                            <div class="col-6 d-flex justify-content-start align-items-bottom">
                                <label>Item Status</label>
                            </div>
                        </div>
                        <div class="row mx-5">
                            <div class="col-6 d-flex justify-content-end align-items-center">
                                <input type="text" name="reservation_id" id="reservation_id" value="<?= $reservation_id ?>" placeholder="Reservation Id" required />
                            </div>
                            <div class="col-6 d-flex justify-content-start align-items-center">
                                <select class="w-50" name="item_status" id="item_status">
                                    <option <?php echo ($item_status == 0) ? 'selected' : ''; ?> value="0">Unpaid</option>
                                    <option <?php echo ($item_status == 1) ? 'selected' : ''; ?> value="1">Paid</option>
                                    <option <?php echo ($item_status == 2) ? 'selected' : ''; ?> value="2">Pending</option>
                                    <option <?php echo ($item_status == 3) ? 'selected' : ''; ?> value="3">Rejected</option>
                                    <option <?php echo ($item_status == 4) ? 'selected' : ''; ?> value="4">Refunded</option>
                                    <option <?php echo ($item_status == 5) ? 'selected' : ''; ?> value="5">Cancelled</option>
                                    <option <?php echo ($item_status == 6) ? 'selected' : ''; ?> value="6">Partial</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mx-5 mt-3">
                            <div class="col-6 d-flex justify-content-start align-items-bottom">
                                <label>Item Name</label>
                            </div>
                            <div class="col-6 d-flex justify-content-start align-items-bottom">
                                <label>Item Discount</label>
                            </div>
                        </div>
                        <div class="row mx-5">
                            <div class="col-6 d-flex justify-content-end align-items-center">
                                <input type="text" name="item_name" id="item_name" value="<?= $item_name ?>" placeholder="Item Name" required />
                            </div>
                            <div class="col-6 d-flex justify-content-end align-items-center">
                                <input type="text" name="item_discount" id="item_discount" value="<?= $item_discount ?>" placeholder="Item Discount" required />
                            </div>
                        </div>
                        <div class="row mx-5 mt-3">
                            <div class="col-6 d-flex justify-content-start align-items-bottom">
                                <label>Item Price</label>
                            </div>
                            <div class="col-6 d-flex justify-content-start align-items-bottom">
                                <label>Item Paid</label>
                            </div>
                        </div>
                        <div class="row mx-5">
                            <div class="col-6 d-flex justify-content-end align-items-center">
                                <input type="text" name="item_price" id="item_price" value="<?= $item_price ?>" placeholder="Item Price" required />
                            </div>
                            <div class="col-6 d-flex justify-content-end align-items-center">
                                <input type="text" name="item_paid" id="item_paid" value="<?= $item_paid ?>" placeholder="Item Paid" required />
                            </div>
                        </div>
                        <div class="row mx-5 px-3 mt-3">
                            <div class="col-12 d-flex justify-content-start align-items-bottom">
                                <label>Item Comments</label>
                            </div>
                        </div>
                        <div class="row mx-5 px-3 mb-5">
                            <div class="col-12 d-flex justify-content-end align-items-center">
                                <input type="text" name="item_comments" id="item_comments" value="<?= $item_comments ?>" placeholder="Item Comments" required />
                            </div>
                        </div>

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