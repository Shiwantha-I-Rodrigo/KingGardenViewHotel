<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/common.php';
$extra_js = '<script src="' . SYSTEM_BASE_URL . 'js/index.js"></script>';
$extra_css = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    extract($_POST);
    $_SESSION['TimeSlotStart'] = ($s_date/1000) + 43200;
    $_SESSION['TimeSlotEnd'] = ($e_date/1000) + 43199;
    $_SESSION['guests'] = $guest_count;
    $_SESSION['rooms'] = $rooms_count;
    $_SESSION['guestid'] = $guest_id;
    isset($_POST['discounted']) ? $_SESSION['discounted'] = 1 :  $_SESSION['discounted'] = 0;
    isset($_POST['ac']) ? $_SESSION['ac'] = 1 :  $_SESSION['ac'] = 0;
    isset($_POST['wifi']) ? $_SESSION['wifi'] = 1 :  $_SESSION['wifi'] = 0;
    reDirect('/system/modules/rooms.php');
}

ob_start();
?>

<div class="banner">
    <div class="row mt-2">
        <div class="col-6">
            <div class="row mt-5">
                <div class="col">
                </div>
            </div>
            <div class="row">
                <div class="col">
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card_transparent mt-5 me-5 ms-5">
                <form class="p-0 m-0" id="search_form" name="search_form" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" role="form" novalidate>
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
                            <input name="start_date" id="start_date" class="form-control datepickers" type="date" required />
                            <input name="s_date" id="s_date" class="d-none" type="number" required />
                        </div>
                        <div class="col-2 p-0 m-0 ms-5">
                            <label>Check-out</label>
                        </div>
                        <div class="col-3 p-0 m-0">
                            <input name="end_date" id="end_date" class="form-control datepickers" type="date" required />
                            <input name="e_date" id="e_date" class="d-none" type="number" required />
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
                            <input type="text" name="rooms_count" id="rooms_count" style="text-align:center; border:none;" required readonly/>
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
                            <input type="text" name="guest_count" id="guest_count" style="text-align:center;border:none;" required readonly/>
                        </div>
                        <div class="col-1 p-0 m-0">
                            <button class="tiny-btn px-1 mx-1" name="guest_count_plus" id="guest_count_plus"><i class="material-icons">add</i></button>
                        </div>
                    </div>
                    <div class="row py-4 px-5 border-bottom d-flex justify-content-around">
                        <div class="form-check form-check-inline col-3  p-0 m-0 d-flex justify-content-center">
                            <input class="form-check-input" type="checkbox" id="discounted" name="discounted" value="1">
                            <label class="form-check-label mx-3" for="discounted">Discounted</label>
                        </div>
                        <div class="form-check form-check-inline col-2  p-0 m-0 d-flex justify-content-center">
                            <input class="form-check-input" type="checkbox" id="ac" name="ac" value="1">
                            <label class="form-check-label mx-3" for="ac">AC</label>
                        </div>
                        <div class="form-check form-check-inline col-2  p-0 m-0 d-flex justify-content-center">
                            <input class="form-check-input" type="checkbox" id="wifi" name="wifi" value="1">
                            <label class="form-check-label mx-3" for="wifi">WiFi</label>
                        </div>
                    </div>
                    <div class="row py-4 px-5 border-bottom d-flex justify-content-around">
                        <div class="form-check form-check-inline col-3  p-0 m-0 d-flex justify-content-center">
                            <input type="number" name="guest_id" id="guest_id" placeholder="Guest Id" required />
                        </div>
                    </div>
                    <div class="row mt-3 pb-3">
                        <div class="col-12 d-flex justify-content-center">
                            <button name="search" id="search" class="success-btn px-5 mx-4">Search</button>
                        </div>
                    </div>
                </form>
            </div>
            </form>
        </div>
    </div>
</div>
</div>

<?php
$page_content = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/system/layout.php';
?>