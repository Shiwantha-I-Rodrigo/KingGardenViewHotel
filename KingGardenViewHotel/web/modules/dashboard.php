<?php
session_start();
ob_start();
?>

Dashboard

<?php
$page_content = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/web/layout.php';
?>