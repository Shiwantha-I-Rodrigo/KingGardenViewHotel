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

blog_back = document.getElementById("blog_back");
blog_fwd = document.getElementById("blog_fwd");

window.onload = function () {
    blog_back.click();
};

document.getElementById("blog_back").addEventListener("click", function (event) {
    event.preventDefault();
    //$("#blogs div").remove();
    ajax_call("blog_back", "", "blogs", blog_fwd, "");
});

document.getElementById("blog_fwd").addEventListener("click", function (event) {
    event.preventDefault();
    //$("#blogs div").remove();
    ajax_call("blog_fwd", "", "blogs", blog_fwd, "");
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
        },
        error: function (xhr, status, error) {
            alert(error);
        }
    });
}