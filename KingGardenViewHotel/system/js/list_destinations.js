if (document.querySelector("#removed")) {
    Swal.fire({
        icon: "success",
        text: "The destination is removed succesfully !",
        customClass: {
            popup: 'sw-alert',
            confirmButton: 'sw-alert-btn',
        }
    });
}

back = document.getElementById("back");
fwd = document.getElementById("fwd");
search = document.getElementById("search");
sort = document.getElementById("sort");
order = document.getElementById("order");
range = document.getElementById("range");
min = document.getElementById("min");
max = document.getElementById("max");
start = document.getElementById("s_date");
end = document.getElementById("e_date");

window.onload = function () {
    back.click();
};

document.getElementById("back").addEventListener("click", function (event) {
    event.preventDefault();
    let query = gen_sql();
    ajax_call("dest_back", "tbl", fwd, query);
});

document.getElementById("fwd").addEventListener("click", function (event) {
    event.preventDefault();
    let query = gen_sql();
    ajax_call("dest_fwd", "tbl", fwd, query);
});

document.getElementById("filter_btn").addEventListener("click", function (event) {
    event.preventDefault();
    let query = gen_sql();
    ajax_call("dest", "tbl", fwd, query);
});

function ajax_call(request, list_name, fwd, options = '') {
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
            if (content.includes("id='end'")) {
                list.innerHTML = content;
                fwd.style.display = "none";
            } else {
                list.innerHTML = content;
                fwd.style.display = "block";
            };

            const edits = document.querySelectorAll('.edit');
            for (i = 0; i < edits.length; ++i) {
                edits[i].addEventListener("click", function (event) {
                    window.location.replace("/system/modules/edit_destinations.php?id=" + this.id);
                })
            }

            const delets = document.querySelectorAll('.delete');
            for (i = 0; i < delets.length; ++i) {
                delets[i].addEventListener("click", function (event) {
                    let id = document.getElementById('id');
                    id.value = this.id;
                    $('#Confirm').modal('show');
                })
            }

        },
        error: function (xhr, status, error) {
            alert(error);
        }
    });
}

document.getElementById("range").addEventListener("change", function (event) {
    let number = document.getElementById("number_range");
    let date = document.getElementById("date_range");
    if (this.value == '') {
        number.classList.add('d-none');
        date.classList.remove('d-none');
    } else {
        number.classList.remove('d-none');
        date.classList.add('d-none');
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
    if (stimestamp >= etimestamp) {
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
    }
    let xdate = new Date(end_date.value);
    let xtimestamp = xdate.getTime();
    e_date.value = xtimestamp;
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

function gen_sql() {
    sql = '';
    search.value != "" ? sql += ` WHERE CONCAT_WS("_",i.DestinationId,i.DestinationText,i.DestinationTitle) LIKE '%${search.value}%'` : sql += "";
    if (range.value != '') {
        min.value != '' ? search.value != '' ? sql += ` AND ${range.value} > ${min.value} ` : sql += ` WHERE ${range.value} > ${min.value} ` : sql += '';
        max.value != '' ? search.value != '' || min.value != '' ? sql += ` AND ${range.value} < ${max.value} ` : sql += ` WHERE ${range.value} < ${max.value} ` : sql += '';
    } else {
        start.value != '' ? search.value != '' ? sql += ` AND ${range.value} > ${start.value} ` : sql += ` WHERE ${range.value} > ${start.value} ` : sql += '';
        end.value != '' ? search.value != '' || start.value != '' ? sql += ` AND ${range.value} < ${end.value} ` : sql += ` WHERE ${range.value} < ${end.value} ` : sql += '';
    }
    sort.value != '' ? sql += ` ORDER BY ${sort.value} ${order.value} ` : sql += '';
    console.log(sql);
    return sql;
}