<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/common.php';
isset($_SESSION['user_id']) ? $user_id = $_SESSION['user_id'] : reDirect("/system/modules/login.php");
authorize($user_id, '10', 'system');
$extra_js = '<script src="' . SYSTEM_BASE_URL . 'js/edit_reviews.js"></script>';
$extra_css = '';

$db = dbConn();

require_once $_SERVER['DOCUMENT_ROOT'] . '/system/sub/user_info.php';

$url =  basename($_SERVER['REQUEST_URI']);
$url_componenets = parse_url($url);
parse_str($url_componenets['query'], $params);
isset($params['id']) ? $review_id = $params['id'] :  $review_id = 0;

if ($review_id != 0) {
    $sql = "SELECT * FROM reviews WHERE ReviewId = $review_id";
    $result = $db->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $reservation_id = $row['ReservationId'];
            $review_title = $row['ReviewTitle'];
            $review_text = $row['ReviewText'];
            $review_status = $row['ReviewStatus'];
            $review_picture = $row['ReviewPicture'];
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    extract($_POST);

    $upload = "";
    if (!empty($_FILES['file_upload']['name'])) {
        $path =  $_SERVER['DOCUMENT_ROOT'] . '/img/galleries/';
        $file = uploadFile($path, $_FILES, "system");
        $full_path = '/img/galleries/' . $file;
        $upload = ",`ReviewPicture`='$full_path'";
    }

    if ($review_id != 0) {
        $sql = "UPDATE reviews SET `ReservationId`='$reservation_id', `ReviewTitle`='$review_title',`ReviewText`='$review_text', `ReviewStatus`='$review_status' $upload WHERE ReviewId=$review_id";
        $db->query($sql);
    } else {
        $sql = "INSERT INTO reviews (ReservationId, ReviewTitle, ReviewText, ReviewStatus, ReviewPicture) VALUES ($reservation_id, $review_title, $review_text, $review_status, $full_path)";
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
                    <div class="my-4 text-center"><label class="my-1" style="font-size : 2vh;">REVIEW NO. <?= $review_id ?></label></div>

                    <form id="reg_form" enctype="multipart/form-data" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $review_id; ?>" method="post" role="form" novalidate>

                        <div class="row mx-5">
                            <div class="col-6 d-flex justify-content-start align-items-bottom">
                                <label>Reservation Id</label>
                            </div>
                            <div class="col-6 d-flex justify-content-start align-items-bottom">
                                <label>Review Status</label>
                            </div>
                        </div>
                        <div class="row mx-5">
                            <div class="col-6 d-flex justify-content-end align-items-center">
                                <input type="text" name="reservation_id" id="reservation_id" value="<?= $reservation_id ?>" placeholder="Reservation Id" required />
                            </div>
                            <div class="col-6 d-flex justify-content-start align-items-center">
                                <select class="w-50" name="review_status" id="review_status">
                                    <option <?php echo ($review_status == 0) ? 'selected' : ''; ?> value="0">Inactive</option>
                                    <option <?php echo ($review_status == 1) ? 'selected' : ''; ?> value="1">Active</option>
                                    <option <?php echo ($review_status == 2) ? 'selected' : ''; ?> value="2">Unavailable</option>
                                    <option <?php echo ($review_status == 3) ? 'selected' : ''; ?> value="3">Unauthorized</option>
                                    <option <?php echo ($review_status == 4) ? 'selected' : ''; ?> value="4">Invalid</option>
                                    <option <?php echo ($review_status == 5) ? 'selected' : ''; ?> value="5">Reserved</option>
                                    <option <?php echo ($review_status == 6) ? 'selected' : ''; ?> value="6">Discounted</option>
                                    <option <?php echo ($review_status == 9) ? 'selected' : ''; ?> value="6">Forbidden</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mx-5 mt-3">
                            <div class="col-6 d-flex justify-content-start align-items-bottom">
                                <label>Review Title</label>
                            </div>
                        </div>
                        <div class="row mx-5">
                            <div class="col-6 d-flex justify-content-end align-items-center">
                                <input type="text" name="review_title" id="review_title" value="<?= $review_title ?>" placeholder="Review Title" required />
                            </div>
                        </div>

                        <div class="row mx-5 px-3 mt-3">
                            <div class="col-12 d-flex justify-content-start align-items-bottom">
                                <label>Review Text</label>
                            </div>
                        </div>
                        <div class="row mx-5 px-3 mb-5">
                            <div class="col-12 d-flex justify-content-end align-items-center">
                                <input type="text" name="review_text" id="review_text" value="<?= $review_text ?>" placeholder="Review Text" required />
                            </div>
                        </div>

                        <div class="row mx-5 px-3 mt-3">
                            <div class="col-6 d-flex justify-content-start align-items-bottom">
                                <label>Review Picture</label>
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
                            <img class="m-0 p-0" src="<?= $review_picture ?>" alt="" style="width: 100%; object-fit: cover; border-radius: 1vh 1vh 1vh 1vh;">
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