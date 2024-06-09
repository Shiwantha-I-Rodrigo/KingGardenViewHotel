<!DOCTYPE html>
<html lang="en">

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
?>

<head>
    <title>kgvh</title>
    <meta charset="utf-8">
    <link href="<?= WEB_BASE_URL ?>images/favicon.ico" rel="icon">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <!-- Style Sheets -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="<?= WEB_BASE_URL ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= WEB_BASE_URL ?>css/common.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5 p-5">
        <div class="card">
            <div class="row m-5">
                <div class="col-12 d-flex justify-content-center">
                    <img src="<?= BASE_URL . '/img/common/logo_big_embosed.png' ?>" alt="logo" style="height:75px;">
                </div>
            </div>
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" role="form" novalidate>

                <div class="row mx-5">
                    <div class="col-6 d-flex justify-content-start align-items-bottom">
                        <label>First Name</label>
                    </div>
                    <div class="col-6 d-flex justify-content-start align-items-bottom">
                        <label>Last Name</label>
                    </div>
                </div>
                <div class="row mx-5">
                    <div class="col-1 d-flex justify-content-start align-items-center">
                        <select>
                            <option selected>Title</option>
                            <option value="1">Mr.</option>
                            <option value="2">Mrs.</option>
                            <option value="3">Ms.</option>
                            <option value="3">Dr.</option>
                            <option value="3">Ven.</option>
                            <option value="3">Other.</option>
                        </select>
                    </div>
                    <div class="col-5 d-flex justify-content-end align-items-center">
                        <input type="text" name="first_name" id="first_name" placeholder="First Name" required />
                    </div>
                    <div class="col-6 d-flex justify-content-end align-items-center">
                        <input type="text" name="last_name" id="last_name" placeholder="Last Name" required />
                    </div>
                </div>

                <div class="row mx-5">
                    <div class="col-6 d-flex justify-content-start align-items-bottom">
                        <label>Username</label>
                    </div>
                    <div class="col-6 d-flex justify-content-start align-items-bottom">
                        <label>Email</label>
                    </div>
                </div>
                <div class="row mx-5">
                    <div class="col-6 d-flex justify-content-end align-items-center">
                        <input type="text" name="user_name" id="user_name" placeholder="Username" required />
                    </div>
                    <div class="col-6 d-flex justify-content-end align-items-center">
                        <input type="text" name="email" id="email" placeholder="Email" required />
                    </div>
                </div>

                <div class="row mx-5">
                    <div class="col-6 d-flex justify-content-start align-items-bottom">
                        <label>Password</label>
                    </div>
                    <div class="col-6 d-flex justify-content-start align-items-bottom">
                        <label>Confirm Password</label>
                    </div>
                </div>
                <div class="row mx-5">
                    <div class="col-6 d-flex justify-content-end align-items-center">
                        <input type="text" name="password" id="password" placeholder="Password" required />
                    </div>
                    <div class="col-6 d-flex justify-content-end align-items-center">
                        <input type="text" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required />
                    </div>
                </div>

                <div class="row mx-5">
                    <div class="alert col-12 d-none" role="alert" id="password_meter">
                        <ul class="list-unstyled">
                            <li class="requirements">
                                <i class="material-icons success" style="font-size: 24px;" id="length_tick">check_circle</i>
                                <i class="material-icons fail" style="font-size: 24px;" id="length_cross">cancel</i>
                                <label>must have at least 8 chars</label>
                            </li>
                            <li class="requirements">
                                <i class="material-icons success" style="font-size: 24px;" id="upper_tick">check_circle</i>
                                <i class="material-icons fail" style="font-size: 24px;" id="upper_cross">cancel</i>
                                <label>must have a uppercase letter</label>
                            </li>
                            <li class="requirements">
                                <i class="material-icons success" style="font-size: 24px;" id="lower_tick">check_circle</i>
                                <i class="material-icons fail" style="font-size: 24px;" id="lower_cross">cancel</i>
                                <label>must have a lowercase letter</label>
                            </li>
                            <li class="requirements">
                                <i class="material-icons success" style="font-size: 24px;" id="number_tick">check_circle</i>
                                <i class="material-icons fail" style="font-size: 24px;" id="number_cross">cancel</i>
                                <label>must have a number</label>
                            </li>
                            <li class="requirements">
                                <i class="material-icons success" style="font-size: 24px;" id="char_tick">check_circle</i>
                                <i class="material-icons fail" style="font-size: 24px;" id="char_cross">cancel</i>
                                <label>must have a special character</label>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="row mx-5">
                    <div class="col-6 d-flex justify-content-start align-items-bottom">
                        <label>Address</label>
                    </div>
                    <div class="col-6 d-flex justify-content-start align-items-bottom">
                        <label>Profile Picture</label>
                    </div>
                </div>
                <div class="row mx-5">
                    <div class="col-6 d-flex justify-content-end align-items-center">
                        <input type="text" name="address_1" id="address_1" placeholder="House No. & Street" required />
                    </div>
                    <div class="col-6 d-flex justify-content-end align-items-center">
                        <input type="file" id="formFile">
                    </div>
                </div>
                <div class="row mx-5">
                    <div class="col-6 d-flex justify-content-end align-items-center">
                        <input type="text" name="address_2" id="address_2" placeholder="City" required />
                    </div>
                </div>
                <div class="row mx-5">
                    <div class="col-6 d-flex justify-content-end align-items-center">
                        <input type="text" name="address_3" id="address_3" placeholder="Province" required />
                    </div>
                </div>

                <div class="row mx-5">
                    <div class="col-6 d-flex justify-content-start align-items-bottom">
                        <label>Telephone</label>
                    </div>
                    <div class="col-6 d-flex justify-content-start align-items-bottom">
                        <label>Mobile</label>
                    </div>
                </div>
                <div class="row mx-5">
                    <div class="col-6 d-flex justify-content-end align-items-center">
                        <input type="text" name="telephone" id="telephone" placeholder="Telephone" required />
                    </div>
                    <div class="col-6 d-flex justify-content-end align-items-center">
                        <input type="text" name="mobile" id="mobile" placeholder="Mobile" required />
                    </div>
                </div>

                <div class="row my-4 mx-5">
                    <div class="col-12 d-flex justify-content-end">
                        <button class="success-btn px-5 mx-4" id="submit_btn">Submit</button>
                        <button class="fail-btn px-5" id="cancel_btn">Cancel</button>
                    </div>
                </div>

            </form>
            <div class="row">
                <div class="col-12 d-flex justify-content-center">
                    <img src="<?= BASE_URL . '/img/common/mountains_4.png' ?>" alt="mountains_1" style="width:100%; border-radius: 10px;">
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="<?= WEB_BASE_URL ?>js/bootstrap.min.js"></script>
    <script src="<?= WEB_BASE_URL ?>js/jquery-3.7.1.min.js"></script>
    <script src="<?= WEB_BASE_URL ?>js/sweetalert2@11.js"></script>
    <script src="<?= WEB_BASE_URL ?>js/common.js"></script>
    <script src="<?= WEB_BASE_URL ?>js/register.js"></script>

</body>

</html>