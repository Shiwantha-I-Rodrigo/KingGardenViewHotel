<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/common.php';
isset($_SESSION['user_id']) ? $user_id = $_SESSION['user_id'] : reDirect("/system/modules/login.php");
authorize($user_id, '6', 'system');
$extra_js = '<script src="' . SYSTEM_BASE_URL . 'js/edit_destinations.js"></script>';
$extra_css = '';

$db = dbConn();

require_once $_SERVER['DOCUMENT_ROOT'] . '/system/sub/user_info.php';

$url =  basename($_SERVER['REQUEST_URI']);
$url_componenets = parse_url($url);
parse_str($url_componenets['query'], $params);
isset($params['id']) ? $destination_id = $params['id'] :  $destination_id = 0;

if ($destination_id != 0) {
    $sql = "SELECT * FROM destinations WHERE DestinationId = $destination_id";
    $result = $db->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $destination_text = $row['DestinationText'];
            $destination_title = $row['DestinationTitle'];
            $destination_status = $row['DestinationStatus'];
            $destination_picture = $row['DestinationPicture'];
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
        $upload = ",`DestinationPicture`='$full_path'";
    }

    if ($destination_id != 0) {
        $sql = "UPDATE destinations SET `DestinationText`='$destination_text',`DestinationTitle`='$destination_title', `DestinationStatus`='$destination_status' $upload WHERE DestinationId=$destination_id";
        $db->query($sql);
    } else {
        $sql = "INSERT INTO destinations (`DestinationText`, `DestinationTitle`, `DestinationStatus`,`DestinationPicture`) VALUES ('$destination_text', '$destination_title', '$destination_status', '$full_path')";
        $db->query($sql);
    }

    $_SESSION['alert_color'] = "var(--primary)";
    $_SESSION['alert_icon'] = "task_alt";
    $_SESSION['alert_title'] = "Success !";
    $_SESSION['alert_msg'] = "The information was submitted succesfully";
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
                    <div class="my-4 text-center"><label class="my-1" style="font-size : 2vh;">DESTINATION <?= $destination_id ?></label></div>

                    <form id="reg_form" enctype="multipart/form-data" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $destination_id; ?>" method="post" role="form" novalidate>

                        <div class="row mx-5">
                            <div class="col-6 d-flex justify-content-start align-items-bottom">
                                <label>Destination Title</label>
                            </div>
                            <div class="col-6 d-flex justify-content-start align-items-bottom">
                                <label>Destination Status</label>
                            </div>
                        </div>
                        <div class="row mx-5">
                            <div class="col-6 d-flex justify-content-end align-items-center">
                                <input type="text" name="destination_title" id="destination_title" value="<?= $destination_title ?>" placeholder="Destination Title" required />
                            </div>
                            <div class="col-6 d-flex justify-content-start align-items-center">
                                <select class="w-50" name="destination_status" id="destination_status">
                                    <option <?php echo ($destination_status == 0) ? 'selected' : ''; ?> value="0">Inactive</option>
                                    <option <?php echo ($destination_status == 1) ? 'selected' : ''; ?> value="1">Active</option>
                                    <option <?php echo ($destination_status == 2) ? 'selected' : ''; ?> value="2">Unavailable</option>
                                    <option <?php echo ($destination_status == 3) ? 'selected' : ''; ?> value="3">Unauthorized</option>
                                    <option <?php echo ($destination_status == 4) ? 'selected' : ''; ?> value="4">Invalid</option>
                                    <option <?php echo ($destination_status == 5) ? 'selected' : ''; ?> value="5">Reserved</option>
                                    <option <?php echo ($destination_status == 6) ? 'selected' : ''; ?> value="6">Discounted</option>
                                    <option <?php echo ($destination_status == 9) ? 'selected' : ''; ?> value="6">Forbidden</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mx-5 px-3 mt-3">
                            <div class="col-12 d-flex justify-content-start align-items-bottom">
                                <label>Destination Text</label>
                            </div>
                        </div>
                        <div class="row mx-5 px-3 mb-5">
                            <div class="col-12 d-flex justify-content-end align-items-center">
                                <input type="text" name="destination_text" id="destination_text" value="<?= $destination_text ?>" placeholder="Destination Text" required />
                            </div>
                        </div>
                        <div class="row mx-5 mt-3">
                            <div class="col-6 d-flex justify-content-start align-items-bottom">
                                <label>Destination Picture</label>
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
                            <img class="m-0 p-0" src="<?= $destination_picture ?>" alt="" style="width: 100%; object-fit: cover; border-radius: 1vh 1vh 1vh 1vh;">
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