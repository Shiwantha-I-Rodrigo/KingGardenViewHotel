<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
$extra_js = '<script src="' . WEB_BASE_URL . 'js/rooms.js"></script>';
$extra_css = '';
ob_start();
?>

<div style="position:absolute; top:10vh; background-image: var(--background_img_03);">
    <?php
    $count_items = 9;
    $columns = 3;
    echo '<div class="row my-5 px-5 d-flex justify-content-between" style="width:100vw; height:30vh;">';
    for ($i = 0; $i < $count_items; $i++) {
        if ($i % $columns === 0 && $i > 0) {
            echo '</div><div class="row my-5 px-5 d-flex justify-content-between" style="width:100vw; height:30vh;">';
        }
        echo '<div name="room' . $i . '" id="room' . $i . '" class="col-3 m-0 p-0 room" style="background-color:var(--background);border: 0.5vh solid var(--background);border-radius: 2vh;">
                <div class="row">
                    <img class="m-0 p-0" src="'. BASE_URL . '/img/common/room1.jpg' . '" alt="" style="height: 15vh; object-fit: cover; border-radius: 2vh 2vh 0 0;">
                </div>
                <div class="p-2">
                    <label>Deulux Room</label>
                    <p>Price : Rs 3000<br>
                        Discounted price Rs.2000 for your entire stay<br>
                        occupancy : 3<br>
                        <i class="material-icons">wifi</i>
                        <i class="material-icons">ac_unit</i>
                        <i class="material-icons">favorite</i>
                        <i class="material-icons">restaurant</i>
                    </p>
                </div>
            </div>';
    }
    echo '</div><div class="row" style="height:10vh;"></div>';
    ?>
</div>

<!-- Book Now Button -->
<div id="booknow">
    <div class="success-btn" id="book_btn">Book Now!</div>
</div>

<!-- Reservation Modal -->
<div class="modal fade" id="Reservation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background-color:var(--background);">
            <div class="modal-header d-flex justify-content-between">
                <img src="<?= BASE_URL . '/img/common/logo_logo.png' ?>" alt="" style="width: 3vw; height: 5vh; object-fit: cover;">
                <label class="mx-3" style="font-size:3vh;">Confirm Reservation</label>
                <button type="button" class="clear_btn" data-bs-dismiss="modal"><i class="material-icons">cancel</i></button>
            </div>
            <div class="modal-body" style="color:var(--primary_font); text-align: justify; text-justify: inter-word; display:inline-block">
                Are you sure you want to confirm the reservation for <div id="room-id" style="display:inline-block"></div> ?
                <div id="room-details"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="success-btn px-3" data-bs-dismiss="modal">Confirm</button>
                <button type="button" class="fail-btn px-3" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<?php
$page_content = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/web/layout.php';
?>