<div class="card mb-4">
    <div class="card-body text-center">
        <img src="<?= $profile_pic ?>" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
        <h2 class="my-1" style="font-size : 4vh;"><?= $username ?></h2>
        <p class="mb-1">Last Update. : <?= date("Y-M-d H:i:s A", $update[0]) . "<br/>By : " . $update[1] . " ( User Id )" ?></p>
        <p class="mb-4">Account : <?= $type ?></p>
        <div class="d-flex justify-content-around mb-2">
            <a href="edit_employees.php?id=<?= $user_id ?>""><button type="button" class="success-btn px-3 py-2" style="width:8vw;">Edit</button></a>
            <a href="../sub/logout.php"><button type="button" class="fail-btn px-3 py-2" style="width:8vw;">Logout</button></a>
        </div>
    </div>
</div>
<div class="card mb-4">
    <div class="card-body" style="min-height: 20vh;">
        <p class="mb-4"><span class="text-primary font-italic me-1">TABLE</span> FILTERS</p>
        <div class="row my-3 border border-2 border-white rounded-3 mb-3 p-2">
            <div class="col-3 d-flex justify-content-end">
                <label>Search :</label>
            </div>
            <div class="col-8 d-flex justify-content-end">
                <input name="search" id="search">
            </div>
        </div>
        <div class="border border-2 border-white rounded-3 mb-3 p-2">
            <div class="row mb-3">
                <div class="col-3 d-flex justify-content-end">
                    <label for="sort">Sort By : </label>
                </div>
                <div class="col-5 d-flex justify-content-center">
                    <select name="sort" id="sort">
                        <?= $sort_options ?>
                    </select>
                </div>
                <div class="col-3 d-flex justify-content-center">
                    <select name="order" id="order">
                        <option value="ASC">Acending</option>
                        <option value="DESC">Decending</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="border border-2 border-white rounded-3 mb-3 p-2">
            <div class="row mb-2">
                <div class="col-3 d-flex justify-content-center">
                    <label>Range :</label>
                </div>
                <div class="col-5 d-flex justify-content-center">
                    <select name="range" id="range">
                        <?= $range_options ?>
                    </select>
                </div>
            </div>
            <div class="row" id="number_range">
                <div class="col-3 d-flex justify-content-end">
                    <label>Min : </label>
                </div>
                <div class="col-3 d-flex justify-content-end">
                    <input name="min" id="min">
                </div>
                <div class="col-3 d-flex justify-content-end">
                    <label>Max : </label>
                </div>
                <div class="col-3 d-flex justify-content-end">
                    <input name="max" id="max">
                </div>
            </div>
            <div class="d-none" id="date_range">
                <div class="row">
                    <div class="col-5 p-0 m-0 d-flex justify-content-center">
                        <label>Start date</label>
                    </div>
                    <div class="col-5 p-0 m-0 ms-5 d-flex justify-content-center">
                        <label>End date</label>
                    </div>
                </div>
                <div class="row d-flex">
                    <div class="col-6 p-0 m-0">
                        <input name="start_date" id="start_date" class="form-control datepickers" type="date" required />
                        <input name="s_date" id="s_date" class="d-none" type="number" required />
                    </div>
                    <div class="col-6 p-0 m-0">
                        <input name="end_date" id="end_date" class="form-control datepickers" type="date" required />
                        <input name="e_date" id="e_date" class="d-none" type="number" required />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <button class="success-btn px-3 py-2" name="filter_btn" id="filter_btn"><i class="material-icons">filter_alt</i> Apply Filters</button>
        </div>
    </div>
</div>