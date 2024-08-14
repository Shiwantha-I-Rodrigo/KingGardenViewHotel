<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/common.php';
isset($_SESSION['user_id']) ? $user_id = $_SESSION['user_id'] : reDirect("/system/modules/login.php");
authorize($user_id, '1', 'web');
$extra_js = '<script src="' . SYSTEM_BASE_URL . 'js/edit_blogs.js"></script>';
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

$update = explode("_", $reg_no);

$url =  basename($_SERVER['REQUEST_URI']);
$url_componenets = parse_url($url);
parse_str($url_componenets['query'], $params);
$blog_id = $params['id'];

$sql = "SELECT * FROM blogs WHERE BlogId = $blog_id";
$result = $db->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $blog_text = $row['BlogText'];
        $blog_title = $row['BlogTitle'];
        $blog_status = $row['BlogStatus'];
        $blog_picture1 = $row['BlogPicture1'];
        $blog_picture2 = $row['BlogPicture2'];
        $blog_picture3 = $row['BlogPicture3'];
        $blog_picture4 = $row['BlogPicture4'];
        $blog_picture5 = $row['BlogPicture5'];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    extract($_POST);

    $upload = "";
    $i = 0;
    foreach ($_FILES['file_upload']['name'] as $file_name) {
        if (!empty($_FILES['file_upload']['name'][$i])) {
            $path =  $_SERVER['DOCUMENT_ROOT'] . '/img/blogs/';
            $file = uploadFiles($path, $_FILES, "system", $i);
            $i++;
            $full_path = '/img/blogs/' . $file;
            $upload .= ",`BlogPicture$i`='$full_path'";
        }
    }

    $sql = "UPDATE blogs SET `BlogText`='$blog_text',`BlogTitle`='$blog_title', `BlogStatus`='$blog_status' $upload WHERE BlogId=$blog_id";
    $db->query($sql);

    $_SESSION['alert_color'] = "var(--primary)";
    $_SESSION['alert_icon'] = "task_alt";
    $_SESSION['alert_title'] = "Success !";
    $_SESSION['alert_msg'] = $upload; //"The information was updated succesfully";
    reDirect('/system/sub/alert.php');
}

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

                    <p class="mb-4"><span class="text-primary font-italic me-1">EDIT</span> TOOLS</p>

                    <div class="row">
                        <button class="success-btn px-3 py-2 mb-4" name="reset_btn" id="reset_btn"><i class="material-icons">restore</i> Reset data</button>
                    </div>

                    <div class="row">
                        <button class="success-btn px-3 py-2 mb-4" name="clear_btn" id="clear_btn"><i class="material-icons">backspace</i> Clear Data</button>
                    </div>

                    <div class="row">
                        <button class="success-btn px-3 py-2 mb-4" name="save_btn" id="save_btn" data-bs-toggle="modal" data-bs-target="#Confirm"><i class="material-icons">save</i> Save data</button>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-7">

            <div class="card mb-4">
                <div class="card-body">

                    <div class="my-4 text-center"><label class="my-1" style="font-size : 2vh;">CATELOGUE <?= $blog_id ?></label></div>

                    <form id="reg_form" enctype="multipart/form-data" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $blog_id; ?>" method="post" role="form" novalidate>

                        <div class="row mx-5">
                            <div class="col-6 d-flex justify-content-start align-items-bottom">
                                <label>Catelogue Title</label>
                            </div>
                            <div class="col-6 d-flex justify-content-start align-items-bottom">
                                <label>Catelogue Status</label>
                            </div>
                        </div>

                        <div class="row mx-5">
                            <div class="col-6 d-flex justify-content-end align-items-center">
                                <input type="text" name="blog_title" id="blog_title" value="<?= $blog_title ?>" placeholder="Blog Title" required />
                            </div>
                            <div class="col-6 d-flex justify-content-start align-items-center">
                                <select class="w-50" name="blog_status" id="blog_status">
                                    <option <?php echo ($blog_status == 0) ? 'selected' : ''; ?> value="0">Inactive</option>
                                    <option <?php echo ($blog_status == 1) ? 'selected' : ''; ?> value="1">Active</option>
                                    <option <?php echo ($blog_status == 2) ? 'selected' : ''; ?> value="2">Unavailable</option>
                                    <option <?php echo ($blog_status == 3) ? 'selected' : ''; ?> value="3">Unauthorized</option>
                                    <option <?php echo ($blog_status == 4) ? 'selected' : ''; ?> value="4">Invalid</option>
                                    <option <?php echo ($blog_status == 5) ? 'selected' : ''; ?> value="5">Reserved</option>
                                    <option <?php echo ($blog_status == 6) ? 'selected' : ''; ?> value="6">Discounted</option>
                                    <option <?php echo ($blog_status == 9) ? 'selected' : ''; ?> value="6">Forbidden</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mx-5 px-3 mt-3">
                            <div class="col-12 d-flex justify-content-start align-items-bottom">
                                <label>Catelogue Text</label>
                            </div>
                        </div>
                        <div class="row mx-5 px-3 mb-5">
                            <div class="col-12 d-flex justify-content-end align-items-center">
                                <input type="text" name="blog_text" id="blog_text" value="<?= $blog_text ?>" placeholder="Blog Text" required />
                            </div>
                        </div>

                        <div class="row mx-5 mt-3">
                            <div class="col-6 d-flex justify-content-start align-items-bottom">
                                <label>Catelogue Pictures (please select five pictures or less !)</label>
                            </div>
                        </div>
                        <div class="row mx-5 mt-2">
                            <div class="col-6 d-flex justify-content-end align-items-center">
                                <input type="file" id="file_upload[]" name="file_upload[]" multiple accept="image/*" />
                            </div>
                        </div>

                    </form>

                    <div class="row mx-5 my-5">
                        <div class="col-12 d-flex justify-content-start">
                            <img class="m-0 p-0" src="<?= $blog_picture1 ?>" alt="" style="width: 100%; object-fit: cover; border-radius: 1vh 1vh 1vh 1vh;">
                        </div>
                    </div>
                    <div class="row mx-5 my-5">
                        <div class="col-12 d-flex justify-content-start">
                            <img class="m-0 p-0" src="<?= $blog_picture2 ?>" alt="" style="width: 100%; object-fit: cover; border-radius: 1vh 1vh 1vh 1vh;">
                        </div>
                    </div>
                    <div class="row mx-5 my-5">
                        <div class="col-12 d-flex justify-content-start">
                            <img class="m-0 p-0" src="<?= $blog_picture3 ?>" alt="" style="width: 100%; object-fit: cover; border-radius: 1vh 1vh 1vh 1vh;">
                        </div>
                    </div>
                    <div class="row mx-5 my-5">
                        <div class="col-12 d-flex justify-content-start">
                            <img class="m-0 p-0" src="<?= $blog_picture4 ?>" alt="" style="width: 100%; object-fit: cover; border-radius: 1vh 1vh 1vh 1vh;">
                        </div>
                    </div>
                    <div class="row mx-5 my-5">
                        <div class="col-12 d-flex justify-content-start">
                            <img class="m-0 p-0" src="<?= $blog_picture5 ?>" alt="" style="width: 100%; object-fit: cover; border-radius: 1vh 1vh 1vh 1vh;">
                        </div>
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
                <button class="success-btn px-3" type="submit" form="reg_form" formmethod="post">Confirm</button>
                <button type="button" class="fail-btn px-3" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php
$page_content = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/system/layout.php';
?>