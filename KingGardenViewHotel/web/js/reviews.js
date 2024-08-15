rev_back = document.getElementById("rev_back");
rev_fwd = document.getElementById("rev_fwd");

let review = document.getElementById("add_review");
let user_id = review.getAttribute('data-id');
let room_id = review.getAttribute('data-room');
let rev_tog = review.getAttribute('data-tog');
if(user_id == 0 || rev_tog == '0'){
    review.classList.add('d-none');
}

window.onload = function () {
    rev_back.click();
};

document.getElementById("rev_back").addEventListener("click", function (event) {
    event.preventDefault();
    //$("#blogs div").remove();
    ajax_call("rev_back", "", "view_review", rev_fwd, "");
});

document.getElementById("rev_fwd").addEventListener("click", function (event) {
    event.preventDefault();
    //$("#blogs div").remove();
    ajax_call("rev_fwd", "", "view_review", rev_fwd, "");
});

document.getElementById("submit_btn").addEventListener("click", function (event) {
    event.preventDefault();
    $("#Confirm").modal("show");
});

function ajax_call(request, sub_request, list_name, fwd, heading) {

    $.ajax({
        data: {
            req: request,
            inf: room_id
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

            let elements = document.querySelectorAll('.popup');
            elements.forEach((item) => {
                item.addEventListener('click', () => {
                    let text = item.innerHTML;
                    $("#room-details").html(text);
                    $('#Reservation').modal('show');
                })
            });

        },
        error: function (xhr, status, error) {
            alert(error);
        }
    });
}
