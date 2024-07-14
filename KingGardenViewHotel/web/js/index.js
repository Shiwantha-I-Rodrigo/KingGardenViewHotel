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
    } else {
        rooms_count++;
        count.value = rooms_count;
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
    const now = Date.now();
    if (isNaN(stimestamp) || stimestamp < now) {
        start_date.value =  new Date().toISOString().slice(0, 10);
    }
});

document.getElementById("end_date").addEventListener("change", function (event) {
    event.preventDefault();
    let start_date = document.getElementById("start_date");
    let sdate = new Date(start_date.value);
    let stimestamp = sdate.getTime();
    let end_date = document.getElementById("end_date");
    let edate = new Date(end_date.value);
    let etimestamp = edate.getTime();
    // 86400000
    if (isNaN(etimestamp) || etimestamp < stimestamp + 86400000) {
        end_date.value =  new Date(stimestamp + 86400000).toISOString().slice(0, 10);
    }
});
