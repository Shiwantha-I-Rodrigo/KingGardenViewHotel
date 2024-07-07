document.getElementById("booknow").addEventListener("click", function (event) {
    event.preventDefault();
    sessionStorage.setItem("current_page", "home");
    location.replace(window.location.origin + "/web/index.php");
});

var i = 0;
function change() {
    var doc = document.getElementById("book_btn");
    var color = ["#14a098", "#cb2d6f"];
    doc.style.backgroundColor = color[i];
    i = (i + 1) % color.length;
}

setInterval(change, 1000);