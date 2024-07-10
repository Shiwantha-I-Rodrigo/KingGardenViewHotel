<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/common.php';
ob_start();

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
        $status = getStatus($row['Status']);
        $email = $row['Email'];
        $username = $row['UserName'];
    }
}

?>

<section>
    <div class="container py-5">
        <div class="row mt-5">
            <div class="col-4">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <img src="<?= $profile_pic ?>" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
                        <h5 class="my-3"><?= $username ?></h5>
                        <p class="text-muted mb-1"><?= $reg_no ?></p>
                        <p class="text-muted mb-4"><?= $status ?></p>
                        <div class="d-flex justify-content-center mb-2">
                            <button type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary">Edit</button>
                            <button type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-primary ms-1">Deactivate</button>
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <ul class="list-group list-group-flush rounded-3">

                        <?php
                        $sql = "SELECT * FROM  messages WHERE ToId = $user_id ";
                        $messages = $db->query($sql);
                        while ($row = $messages->fetch_assoc()) {
                        ?>

                            <li class="list-group-item d-flex justify-content-between align-items-center p-3" style="background-color: var(--primary);">
                                <label style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= $row['MessageText'] ?></label>
                            </li>

                        <?php } ?>

                    </ul>
                    <div class="d-flex justify-content-between align-items-center p-3">
                        <i class="material-icons">arrow_back</i>
                        <i class="material-icons">arrow_forward</i>
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
                            <div class="card-body">

                                <p class="mb-4"><span class="text-primary font-italic me-1">Past</span> Reservations</p>

                                <?php
                                $current = time();
                                $sql = "SELECT * FROM  reservations WHERE GuestId = $user_id AND TimeSlotEnd < $current";
                                $reservations = $db->query($sql);
                                while ($row = $reservations->fetch_assoc()) {
                                ?>

                                    <p class="mb-1" style="font-size: .77rem;">Reservation : <?= $row['ReservationId']  ?><br>From : <?= getTime($row['TimeSlotStart']) ?><br>To : <?= getTime($row['TimeSlotEnd']) ?></p>

                                <?php } ?>

                            </div>
                            <div class="d-flex justify-content-between align-items-center p-3">
                                <i class="material-icons">arrow_back</i>
                                <i class="material-icons">arrow_forward</i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">

                                <p class="mb-4"><span class="text-primary font-italic me-1">Upcomming</span> Reservations</p>

                                <?php
                                $current = time();
                                $sql = "SELECT * FROM  reservations WHERE GuestId = $user_id AND TimeSlotStart > $current";
                                $reservations = $db->query($sql);
                                while ($row = $reservations->fetch_assoc()) {
                                ?>

                                    <p class="mb-1" style="font-size: .77rem;">Reservation : <?= $row['ReservationId']  ?><br>From : <?= getTime($row['TimeSlotStart']) ?><br>To : <?= getTime($row['TimeSlotEnd']) ?></p>

                                <?php } ?>

                            </div>
                            <div class="d-flex justify-content-between align-items-center p-3">
                                <i class="material-icons">arrow_back</i>
                                <i class="material-icons">arrow_forward</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$page_content = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/web/layout.php';
?>