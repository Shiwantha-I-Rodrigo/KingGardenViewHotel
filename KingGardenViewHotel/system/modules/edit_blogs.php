<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/common.php';
isset($_SESSION['user_id']) ? $user_id = $_SESSION['user_id'] : reDirect("/system/modules/login.php");
authorize($user_id, '9', 'system');
$extra_js = '<script src="' . SYSTEM_BASE_URL . 'js/edit_blogs.js"></script>';
$extra_css = '';

$db = dbConn();

require_once $_SERVER['DOCUMENT_ROOT'] . '/system/sub/user_info.php';

$url =  basename($_SERVER['REQUEST_URI']);
$url_componenets = parse_url($url);
parse_str($url_componenets['query'], $params);
isset($params['id']) ? $blog_id = $params['id'] :  $blog_id = 0;

if ($blog_id != 0) {
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

    if ($blog_id != 0) {
        $sql = "UPDATE blogs SET `BlogText`='$blog_text',`BlogTitle`='$blog_title', `BlogStatus`='$blog_status' $upload WHERE BlogId=$blog_id";
        $db->query($sql);
    }else{
        $sql = "INSERT INTO blogs (`BlogText`, `BlogTitle`, `BlogStatus`,`BlogPicture`) VALUES (`$blog_text`, `$blog_title`, `$blog_status`, `$full_path`)";
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
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/system/sub/sidebar.php'; ?>
    </div>
    <div class="row" style="height:10vh;"></div>
</section>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/system/sub/modals.php';
$page_content = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/system/layout.php';
?>