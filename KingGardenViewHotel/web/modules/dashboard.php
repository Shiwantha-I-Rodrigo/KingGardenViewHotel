<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/common.php';
isset($_SESSION['user_id']) ? $user_id = $_SESSION['user_id'] : reDirect("/web/modules/login.php");
authorize($user_id, '1', 'web');
$extra_js = '<script src="' . WEB_BASE_URL . 'js/dashboard.js"></script>';
$extra_css = '';
$db = dbConn();
$sql = "SELECT * FROM customers c INNER JOIN users u ON c.UserId = u.UserId WHERE u.UserId = $user_id";
$result = $db->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['ProfilePic'] != "" ? $profile_pic = $row['ProfilePic'] : $profile_pic = "/img/users/default.png";
        $title = getTitle($row['Title']);
        $name = $title . $row['FirstName'] . " " . $row['LastName'];
        $telephone = $row['Telephone'];
        $mobile = $row['Mobile'];
        $address = $row['AddressLine1'] . ", " . $row['AddressLine2'] . ", " . $row['AddressLine3'];
        $reg_no = $row['RegNo'];
        $status = getStatus($row['UserStatus']);
        $email = $row['Email'];
        $username = $row['UserName'];
    }
}

ob_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    extract($_POST);
    if (isset($ReservationId)) {
        $sql = "DELETE FROM reservations WHERE ReservationId = $ReservationId";
        $result = $db->query($sql);
        if ($result) {
            echo '<div id="cancelled"></div>';
        }
    }

    if (isset($chat)) {
        $name_hash = $username . substr(password_hash($user_id, PASSWORD_BCRYPT),-5,5);
        $sql = "INSERT INTO messages (MessageText, MessageTime, FromId, FromName, ToId, Thread, MessageStatus) VALUES ('$chat'," . time() . ",$user_id, '$name_hash',$id,$user_id,1)";
        $result = $db->query($sql);
        if ($result) {
            echo '<div id="sent"></div>';
        }
    }
}

?>

<section style="background-color:var(--shadow);">
    <div class="container py-5">
        <div class="row mt-5">
            <div class="col-4">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <img src="<?= $profile_pic ?>" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
                        <h2 class="my-1" style="font-size : 4vh;"><?= $username ?></h2>
                        <p class="mb-1">Registration No. : <?= $reg_no ?></p>
                        <p class="mb-4">Account Status : <?= $status ?></p>
                        <div class="d-flex justify-content-around mb-2">
                            <button type="button" class="success-btn px-3 py-2" style="width:8vw;">Edit</button>
                            <button type="button" class="fail-btn px-3 py-2" style="width:8vw;">Logout</button>
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-body" style="min-height: 20vh;">

                        <p class="mb-4"><span class="text-primary font-italic me-1">Recieved</span> Messages</p>

                        <ul class="list-group list-group-flush rounded-3 px-3" id="msg" style="list-style-type:none;">

                            <li>none</li>
                            <li>none</li>
                            <li>none</li>
                            <li>none</li>
                            <li>none</li>

                        </ul>

                    </div>
                    <div class="d-flex justify-content-between align-items-center p-3">
                        <i class="material-icons" id="msg_back">arrow_back</i>
                        <i class="material-icons" id="msg_fwd">arrow_forward</i>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3">
                                <p class="mb-0">Full Name</p>
                            </div>
                            <div class="col-9">
                                <p class="text-muted mb-0"><?= $name ?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <p class="mb-0">Email</p>
                            </div>
                            <div class="col-9">
                                <p class="text-muted mb-0"><?= $email ?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <p class="mb-0">Phone</p>
                            </div>
                            <div class="col-9">
                                <p class="text-muted mb-0"><?= $telephone ?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <p class="mb-0">Mobile</p>
                            </div>
                            <div class="col-9">
                                <p class="text-muted mb-0"><?= $mobile ?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <p class="mb-0">Address</p>
                            </div>
                            <div class="col-9">
                                <p class="text-muted mb-0"><?= $address ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="card mb-4">
                            <div class="card-body" style="min-height: 30vh;">

                                <p class="mb-4"><span class="text-primary font-italic me-1">Past</span> Reservations</p>

                                <ul class="list-group list-group-flush rounded-3 px-3" id="past" style="list-style-type:none;">

                                    <li>none</li>
                                    <li>none</li>
                                    <li>none</li>
                                    <li>none</li>
                                    <li>none</li>

                                </ul>

                            </div>
                            <div class="d-flex justify-content-between align-items-center p-3">
                                <i class="material-icons" id="past_back">arrow_back</i>
                                <i class="material-icons" id="past_fwd">arrow_forward</i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body" style="min-height: 30vh;">

                                <p class="mb-4"><span class="text-primary font-italic me-1">Pending</span> Reservations</p>

                                <ul class="list-group list-group-flush rounded-3 px-3" id="comming" style="list-style-type:none;">

                                    <li>none</li>
                                    <li>none</li>
                                    <li>none</li>
                                    <li>none</li>
                                    <li>none</li>

                                </ul>

                            </div>
                            <div class="d-flex justify-content-between align-items-center p-3">
                                <i class="material-icons" id="comming_back">arrow_back</i>
                                <i class="material-icons" id="comming_fwd">arrow_forward</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="height:10vh;"></div>
</section>

<!-- Message Modal -->
<div class="modal fade" id="Dash_Pop" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" style="background-color:var(--background);">
            <div class="modal-header d-flex justify-content-between">
                <img src="<?= BASE_URL . '/img/common/logo_logo.png' ?>" alt="" style="width: 3vw; height: 5vh; object-fit: cover;">
                <label class="mx-3" style="font-size:3vh;" id="modal-heading"></label>
                <button type="button" class="clear_btn" data-bs-dismiss="modal"><i class="material-icons">cancel</i></button>
            </div>
            <div class="modal-body" style="color:var(--primary_font); text-align: justify; text-justify: inter-word; display:inline-block">
                <ul id="model_list" style="list-style-type : none;"></ul>
            </div>
            <div class="modal-footer" id="modal_foot">
                <button type="button" class="fail-btn px-3" data-bs-dismiss="modal">Close</button>
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
                <p>YOU WON'T BE ABLE TO UNDO THIS ACTION !</p>
                <p>Are you sure you want to cancel the Reservation ?</p>
            </div>
            <div class="modal-footer">
                <form method='post'>
                    <input class='d-none' id='ReservationId' name='ReservationId' />
                    <button class="success-btn px-3" type="submit" formmethod="post">Confirm</button>
                </form>
                <button type="button" class="fail-btn px-3" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php
$page_content = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/web/layout.php';
?>