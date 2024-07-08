<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
$extra_js = '<script src="' . WEB_BASE_URL . 'js/contact.js"></script>';
$extra_css = '';
ob_start();
?>

<div class="row pt-5" style="position:absolute; top:10vh; background-image: var(--background_img_03); width:100vw; height:100vh;">
    <div class="col-6 ms-5 mt-5">
        <div class="row">
            <div class="col-4">
                <label>Full Name</label>
            </div>
            <div class="col-8">
                <input type="text" name="user_name" id="user_name" placeholder="Name" required />
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <label>Email</label>
            </div>
            <div class="col-8">
                <input type="text" name="user_name" id="user_name" placeholder="Email" required />
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <label>Telephone</label>
            </div>
            <div class="col-8">
                <input type="text" name="user_name" id="user_name" placeholder="Telephone" required />
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <label>Subject</label>
            </div>
            <div class="col-8">
                <input type="text" name="user_name" id="user_name" placeholder="Subject" required />
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <label>Message</label>
            </div>
            <div class="col-8 m-0 p-0 pe-4 ps-1">
                <textarea id="freeform" name="freeform" rows="4" cols="50">Message</textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-2">
                <button class="success-btn px-5 py-2 mt-5">Submit</button>
            </div>
        </div>
    </div>
    <div class="col-5 ms-5">
        <div class="row">
            <div class="card w-100 p-3">
                <div class="card-body">
                    <h5 class="card-title">Visit Us</h5>
                </div>
                <div class="ratio ratio-16x9">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d495.11344402361016!2d80.88619897698673!3d6.901658736970721!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae479436af5e1c9%3A0x260d86654891194f!2sKing%20Garden%20View%20Hotel!5e0!3m2!1sit!2sit!4v1720430491432!5m2!1sit!2sit" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
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