if (document.querySelector("#removed")) {
    Swal.fire({
        icon: "success",
        text: "The customer is removed succesfully !",
        customClass: {
            popup: 'sw-alert',
            confirmButton: 'sw-alert-btn',
        }
    });
}

back = document.getElementById("back");
fwd = document.getElementById("fwd");

window.onload = function () {
    back.click();
};

document.getElementById("back").addEventListener("click", function (event) {
    event.preventDefault();
    ajax_call("room_back", "tbl", fwd);
});

document.getElementById("fwd").addEventListener("click", function (event) {
    event.preventDefault();
    ajax_call("room_fwd", "tbl", fwd);
});

function ajax_call(request, list_name, fwd) {
    $.ajax({
        data: {
            req: request
        },
        type: 'POST',
        dataType: 'json',
        url: 'http://localhost:8080/kng/system/sub/ajax2.php',
        success: function (response) {
            var content = response.content;
            let list = document.getElementById(list_name);
            if (content == '') {
                fwd.style.display = "none";
            } else {
                list.innerHTML = content;
                fwd.style.display = "block";
            };

            const edits = document.querySelectorAll('.edit');
            for (i = 0; i < edits.length; ++i) {
                edits[i].addEventListener("click", function (event) {
                    window.location.replace("http://localhost:8080/kng/system/modules/edit_rooms.php?id=" + this.id);
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