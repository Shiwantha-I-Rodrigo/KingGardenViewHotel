sessionStorage.setItem("current_page", "home");

document.getElementById("rooms_count_minus").addEventListener("click", function (event) {
    event.preventDefault();
    let count = document.getElementById("rooms_count");
    let rooms_count = parseInt(count.value, 10);
    if (isNaN(rooms_count) || count.value <= 1) {
        count.value = 1;
    } else {
        rooms_count--;
        count.value = rooms_count;
    }
});

document.getElementById("rooms_count_plus").addEventListener("click", function (event) {
    event.preventDefault();
    let count = document.getElementById("rooms_count");
    let rooms_count = parseInt(count.value, 10);
    if (isNaN(rooms_count) || count.value < 1) {
        count.value = 1;
    } else if (count.value < 3) {
        rooms_count++;
        count.value = rooms_count;
    } else {
        infoAlert("a maximum of 3 rooms is allowed per reservation!");
    }
});

document.getElementById("guest_count_minus").addEventListener("click", function (event) {
    event.preventDefault();
    let count = document.getElementById("guest_count");
    let guest_count = parseInt(count.value, 10);
    if (isNaN(guest_count) || count.value <= 1) {
        count.value = 1;
    } else {
        guest_count--;
        count.value = guest_count;
    }
});

document.getElementById("guest_count_plus").addEventListener("click", function (event) {
    event.preventDefault();
    let count = document.getElementById("guest_count");
    let guest_count = parseInt(count.value, 10);
    if (isNaN(guest_count) || count.value < 1) {
        count.value = 1;
    } else if (count.value == 20) {
        infoAlert("a maximum of 20 guests are allowed for a single reservation !");
    } else if (count.value == 9) {
        questionAlert("are you sure, you want to increase the number of guests further ?");
        guest_count++;
        count.value = guest_count;
    } else {
        guest_count++;
        count.value = guest_count;
    }
});

document.getElementById("start_date").addEventListener("change", function (event) {
    event.preventDefault();
    let start_date = document.getElementById("start_date");
    let sdate = new Date(start_date.value);
    let stimestamp = sdate.getTime();
    let end_date = document.getElementById("end_date");
    let edate = new Date(end_date.value);
    let etimestamp = edate.getTime();
    let s_date = document.getElementById("s_date");
    let e_date = document.getElementById("e_date");
    const now = Date.now();
    if (isNaN(stimestamp) || stimestamp < now) {
        start_date.value = new Date().toISOString().slice(0, 10);
        infoAlert("please select a current or future date !");
    } else if (stimestamp >= etimestamp) {
        end_date.value = "";
        e_date.value = "";
        infoAlert("you have selected a starting date later than ending date, the ending date will be emptied now !");
    }
    let xdate = new Date(start_date.value);
    let xtimestamp = xdate.getTime();
    s_date.value = xtimestamp;
});

document.getElementById("end_date").addEventListener("change", function (event) {
    event.preventDefault();
    let start_date = document.getElementById("start_date");
    let sdate = new Date(start_date.value);
    let stimestamp = sdate.getTime();
    let end_date = document.getElementById("end_date");
    let edate = new Date(end_date.value);
    let etimestamp = edate.getTime();
    let e_date = document.getElementById("e_date");
    // 86400000
    if (isNaN(etimestamp) || etimestamp < (stimestamp + 86400000)) {
        end_date.value = new Date(stimestamp + 86400000).toISOString().slice(0, 10);
        infoAlert("please select a date later than the starting date !");
    } else if ((etimestamp - stimestamp) >= 1209600001) {
        end_date.value = new Date(stimestamp + 1209600000).toISOString().slice(0, 10);
        infoAlert("a maximum of 14 days of duration is allowed for a single reservation !");
    }
    let xdate = new Date(end_date.value);
    let xtimestamp = xdate.getTime();
    e_date.value = xtimestamp;
});

document.getElementById("search").addEventListener("click", function (event) {
    event.preventDefault();
    let sdate = document.getElementById("s_date");
    let edate = document.getElementById("e_date");
    let rooms_count = document.getElementById("rooms_count");
    let guest_count = document.getElementById("guest_count");
    if (sdate.value == "") {
        errorAlert("please select a start date !")
    } else if (edate.value == "") {
        errorAlert("please select an end date !")
    } else if (rooms_count.value == "") {
        errorAlert("please select how many rooms are required !")
    } else if (guest_count.value == "") {
        errorAlert("please select the number of guests !")
    } else {
        
        document.search_form.submit();
    }
});

function infoAlert(text) {
    Swal.fire({
        icon: "warning",
        text: text,
        customClass: {
            popup: 'sw-alert',
            confirmButton: 'sw-alert-btn',
        }
    });
}

function questionAlert(text) {
    Swal.fire({
        icon: "question",
        text: text,
        confirmButtonText: "Yes",
        customClass: {
            popup: 'sw-alert',
            confirmButton: 'sw-alert-btn',
        }
    });
}

function errorAlert(text){
    Swal.fire({
        icon: "error",
        text: text,
        customClass: {
            popup: 'sw-alert',
            confirmButton: 'sw-alert-btn',
        }
    });
}