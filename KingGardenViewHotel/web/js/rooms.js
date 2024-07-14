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

let elements = document.querySelectorAll('.room');

elements.forEach((item) => {
    item.addEventListener('click', () => {
        let room_id = item.id;
        let text = item.innerHTML;
        let input = document.getElementById("room_id");
        $("#room-id").html(room_id);
        $("#room-details").html(text);
        input.value = room_id;
        $('#Reservation').modal('show');
    })
});