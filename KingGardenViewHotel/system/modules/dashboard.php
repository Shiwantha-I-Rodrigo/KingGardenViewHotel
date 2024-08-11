<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/common.php';
isset($_SESSION['user_id']) ? $user_id = $_SESSION['user_id'] : reDirect("/system/modules/login.php");
authorize($user_id, '1', 'web');
$extra_js = '<script src="' . SYSTEM_BASE_URL . 'js/dashboard.js"></script>';
$extra_css = '';
$db = dbConn();
$sql = "SELECT * FROM customers c INNER JOIN users u ON c.UserId = u.UserId WHERE u.UserId = $user_id";
$result = $db->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['ProfilePic'] != "" ? $profile_pic = $row['ProfilePic'] : $profile_pic = "/img/users/default.png";
        $title = getTitle($row['Title']);
        $name = $title . $row['FirstName'] . " " . $row['LastName'];
        $name2 = $row['FirstName'] . " " . $row['LastName'];
        $telephone = $row['Telephone'];
        $mobile = $row['Mobile'];
        $address = $row['AddressLine1'] . ", " . $row['AddressLine2'] . ", " . $row['AddressLine3'];
        $reg_no = $row['RegNo'];
        $status = getStatus($row['UserStatus']);
        $email = $row['Email'];
        $username = $row['UserName'];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    extract($_POST);
    if (isset($ReservationId)) {
        $sql = "DELETE FROM reservations WHERE ReservationId = $ReservationId";
        $result = $db->query($sql);
        if ($result) {
            echo '<div id="cancelled"></div>';

            $msg = "Dear " . $name2 . ",<br/>" .
                "I hope this email finds you well. We would like to extend our sincerest appreciation for choosing King Garden View Hotel for your stay.<br/>" .
                "The Reservation [ id : " . $ReservationId . "] is cancelled according to your request. If there is anything else we can assist you with or if you have any alternative requests, please do not hesitate to reach out to our front desk staff.<br/>" .
                "Warm regards,<br/>Managing Director,<br/>King garden View Hotel";

            sendEmail($email, $name, "Your Reservation Is Cancelled!", $msg);
        }
    }

    if (isset($chat)) {
        $sql = "INSERT INTO messages (MessageText, MessageTime, FromId, FromName, ToId, Thread, MessageStatus) VALUES ('$chat'," . time() . ",$user_id, '$name2',$id,$user_id,1)";
        $result = $db->query($sql);
        if ($result) {
            echo '<div id="sent"></div>';
        }
    }

    if (isset($new_chat_id)) {
        $sql = "SELECT * FROM users WHERE UserName='$new_chat_id' OR Email='$new_chat_id'";
        $result = $db->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $to_id = $row['UserId'];
            $req = "INSERT INTO messages (MessageText, MessageTime, FromId, FromName, ToId, Thread, MessageStatus) VALUES ('$new_chat_text'," . time() . ",$user_id, '$name2',$to_id,$user_id,1)";
            $res = $db->query($req);
            if ($result) {
                echo '<div id="sent"></div>';
            }
        } else {
            echo '<div id="not_sent"></div>';
        }
    }
}

$update = explode("_",$reg_no);

ob_start();
?>

<section style="background-color:var(--shadow);">
    <div class="row" style="height:10vh;"></div>
    <div class="row my-5 mx-5">
        <div class="col-3">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <img src="<?= $profile_pic ?>" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
                    <h2 class="my-1" style="font-size : 4vh;"><?= $username ?></h2>
                    <p class="mb-1">Last Update. : <?= date("Y-M-d H:i:s A",$update[0]) . "<br/>By : " . $update[1] . " ( User Id )" ?></p>
                    <p class="mb-4">Account Status : <?= $status ?></p>
                    <div class="d-flex justify-content-around mb-2">
                        <a href="user.php"><button type="button" class="success-btn px-3 py-2" style="width:8vw;">Edit</button></a>
                        <a href="../sub/logout.php"><button type="button" class="fail-btn px-3 py-2" style="width:8vw;">Logout</button></a>
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-body" style="min-height: 20vh;">

                    <p class="mb-4"><span class="text-primary font-italic me-1">Recieved</span> Messages</p>

                    <button class="success-btn px-3 py-2 mb-4" name="new_chat_btn" id="new_chat_btn"><i class="material-icons">add</i> New Chat</button>

                    <ul class="list-group list-group-flush rounded-3 px-3" id="msg" style="list-style-type:none;">

                        <li>none</li>

                    </ul>

                </div>
                <div class="d-flex justify-content-between align-items-center p-3">
                    <i class="material-icons" id="msg_back">arrow_back</i>
                    <i class="material-icons" id="msg_fwd">arrow_forward</i>
                </div>
            </div>
        </div>
        <div class="col-7">
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
        <div class="col-2">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="my-4 text-center"><label class="my-1" style="font-size : 2vh;">Modules</label></div>
                    <div class="my-3"><a href="/system/index.php"><label class="my-1" style="font-size : 2vh;"><i class="material-icons mx-3">home</i>Home</label></a></div>
                    <div class="my-3"><a href="/system/modules/list_customers.php"><label class="my-1" style="font-size : 2vh;"><i class="material-icons mx-3">portrait</i>Customers</label></a></div>
                    <div class="my-3"><a href="/system/modules/list_employees.php"><label class="my-1" style="font-size : 2vh;"><i class="material-icons mx-3">badge</i>Employees</label></a></div>
                    <div class="my-3"><a href="/system/modules/list_rooms.php"><label class="my-1" style="font-size : 2vh;"><i class="material-icons mx-3">apartment</i>Rooms</label></a></div>
                    <div class="my-3"><a href="/system/modules/list_destinations.php"><label class="my-1" style="font-size : 2vh;"><i class="material-icons mx-3">terrain</i>Destinations</label></a></div>
                    <div class="my-3"><a href="/system/modules/list_reservations.php"><label class="my-1" style="font-size : 2vh;"><i class="material-icons mx-3">book</i>Reservations</label></a></div>
                    <div class="my-3"><a href="/system/modules/list_invoices.php"><label class="my-1" style="font-size : 2vh;"><i class="material-icons mx-3">request_quote</i>Invoice</label></a></div>
                    <div class="my-3"><a href="/system/modules/list_blog.php"><label class="my-1" style="font-size : 2vh;"><i class="material-icons mx-3">edit_note</i>Blog</label></a></div>
                    <div class="my-3"><a href="/system/modules/list_reviews.php"><label class="my-1" style="font-size : 2vh;"><i class="material-icons mx-3">star_half</i>Reviews</label></a></div>
                    <div class="my-3"><a href="/system/modules/list_reports.php"><label class="my-1" style="font-size : 2vh;"><i class="material-icons mx-3">trending_up</i>Reports</label></a></div>
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
require_once $_SERVER['DOCUMENT_ROOT'] . '/system/layout.php';
?>