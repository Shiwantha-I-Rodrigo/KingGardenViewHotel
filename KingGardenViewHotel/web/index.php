<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
$extra_js = '<script src="' . WEB_BASE_URL . 'js/index.js"></script>';
$extra_css = '';
ob_start();
?>

<div class="row banner">
    <img src="<?= BASE_URL . 'img/common/mountains_2.png'?>" />
</div>

<?php
$page_content = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/web/layout.php';
?>