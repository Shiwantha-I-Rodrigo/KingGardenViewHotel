document.getElementById("booknow").addEventListener("click", function (event) {
    event.preventDefault();
    sessionStorage.setItem("current_page", "home");
    location.replace(window.location.origin + "/web/index.php");
});

var i = 0;
function change() {
    var doc = document.getElementById("book_btn");
    var color = ["#14a098", "#ffffff"];
    doc.style.backgroundColor = color[i];
    doc.style.color = color[(i + 1) % color.length];
    i = (i + 1) % color.length;
}

setInterval(change, 1000);

dest_back = document.getElementById("dest_back");
dest_fwd = document.getElementById("dest_fwd");

window.onload = function () {
    dest_back.click();
};

document.getElementById("dest_back").addEventListener("click", function (event) {
    event.preventDefault();
    //$("#dests div").remove();
    ajax_call("dest_back", "", "dests", dest_fwd, "");
});

document.getElementById("dest_fwd").addEventListener("click", function (event) {
    event.preventDefault();
    //$("#dests div").remove();
    ajax_call("dest_fwd", "", "dests", dest_fwd, "");
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

            let elements = document.querySelectorAll('.popup');
            elements.forEach((item) => {
                item.addEventListener('click', () => {
                    let text = item.innerHTML;
                    $("#room-details").html(text);
                    $('#Reservation').modal('show');
                })
            });

            let transports = document.querySelectorAll('.transport');
            document.getElementById("list_toggle").addEventListener("click", function (event) {
                transports.forEach((item) => {
                    item.classList.toggle('d-none');
                })
            });

        },
        error: function (xhr, status, error) {
            alert(error);
        }
    });
}