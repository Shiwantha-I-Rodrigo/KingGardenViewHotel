<div class="card mb-4">
    <div class="card-body text-center">
        <img src="<?= $profile_pic ?>" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
        <h2 class="my-1" style="font-size : 4vh;"><?= $username ?></h2>
        <p class="mb-1">Last Update. : <?= date("Y-M-d H:i:s A", $update[0]) . "<br/>By : " . $update[1] . " ( User Id )" ?></p>
        <p class="mb-4">Account : <?= $type ?></p>
        <div class="d-flex justify-content-around mb-2">
            <a href="edit_employees.php?id=<?= $user_id ?>"><button type="button" class="success-btn px-3 py-2" style="width:8vw;">Edit</button></a>
            <a href="../sub/logout.php"><button type="button" class="fail-btn px-3 py-2" style="width:8vw;">Logout</button></a>
        </div>
    </div>
</div>
<div class="card mb-4">
    <div class="card-body" style="min-height: 20vh;">

        <p class="mb-4"><span class="text-primary font-italic me-1">EDIT</span> TOOLS</p>

        <div class="row">
            <button class="success-btn px-3 py-2 mb-4" name="reset_btn" id="reset_btn"><i class="material-icons">restore</i> Reset data</button>
        </div>

        <div class="row">
            <button class="success-btn px-3 py-2 mb-4" name="clear_btn" id="clear_btn"><i class="material-icons">backspace</i> Clear Data</button>
        </div>

        <div class="row">
            <button class="success-btn px-3 py-2 mb-4" name="save_btn" id="save_btn" data-bs-toggle="modal" data-bs-target="#EditConfirm"><i class="material-icons">save</i> Save data</button>
        </div>
    </div>
</div>