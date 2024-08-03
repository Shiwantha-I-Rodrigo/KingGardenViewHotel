<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/common.php';
$extra_js = '<script src="' . WEB_BASE_URL . 'js/blog.js"></script>';
$extra_css = '';
ob_start();
?>

<div class="d-flex justify-content-between align-items-center p-3" style="position:fixed; top:10vh; background-image: var(--background_img_03); z-index:95; width:100vw; ">
    <i class="material-icons" id="blog_back">arrow_back</i>
    <i class="material-icons" id="blog_fwd">arrow_forward</i>
</div>

<div class="pb-5" style="position:absolute; top:15vh; background-image: var(--background_img_03);min-height:100vh;" name="blogs" id="blogs">

</div>

<!-- Book Now Button -->
<div id="booknow">
    <div class="success-btn" id="book_btn">Book Now!</div>
</div>

<?php
$page_content = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/web/layout.php';
?>