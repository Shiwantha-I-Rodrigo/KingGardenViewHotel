<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/common.php';
isset($_SESSION['user_id']) ? $user_id = $_SESSION['user_id'] : reDirect("/system/modules/login.php");
authorize($user_id, '11', 'system');
$extra_js = '<script src="' . SYSTEM_BASE_URL . 'js/list_reports.js"></script>';
$extra_css = '';

$db = dbConn();

require_once $_SERVER['DOCUMENT_ROOT'] . '/system/sub/user_info.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    extract($_POST);
    if (isset($id)) {
        $sql = "DELETE FROM items WHERE ItemId = $id";
        $result = $db->query($sql);
        if ($result) {
            echo '<div id="removed"></div>';
        }
    }
}

ob_start();
?>

<section style="background-color:var(--shadow);">
    <div class="row" style="height:10vh;"></div>
    <div class="row mx-5">
        <div class="col-3">
            <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/system/sub/report_tools.php'; ?>
        </div>
        <div class="col-7">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <a class="success-btn p-2 m-0 align-items-center" id="print_btn"><i class="material-icons">print</i></a>
                    </div>
                    <div class="my-4 text-center"><label class="my-1" style="font-size : 2vh;">REPORT</label></div>
                    <div id="print_page">
                        <table width="100%">
                            <tr>
                                <th align="center" colspan="1">
                                    <img class="d-none" style="height:8vh;" src="<?= BASE_URL . '/img/common/logo_logo.png' ?>" alt="logo">
                                </th>
                            </tr>
                            <tr>
                                <th align="center" colspan="4">
                                    <p class="d-none" style="font-size:4vh;"> KING GARDEN VIEW HOTEL </p>
                                </th>
                            </tr>
                            <tr>
                                <th align="center" colspan="1">
                                    <p class="d-none" style="font-size:2vh;">REPORT</p>
                                </th>
                            </tr>
                            <tr>
                                <th align="center" colspan="1">
                                    <p class="d-none" style="font-size:2vh;"> This report is generated on <?= getTimes(time()); ?> by user : <?= $user_id; ?> <nobr>on behalf of KING GARDEN VIEW HOTEL.</nobr>
                                    </p>
                                </th>
                            </tr>
                            <tr>
                                <td colspan="1">
                                    <table id="tbl" name="tbl" class="table table-dark table-striped-columns table-hover" width="100%">

                                    </table>
                                </td>
                            </tr>
                        </table>
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