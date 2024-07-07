<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
$extra_js = '<script src="' . WEB_BASE_URL . 'js/index.js"></script>';
$extra_css = '';
ob_start();
?>

<div class="banner">
    <div class="row mt-2">
        <div class="col-6">
            <div class="row mt-5">
                <div class="col">
                    <h2>Welcome<br>King Garden</h2>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <h3 style="font-size: 5vh;">Enjoy your stay with us</h3>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card_transparent mt-5 me-5 ms-5">
                <form class="p-0 m-0">
                    <div class="row py-3 border-bottom">
                        <div class="col-12 d-flex justify-content-center align-items-center">
                            <h2 style="font-size:5vh;">Search for Accomadations</h2>
                        </div>
                    </div>
                    <div class="row py-4 px-5 d-flex justify-content-between">
                        <div class="col-2 p-0 m-0">
                            <label>Check-in</label>
                        </div>
                        <div class="col-3 p-0 m-0">
                            <input name="start_date" id="start_date" class="form-control datepickers" type="date" required/>
                        </div>
                        <div class="col-2 p-0 m-0 ms-5">
                            <label>Check-out</label>
                        </div>
                        <div class="col-3 p-0 m-0">
                            <input name="end_date" id="end_date" class="form-control datepickers" type="date" required/>
                        </div>
                    </div>
                    <div class="row py-4 px-5 d-flex justify-content-between border-bottom">
                        <div class="col-2 p-0 m-0">
                            <label>Rooms</label>
                        </div>
                        <div class="col-1 p-0 m-0">
                            <button class="tiny-btn px-1 mx-1" name="rooms_count_minus" id="rooms_count_minus"><i class="material-icons">remove</i></button>
                        </div>
                        <div class="col-1 p-0 m-0">
                            <input type="text" name="rooms_count" id="rooms_count" style="text-align:center;" required />
                        </div>
                        <div class="col-1 p-0 m-0">
                            <button class="tiny-btn px-1 mx-1" name="rooms_count_plus" id="rooms_count_plus"><i class="material-icons">add</i></button>
                        </div>
                        <div class="col-2 p-0 m-0 ms-5">
                            <label>Guests</label>
                        </div>
                        <div class="col-1 p-0 m-0">
                            <button class="tiny-btn px-1 mx-1" name="guest_count_minus" id="guest_count_minus"><i class="material-icons">remove</i></button>
                        </div>
                        <div class="col-1 p-0 m-0">
                            <input type="text" name="guest_count" id="guest_count" style="text-align:center;" required />
                        </div>
                        <div class="col-1 p-0 m-0">
                            <button class="tiny-btn px-1 mx-1" name="guest_count_plus" id="guest_count_plus"><i class="material-icons">add</i></button>
                        </div>
                    </div>
                    <div class="row py-4 px-5 border-bottom d-flex justify-content-end">
                        <div class="form-check form-check-inline col-3  p-0 m-0">
                            <input class="form-check-input" type="checkbox" id="available" value="1" checked>
                            <label class="form-check-label mx-3" for="available">Available</label>
                        </div>
                        <div class="form-check form-check-inline col-3  p-0 m-0">
                            <input class="form-check-input" type="checkbox" id="discounted" value="2">
                            <label class="form-check-label mx-3" for="discounted">Discounted</label>
                        </div>
                        <div class="form-check form-check-inline col-2  p-0 m-0">
                            <input class="form-check-input" type="checkbox" id="ac" value="3">
                            <label class="form-check-label mx-3" for="ac">AC</label>
                        </div>
                        <div class="form-check form-check-inline col-2  p-0 m-0">
                            <input class="form-check-input" type="checkbox" id="wifi" value="4">
                            <label class="form-check-label mx-3" for="wifi">WiFi</label>
                        </div>
                    </div>
                    <div class="row mt-3 pb-3">
                        <div class="col-12 d-flex justify-content-center">
                            <button class="success-btn px-5 mx-4">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$page_content = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/web/layout.php';
?>