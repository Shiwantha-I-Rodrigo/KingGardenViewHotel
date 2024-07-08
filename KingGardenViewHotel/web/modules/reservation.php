<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
$extra_js = '<script src="' . WEB_BASE_URL . 'js/reservation.js"></script>';
$extra_css = '';
ob_start();
?>

<div style="position:absolute; top:10vh; background-image: var(--background_img_03);">
    <div class="row px-5" style="width:100vw; height:80vh;">';
        <div class="col-12 m-0 p-0" style="background-color:var(--background);border: 0.5vh solid var(--background);border-radius: 2vh;">
            <div class="row">
                <img class="m-0 p-0" src="<?= BASE_URL ?>/img/common/room1.jpg" alt="" style="height: 15vh; object-fit: cover; border-radius: 2vh 2vh 0 0;">
            </div>
            <div class="row ms-3 mt-3">
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
                    <label>Deulux Room</label>
                    <p>Price : Rs 3000<br>
                        Discounted price Rs.2000 for your entire stay<br>
                        occupancy : 3<br>
                        Theres a great range of accommodation options to choose from
                        at the Araliya Red, one of the very best hotels in Nuwara Eliya,
                        Sri Lanka. Each room in our selection was designed with comfort and ease of living in mind
                        the perfect places to pull your feet up and relax in! Enjoy the premium comfort of our suites
                        or indulge in a truly luxurious experience at one of our deluxe rooms the choice is up to you!
                    </p>
                    <label>Deulux Room</label>
                    <label>Deulux Room</label>
                </div>
            </div>
            <button type="button" class="fail-btn px-3 m-4" data-bs-dismiss="modal">Cancel Reservation</button>
        </div>';
    </div>
    <div class="row" style="height:10vh;"></div>';
</div>

<?php
$page_content = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/web/layout.php';
?>