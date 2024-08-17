<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/common.php';
isset($_SESSION['user_id']) ? $user_id = $_SESSION['user_id'] : $user_id = 0;
// authorize($user_id, '1', 'web');
$extra_js = '<script src="' . WEB_BASE_URL . 'js/reviews.js"></script>';
$extra_css = '';
$db = dbConn();

$url =  basename($_SERVER['REQUEST_URI']);
$url_componenets = parse_url($url);
parse_str($url_componenets['query'], $params);
$reservation_id = $params['id'];
isset($params['add']) ? $add = 1 : $add = 0;

$sql = "SELECT * FROM reservations r JOIN rooms m ON r.RoomId=m.RoomId where r.ReservationId=$reservation_id";
$result = $db->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $room_name = $row['RoomName'];
        $room_id = $row['RoomId'];
        $room_price = $row['RoomPrice'];
        $room_ac = $row['RoomAC'];
        $room_wifi = $row['RoomWIFI'];
        $room_capacity = $row['RoomCapacity'];
        $room_picture = $row['RoomPicture'];
        $room_status = $row['RoomStatus'];
        $guest_id = $row['GuestId'];
    }
}

if ($user_id != 0) {

    $sql = "SELECT * FROM customers c INNER JOIN users u ON c.UserId = u.UserId WHERE u.UserId = $user_id";
    $result = $db->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $first_name = $row['FirstName'];
            $last_name = $row['LastName'];
        }
    }

    $update = false;
    if ($guest_id == $user_id) {
        $sql = "SELECT * FROM reviews WHERE ReservationId=$reservation_id ";
        $result = $db->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $ReviewTitle = $row['ReviewTitle'];
                $ReviewText = $row['ReviewText'];
                $ReviewId = $row['ReviewId'];
                $update = true;
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        extract($_POST);

        if ($del_id != '') {

            $sql = "DELETE FROM reviews WHERE ReviewId = $del_id";
            $db->query($sql);
            $_SESSION['alert_color'] = "var(--primary)";
            $_SESSION['alert_icon'] = "task_alt";
            $_SESSION['alert_title'] = "Success !";
            $_SESSION['alert_msg'] = "your review was removed succesfully";
            reDirect('/web/sub/alert.php');
        } else {
            if ($guest_id == $user_id) {
                

                $full_path = "";
                $update_picture = "";
                if (!empty($_FILES['file_upload']['name'])) {
                    $path =  $_SERVER['DOCUMENT_ROOT'] . '/img/users/';
                    $file = uploadFile($path, $_FILES, "web");
                    $full_path = '/img/users/' . $file;
                    $update_picture = ", ReviewPicture=$full_path ";
                }

                if ($update) {
                    $sql = "UPDATE reviews SET ReviewTitle='$title', ReviewText='$text' $update_picture WHERE ReservationId=$reservation_id";
                    $db->query($sql);
                } else {
                    $full_path == "" ? $full_path = "/img/common/default.png" : null;
                    $sql = "INSERT INTO reviews (ReservationId,ReviewTitle,ReviewText,ReviewStatus,ReviewPicture) values ($reservation_id,'$title','$text',1,'$full_path')";
                    $db->query($sql);
                }

                $_SESSION['alert_color'] = "var(--primary)";
                $_SESSION['alert_icon'] = "task_alt";
                $_SESSION['alert_title'] = "Success !";
                $_SESSION['alert_msg'] = $del_id; //"your review was added succesfully";
                reDirect('/web/sub/alert.php');
            }
        }
    }
}

ob_start();
?>

<div class="d-flex justify-content-around align-items-center text-center p-3 row" style="position:fixed; top:10vh; background-color:var(--secondary); z-index:95; width:100vw; ">
    <div class="col-2">
        <i class="material-icons" id="rev_back">arrow_back</i>
    </div>
    <div class="col-5">
        <h4 style="font-size:3vh; text-transform:uppercase;">REVIEWS FOR <?= $room_name ?></h4>
    </div>
    <div class="col-2">
        <i class="material-icons" id="rev_fwd">arrow_forward</i>
    </div>
</div>

<div class="row" style="height:15vh;"></div>

<div class="py-5" style="min-height:80vh;" name="view_review" id="view_review">

</div>

<div class="row" style="height:20vh;"></div>

