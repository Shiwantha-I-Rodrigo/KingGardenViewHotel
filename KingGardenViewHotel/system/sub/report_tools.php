<div class="card mb-4">
    <div class="card-body text-center">
        <img src="<?= $profile_pic ?>" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
        <h2 class="my-1" style="font-size : 4vh;"><?= $username ?></h2>
        <p class="mb-1">Last Update. : <?= date("Y-M-d H:i:s A", $update[0]) . "<br/>By : " . $update[1] . " ( User Id )" ?></p>
        <p class="mb-4">Account : <?= $type ?></p>
        <div class="d-flex justify-content-around mb-2">
            <a href="edit_employees.php?id=<?= $user_id ?>""><button type=" button" class="success-btn px-3 py-2" style="width:8vw;">Edit</button></a>
            <a href="../sub/logout.php"><button type="button" class="fail-btn px-3 py-2" style="width:8vw;">Logout</button></a>
        </div>
    </div>
</div>
<div class="card mb-4">
    <div class="card-body" style="min-height: 20vh;">

        <p class="mb-4"><span class="text-primary font-italic me-1">REPORT</span> TOOLS</p>

        <div class="row">
            <div class="col-4 p-0 m-0 d-flex justify-content-start">
                <label>Report Date</label>
            </div>
            <div class="col-8 p-0 m-0 mb-4">
                <input name="start_date" id="start_date" class="form-control datepickers" type="date" required />
                <input name="s_date" id="s_date" class="d-none" type="number" required />
            </div>
        </div>

        <div class="row">
            <button class="success-btn px-3 py-2 mb-4" name="day_btn" id="day_btn"><i class="material-icons">today</i> Daily Report </button>
        </div>

        <div class="row">
            <button class="success-btn px-3 py-2 mb-4" name="month_btn" id="month_btn"><i class="material-icons">date_range</i> Monthly Report </button>
        </div>

        <div class="row">
            <button class="success-btn px-3 py-2 mb-4" name="year_btn" id="year_btn"><i class="material-icons">calendar_month</i> Yearly Report </button>
        </div>
    </div>
</div>