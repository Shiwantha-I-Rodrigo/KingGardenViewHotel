<?php
session_start();
session_destroy();
require_once $_SERVER['DOCUMENT_ROOT'] . '/common.php';
reDirect("/system/modules/login.php");
?>