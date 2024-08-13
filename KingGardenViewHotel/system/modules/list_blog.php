<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/common.php';
isset($_SESSION['user_id']) ? $user_id = $_SESSION['user_id'] : reDirect("/system/modules/login.php");
authorize($user_id, '1', 'web');
$extra_js = '<script src="' . SYSTEM_BASE_URL . 'js/list_blogs.js"></script>';
$extra_css = '';
$db = dbConn();
$sql = "SELECT * FROM employees c INNER JOIN users u ON c.UserId = u.UserId WHERE u.UserId = $user_id";
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
    if (isset($id)) {
        $sql = "DELETE FROM blogs WHERE BlogId = $id";
        $result = $db->query($sql);
        if ($result) {
            echo '<div id="removed"></div>';
        }
    }
}

$update = explode("_", $reg_no);

ob_start();
?>

<section style="background-color:var(--shadow);">
    <div class="row" style="height:10vh;"></div>
    <div class="row mx-5">
        <div class="col-3">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <img src="<?= $profile_pic ?>" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
                    <h2 class="my-1" style="font-size : 4vh;"><?= $username ?></h2>
                    <p class="mb-1">Last Update. : <?= date("Y-M-d H:i:s A", $update[0]) . "<br/>By : " . $update[1] . " ( User Id )" ?></p>
                    <p class="mb-4">Account Status : <?= $status ?></p>
                    <div class="d-flex justify-content-around mb-2">
                        <a href="edit_user.php"><button type="button" class="success-btn px-3 py-2" style="width:8vw;">Edit</button></a>
                        <a href="../sub/logout.php"><button type="button" class="fail-btn px-3 py-2" style="width:8vw;">Logout</button></a>
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-body" style="min-height: 20vh;">

                    <p class="mb-4"><span class="text-primary font-italic me-1">TABLE</span> FILTERS</p>

                    <div class="row my-3 border border-2 border-white rounded-3 mb-3 p-2">
                        <div class="col-3 d-flex justify-content-end">
                            <label>Search :</label>
                        </div>
                        <div class="col-8 d-flex justify-content-end">
                            <input name="search" id="search">
                        </div>
                    </div>

                    <div class="border border-2 border-white rounded-3 mb-3 p-2">
                        <div class="row mb-3">
                            <div class="col-3 d-flex justify-content-end">
                                <label for="sort">Sort By : </label>
                            </div>
                            <div class="col-5 d-flex justify-content-center">
                                <select name="sort" id="sort">
                                    <option value="i.BlogId">Blog Id</option>
                                    <option value="i.BlogText">Blog Text</option>
                                    <option value="i.BlogTitle">Blog Title</option>
                                    <option value="i.BlogStatus">Blog Status</option>
                                </select>
                            </div>
                            <div class="col-3 d-flex justify-content-center">
                                <select name="order" id="order">
                                    <option value="ASC">Acending</option>
                                    <option value="DESC">Decending</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="border border-2 border-white rounded-3 mb-3 p-2">
                        <div class="row mb-2">
                            <div class="col-3 d-flex justify-content-center">
                                <label>Range :</label>
                            </div>
                            <div class="col-5 d-flex justify-content-center">
                                <select name="range" id="range">
                                    <option value="i.BlogId">Blog Id</option>
                                </select>
                            </div>
                        </div>

                        <div class="row" id="number_range">
                            <div class="col-3 d-flex justify-content-end">
                                <label>Min : </label>
                            </div>
                            <div class="col-3 d-flex justify-content-end">
                                <input name="min" id="min">
                            </div>
                            <div class="col-3 d-flex justify-content-end">
                                <label>Max : </label>
                            </div>
                            <div class="col-3 d-flex justify-content-end">
                                <input name="max" id="max">
                            </div>
                        </div>

                        <div class="d-none" id="date_range">
                            <div class="row">
                                <div class="col-5 p-0 m-0 d-flex justify-content-center">
                                    <label>Start date</label>
                                </div>
                                <div class="col-5 p-0 m-0 ms-5 d-flex justify-content-center">
                                    <label>End date</label>
                                </div>
                            </div>
                            <div class="row d-flex">
                                <div class="col-6 p-0 m-0">
                                    <input name="start_date" id="start_date" class="form-control datepickers" type="date" required />
                                    <input name="s_date" id="s_date" class="d-none" type="number" required />
                                </div>
                                <div class="col-6 p-0 m-0">
                                    <input name="end_date" id="end_date" class="form-control datepickers" type="date" required />
                                    <input name="e_date" id="e_date" class="d-none" type="number" required />
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <button class="success-btn px-3 py-2" name="filter_btn" id="filter_btn"><i class="material-icons">filter_alt</i> Apply Filters</button>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-7">

            <div class="card mb-4">
                <div class="card-body">

                    <div class="my-4 text-center"><label class="my-1" style="font-size : 2vh;">CATELOGUE</label></div>

                    <table id="tbl" name="tbl" class="table table-dark table-striped-columns table-hover">

                    </table>

                    <div class="d-flex justify-content-between align-items-center p-3" style="width:100%;">
                        <i class="material-icons" id="back">arrow_back</i>
                        <i class="material-icons" id="fwd">arrow_forward</i>
                    </div>

                </div>
            </div>

        </div>
        <div class="col-2">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="my-4 text-center"><label class="my-1" style="font-size : 2vh;">MODULES</label></div>
                    <div class="my-3"><a href="/system/index.php"><label class="my-1" style="font-size : 2vh;"><i class="material-icons mx-3">home</i>Home</label></a></div>
                    <div class="my-3"><a href="/system/modules/list_customers.php"><label class="my-1" style="font-size : 2vh;"><i class="material-icons mx-3">portrait</i>Customers</label></a></div>
                    <div class="my-3"><a href="/system/modules/list_employees.php"><label class="my-1" style="font-size : 2vh;"><i class="material-icons mx-3">badge</i>Employees</label></a></div>
                    <div class="my-3"><a href="/system/modules/list_rooms.php"><label class="my-1" style="font-size : 2vh;"><i class="material-icons mx-3">apartment</i>Rooms</label></a></div>
                    <div class="my-3"><a href="/system/modules/list_destinations.php"><label class="my-1" style="font-size : 2vh;"><i class="material-icons mx-3">terrain</i>Destinations</label></a></div>
                    <div class="my-3"><a href="/system/modules/list_reservations.php"><label class="my-1" style="font-size : 2vh;"><i class="material-icons mx-3">book</i>Reservations</label></a></div>
                    <div class="my-3"><a href="/system/modules/list_invoices.php"><label class="my-1" style="font-size : 2vh;"><i class="material-icons mx-3">request_quote</i>Invoice</label></a></div>
                    <div class="my-3"><a href="/system/modules/list_blog.php"><label class="my-1" style="font-size : 2vh;"><i class="material-icons mx-3">edit_note</i>Catelogue</label></a></div>
                    <div class="my-3"><a href="/system/modules/list_reviews.php"><label class="my-1" style="font-size : 2vh;"><i class="material-icons mx-3">star_half</i>Reviews</label></a></div>
                    <div class="my-3"><a href="/system/modules/list_reports.php"><label class="my-1" style="font-size : 2vh;"><i class="material-icons mx-3">trending_up</i>Reports</label></a></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="height:10vh;"></div>
</section>

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
                <p>Are you sure you want to remove ?</p>
            </div>
            <div class="modal-footer">
                <form method='post'>
                    <input class='d-none' id='id' name='id' />
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