<div class="" style="position:fixed; bottom:11vh;" data-id="<?= $user_id ?>" data-room="<?= $room_id ?>" data-tog="<?= $add ?>" name="add_review" id="add_review">
    <div class="row ps-5 py-0 my-0" style="width:100vw;">
        <div class="col-11 m-0 p-0" style="background-color:var(--background);border: 0.5vh solid var(--background);border-radius: 2vh;">
            <div class="row">
                <div class="col-10">
                    <form id="reg_form" enctype="multipart/form-data" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . "?id=$reservation_id"; ?>" method="post" role="form" novalidate>
                        <div class="row mx-5 py-0 my-0">
                            <div class="col-6 d-flex justify-content-start align-items-bottom">
                                <label>Title</label>
                            </div>
                            <div class="col-6 d-flex justify-content-start align-items-bottom">
                                <label>Review Picture</label>
                            </div>
                        </div>
                        <div class="row mx-5 py-0 my-0">
                            <div class="col-6 d-flex justify-content-end align-items-center">
                                <input type="text" name="title" id="title" value="<?= $ReviewTitle ?>" placeholder="Title" />
                            </div>
                            <div class="col-6 d-flex justify-content-end align-items-center">
                                <input type="file" id="file_upload" name="file_upload" accept="image/*" />
                            </div>
                        </div>
                        <div class="row mx-5 py-0 my-0">
                            <div class="col-6 d-flex justify-content-start align-items-bottom">
                                <label>Review</label>
                            </div>
                        </div>
                        <div class="row mx-5 py-0 my-0">
                            <div class="col-12 d-flex justify-content-end align-items-center px-2">
                                <input type="text" name="text" id="text" value="<?= $ReviewText ?>" placeholder="Review" />
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-2 ">
                    <button class="success-btn px-4 my-4" name="submit_btn" id="submit_btn">Add / Update Review</button>
                    <button class="fail-btn px-5" name="delete_btn" id="delete_btn">Delete Review</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Confirm" tabindex="-1" aria-labelledby="Confirm" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color:var(--background);">
            <div class="modal-header d-flex justify-content-between">
                <img src="<?= BASE_URL . '/img/common/logo_logo.png' ?>" alt="" style="width: 3vw; height: 5vh; object-fit: cover;">
                <label class="mx-3" style="font-size:3vh;">Confirmation</label>
                <button type="button" class="clear_btn" data-bs-dismiss="modal"><i class="material-icons">cancel</i></button>
            </div>
            <div class="modal-body" style="font-weight: normal; color:var(--primary_font); text-align: justify; text-justify: inter-word;">
                <p>Are you sure you want to submit the review ?</p>
            </div>
            <div class="modal-footer">
                <button class="success-btn px-3" type="submit" form="reg_form" formmethod="post">Confirm</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Delete" tabindex="-1" aria-labelledby="Confirm" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color:var(--background);">
            <div class="modal-header d-flex justify-content-between">
                <img src="<?= BASE_URL . '/img/common/logo_logo.png' ?>" alt="" style="width: 3vw; height: 5vh; object-fit: cover;">
                <label class="mx-3" style="font-size:3vh;">Confirmation</label>
                <button type="button" class="clear_btn" data-bs-dismiss="modal"><i class="material-icons">cancel</i></button>
            </div>
            <div class="modal-body" style="font-weight: normal; color:var(--primary_font); text-align: justify; text-justify: inter-word;">
                <p>Are you sure you want to delete the review ?</p>
            </div>
            <div class="modal-footer">
                <form id="del_form" enctype="multipart/form-data" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . "?id=$reservation_id"; ?>" method="post" role="form" novalidate>
                    <input id='del_id' name='del_id' value='<?= $ReviewId ?>' class="d-none"></input>
                    <button class="success-btn px-3" type="submit" form="del_form" formmethod="post">Confirm</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Reservation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background-color:var(--background);">
            <div class="modal-header d-flex justify-content-between">
                <img src="<?= BASE_URL . '/img/common/logo_logo.png' ?>" alt="" style="width: 3vw; height: 5vh; object-fit: cover;">
                <button type="button" class="clear_btn" data-bs-dismiss="modal"><i class="material-icons">cancel</i></button>
            </div>
            <div class="modal-body" style="color:var(--primary_font); text-align: justify; text-justify: inter-word; display:inline-block; height:50vh;">
                <div id="room-details" style="height:100%; width:100%;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="fail-btn px-3" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<?php
$page_content = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/web/layout.php';
?>