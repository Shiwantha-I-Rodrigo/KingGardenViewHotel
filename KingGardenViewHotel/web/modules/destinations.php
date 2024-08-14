<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/common.php';
$extra_js = '<script src="' . WEB_BASE_URL . 'js/destinations.js"></script>';
$extra_css = '';
ob_start();
?>



<div class="d-flex justify-content-around align-items-center text-center p-3 row" style="position:fixed; top:10vh; background-color:var(--secondary); z-index:95; width:100vw; ">
    <div class="col-2">
        <i class="material-icons" id="dest_back">arrow_back</i>
    </div>
    <div class="col-5">
        <h4 style="font-size:3vh;">DESTINATIONS</h4>
    </div>
    <div class="col-2">
        <i class="material-icons" id="dest_fwd">arrow_forward</i>
    </div>
</div>

<div class="pb-5" style="position:absolute; top:18vh; background-image: var(--background_img_03);min-height:100vh;" name="dests" id="dests">

</div>

<!-- Book Now Button -->
<div id="booknow">
    <div class="success-btn" id="book_btn">Book Now!</div>
</div>

<div class="modal fade" id="Reservation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background-color:var(--background);">
            <div class="modal-header d-flex justify-content-between">
                <img src="<?= BASE_URL . '/img/common/logo_logo.png' ?>" alt="" style="width: 3vw; height: 5vh; object-fit: cover;">
                <button type="button" class="clear_btn" data-bs-dismiss="modal"><i class="material-icons">cancel</i></button>
            </div>
            <div class="modal-body" style="color:var(--primary_font); text-align: justify; text-justify: inter-word; display:inline-block; height:50vh;">
                <div id="room-details" style="height:100%; width:100%;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="fail-btn px-3" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<?php
$page_content = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/web/layout.php';
?>