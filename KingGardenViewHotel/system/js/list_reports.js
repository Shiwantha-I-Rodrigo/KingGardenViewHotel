window.onload = function () {
    start_date = document.getElementById("start_date");
    s_date = document.getElementById("s_date");
    day_btn = document.getElementById("day_btn");
    month_btn = document.getElementById("month_btn");
    year_btn = document.getElementById("year_btn");
    tbl = document.getElementById("tbl");
};

day_btn.addEventListener("click", function (event) {
    event.preventDefault();
    if (s_date.value == '') {
        infoAlert("please select a date !");
    } else {
        ajax_call("daily", "tbl", (s_date.value / 1000) + 43200);
    }
});

month_btn.addEventListener("click", function (event) {
    event.preventDefault();
    if (s_date.value == '') {
        infoAlert("please select a date !");
    } else {
    ajax_call("monthly", "tbl", (s_date.value / 1000) + 43200);
    }
});

year_btn.addEventListener("click", function (event) {
    event.preventDefault();
    if (s_date.value == '') {
        infoAlert("please select a date !");
    } else {
    ajax_call("yearly", "tbl", (s_date.value / 1000) + 43200);
    }
});

function ajax_call(request, list_name, options = '') {
    $.ajax({
        data: {
            req: request,
            opt: options
        },
        type: 'POST',
        dataType: 'json',
        url: '/system/sub/ajax2.php',
        success: function (response) {
            var content = response.content;
            let list = document.getElementById(list_name);
            list.innerHTML = content;
        },
        error: function (xhr, status, error) {
            alert(error);
        }
    });
}

start_date.addEventListener("change", function (event) {
    event.preventDefault();
    let start_date = document.getElementById("start_date");
    let sdate = new Date(start_date.value);
    let stimestamp = sdate.getTime();
    let s_date = document.getElementById("s_date");
    s_date.value = stimestamp;
    const now = Date.now();
    if (stimestamp >= now) {
        infoAlert("you have selected a report date later than current date, the reports may be affected accordingly !");
    }
    let xdate = new Date(start_date.value);
    let xtimestamp = xdate.getTime();
    s_date.value = xtimestamp;
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

document.getElementById("print_btn").addEventListener("click", function (event) {
    var page = document.getElementById("print_page");
    newWin = window.open("");
    newWin.document.write('<style> table, th, td {border:2px solid black; border-collapse : collapse;}</style>' + page.outerHTML);
    newWin.print();
    newWin.close();
});