<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/common.php';
isset($_SESSION['user_id']) ? $user_id = $_SESSION['user_id'] : reDirect("/system/modules/login.php");
authorize($user_id, '9', 'system');
$extra_js = '<script src="' . SYSTEM_BASE_URL . 'js/list_blogs.js"></script>';
$extra_css = '';

$db = dbConn();

require_once $_SERVER['DOCUMENT_ROOT'] . '/system/sub/user_info.php';

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

$sort_options = '<option value="i.BlogId">Blog Id</option>
<option value="i.BlogText">Blog Text</option>
<option value="i.BlogTitle">Blog Title</option>
<option value="i.BlogStatus">Blog Status</option>';

$range_options = '<option value="i.BlogId">Blog Id</option>';

ob_start();
?>

<section style="background-color:var(--shadow);">
    <div class="row" style="height:10vh;"></div>
    <div class="row mx-5">
        <div class="col-3">
            <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/system/sub/list_tools.php'; ?>
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
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/system/sub/sidebar.php'; ?>
    </div>
    <div class="row" style="height:10vh;"></div>
</section>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/system/sub/modals.php';
$page_content = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/system/layout.php';
?>