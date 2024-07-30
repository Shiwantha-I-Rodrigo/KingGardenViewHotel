sessionStorage.setItem("current_page", "rooms");

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
        let input1 = document.getElementById("room_id1");
        let input2 = document.getElementById("room_id2");
        let input3 = document.getElementById("room_id3");
        $("#room-id").html(room_id);
        $("#room-details").html(text);
        let rooms = room_id.split('_');
        typeof rooms[0] === 'undefined' ? input1.value = 0 : input1.value = rooms[0] ;
        typeof rooms[1] === 'undefined' ? input2.value = 0 : input2.value = rooms[1] ;
        typeof rooms[2] === 'undefined' ? input3.value = 0 : input3.value = rooms[2] ;
        $('#Reservation').modal('show');
    })
});