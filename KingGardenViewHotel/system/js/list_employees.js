if (document.querySelector("#removed")) {
    Swal.fire({
        icon: "success",
        text: "The employee is removed succesfully !",
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

window.onload = function () {
    back.click();
};

document.getElementById("back").addEventListener("click", function (event) {
    event.preventDefault();
    let query = gen_sql();
    ajax_call("employee_back", "tbl", fwd, query);
});

document.getElementById("fwd").addEventListener("click", function (event) {
    event.preventDefault();
    let query = gen_sql();
    ajax_call("employee_fwd", "tbl", fwd, sql);
});

document.getElementById("filter_btn").addEventListener("click", function (event) {
    event.preventDefault();
    let query = gen_sql();
    ajax_call("employee", "tbl", fwd, query);
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
                    window.location.replace("/system/modules/edit_employees.php?id=" + this.id);
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

function gen_sql() {
    sql = '';
    search.value != "" ? sql += ` WHERE CONCAT_WS("_",u.UserId,u.UserName,u.Email,c.FirstName,c.LastName,c.AddressLine1,c.AddressLine2,c.AddressLine3,c.Telephone,c.Mobile,c.RegNo) LIKE '%${search.value}%'` : sql += "";
    min.value != '' ? search.value != '' ? sql += ` AND ${range.value} > ${min.value} ` : sql += ` WHERE ${range.value} > ${min.value} ` : sql += '';
    max.value != '' ? search.value != '' || min.value != '' ? sql += ` AND ${range.value} < ${max.value} ` : sql += ` WHERE ${range.value} < ${max.value} ` : sql += '';
    sort.value != '' ? sql += ` ORDER BY ${sort.value} ${order.value} ` : sql += '';
    console.log(sql);
    return sql;
}