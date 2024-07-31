sessionStorage.setItem("current_page", "dashboard");
msg_back = document.getElementById("msg_back");
msg_fwd = document.getElementById("msg_fwd");
past_back = document.getElementById("past_back");
past_fwd = document.getElementById("past_fwd");
comming_back = document.getElementById("comming_back");
comming_fwd = document.getElementById("comming_fwd");

window.onload = function () {
    msg_back.click();
    past_back.click();
    comming_back.click();
};

document.getElementById("msg_back").addEventListener("click", function (event) {
    event.preventDefault();
    // $("#msg li").remove();
    ajax_call("msg_back", "msg_li", "msg", msg_fwd, "Messages");
});

document.getElementById("msg_fwd").addEventListener("click", function (event) {
    event.preventDefault();
    // $("#msg li").remove();
    ajax_call("msg_fwd", "msg_li", "msg", msg_fwd, "Messages");
});

document.getElementById("past_back").addEventListener("click", function (event) {
    event.preventDefault();
    // $("#past li").remove();
    ajax_call("past_back", "res_li", "past", past_fwd, "Reservation");
});

document.getElementById("past_fwd").addEventListener("click", function (event) {
    event.preventDefault();
    // $("#past li").remove();
    ajax_call("past_fwd", "res_li", "past", past_fwd, "Reservation");
});

document.getElementById("comming_back").addEventListener("click", function (event) {
    event.preventDefault();
    // $("#comming li").remove();
    ajax_call("comming_back", "res_li", "comming", comming_fwd, "Reservation");
});

document.getElementById("comming_fwd").addEventListener("click", function (event) {
    event.preventDefault();
    // $("#comming li").remove();
    ajax_call("comming_fwd", "res_li", "comming", comming_fwd, "Reservation");
});


function ajax_call(request, sub_request, list_name, fwd, heading) {
    $.ajax({
        data: {
            req: request
        },
        type: 'POST',
        dataType: 'json',
        url: '/web/sub/ajax.php',
        success: function (response) {
            var content = response.content;
            let list = document.getElementById(list_name);
            if (content == '') {
                fwd.style.display = "none";
            } else {
                list.innerHTML = content;
                fwd.style.display = "block";
            };

            const lists = document.querySelectorAll('#' + list_name + '>li');
            for (i = 0; i < lists.length; ++i) {
                lists[i].addEventListener("click", function (event) {
                    $.ajax({
                        data: {
                            req: sub_request,
                            inf: this.id
                        },
                        type: 'POST',
                        dataType: 'json',
                        url: '/web/sub/ajax.php',
                        success: function (response) {
                            var content = response.content;
                            let list = document.getElementById('model_list');
                            list.innerHTML = content;
                            let title = document.getElementById('modal-heading');
                            title.innerHTML = heading;
                            $('#Dash_Pop').modal('show');

                            if (document.querySelector("#cancel")){
                                let cancel = document.getElementById("cancel");
                                let id = cancel.getAttribute('data-id');
                                cancel.addEventListener("click", function (event) {
                                    $('#Dash_Pop').modal('hide');
                                    $("#Confirm").modal("show");
                                    let input = document.getElementById("ReservationId");
                                    input.value = id;
                                });
                            }
                        
                        },
                        error: function (xhr, status, error) {
                            alert(error);
                        }
                    });
                });
            };

        },
        error: function (xhr, status, error) {
            alert(error);
        }
    });
}

if (document.querySelector("#cancelled")){
    Swal.fire({
        icon: "warning",
        text: "The reservation is cancelled succesfully !",
        customClass: {
            popup: 'sw-alert',
            confirmButton: 'sw-alert-btn',
        }
    });
}
