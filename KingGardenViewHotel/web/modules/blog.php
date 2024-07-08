<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
$extra_js = '<script src="' . WEB_BASE_URL . 'js/blog.js"></script>';
$extra_css = '';
ob_start();
?>

<div style="position:absolute; top:10vh; background-image: var(--background_img_03);">
    <?php
    $count_items = 9;
    $columns = 1;
    echo '<div class="row my-5 ps-5" style="width:100vw; height:30vh;">';
    for ($i = 0; $i < $count_items; $i++) {
        if ($i % $columns === 0 && $i > 0) {
            echo '</div><div class="row my-5 ps-5" style="width:100vw; height:30vh;">';
        }
        echo '<div class="col-11 m-0 p-0" style="background-color:var(--background);border: 0.5vh solid var(--background);border-radius: 2vh;">
                <div class="row m-0 p-0">
                    <div class="col-4 m-0 p-0" style="overflow: hidden;">
                        <img class="m-0 p-0" src="'. BASE_URL . '/img/common/banner4.jpg' . '" alt="" style="height:30vh; object-fit: cover; border-radius: 2vh 0 0 2vh;">
                    </div>
                    <div class="col-8 m-0 p-0">
                        <h3 style="font-size: 3vh; text-align:center;" class="my-3">Enjoy your stay with us</h3>
                        <p class="me-3 px-5" style="font-size:2vh; text-align: justify; text-justify: inter-word;">
                        Explore the city of Colombo with its rich colonial history, cultural heritage and diverse community. 
                        From shopping centres to religious sites, each tour is a day you will want to recount again.
                        Rediscover your inner harmony and revitalise your mind, body, and spirit at our luxurious spa. Experience a blissful 
                        retreat within our urban oasis, dedicated to your well-being and rejuvenation. </p>
                    </div>
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

<!-- Room Modal -->
<div class="modal fade" id="Sustainability" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background-color:var(--background);">
            <div class="modal-header d-flex justify-content-between">
                <img src="<?= BASE_URL . '/img/common/logo_logo.png' ?>" alt="" style="width: 3vw; height: 5vh; object-fit: cover;">
                <label class="mx-3" style="font-size:3vh;">Sustainability Policy</label>
                <button type="button" class="clear_btn" data-bs-dismiss="modal"><i class="material-icons">cancel</i></button>
            </div>
            <div class="modal-body" style="color:var(--primary_font); text-align: justify; text-justify: inter-word;">
                Cinnamon Grand will strive to conduct its activities in accordance with the highest standards of corporate
                best practice and in compliance with all applicable local and international regulatory requirements and
                conventions.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php
$page_content = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/web/layout.php';
?>