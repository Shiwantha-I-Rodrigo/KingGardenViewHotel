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
    $("#msg li").remove();
    let request = "msg_back";
    $.ajax({
        data: {
            req: request
        },
        type: 'POST',
        dataType: 'json',
        url: '/web/sub/ajax.php',
        success: function (response) {
            var content = response.content;
            let list = document.getElementById('msg');
            list.innerHTML = content;
            if (content == '') {
                msg_fwd.style.display = "none";
            } else {
                msg_fwd.style.display = "block";
            };

            const lists = document.querySelectorAll('#msg>li');
            for (i = 0; i < lists.length; ++i) {
                lists[i].addEventListener("click", function (event) {
                    $.ajax({
                        data: {
                            req: "msg_li",
                            inf: this.id
                        },
                        type: 'POST',
                        dataType: 'json',
                        url: '/web/sub/ajax.php',
                        success: function (response) {
                            var content = response.content;
                            let list = document.getElementById('model_msg');
                            list.innerHTML = content;
                            $('#Conversation').modal('show');
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
});

document.getElementById("msg_fwd").addEventListener("click", function (event) {
    event.preventDefault();
    $("#msg li").remove();
    let request = "msg_fwd";
    $.ajax({
        data: {
            req: request
        },
        type: 'POST',
        dataType: 'json',
        url: '/web/sub/ajax.php',
        success: function (response) {
            var content = response.content;
            let list = document.getElementById('msg');
            list.innerHTML = content;
            if (content == '') {
                msg_fwd.style.display = "none";
            } else {
                msg_fwd.style.display = "block";
            }

            const lists = document.querySelectorAll('#msg>li');
            for (i = 0; i < lists.length; ++i) {
                lists[i].addEventListener("click", function (event) {
                    $.ajax({
                        data: {
                            req: "msg_li",
                            inf: this.id
                        },
                        type: 'POST',
                        dataType: 'json',
                        url: '/web/sub/ajax.php',
                        success: function (response) {
                            var content = response.content;
                            let list = document.getElementById('model_msg');
                            list.innerHTML = content;
                            $('#Conversation').modal('show');
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
});

document.getElementById("past_back").addEventListener("click", function (event) {
    event.preventDefault();
    $("#past li").remove();
    let request = "past_back";
    $.ajax({
        data: {
            req: request
        },
        type: 'POST',
        dataType: 'json',
        url: '/web/sub/ajax.php',
        success: function (response) {
            var content = response.content;
            let list = document.getElementById('past');
            list.innerHTML = content;
            if (content == '') {
                past_fwd.style.display = "none";
            } else {
                past_fwd.style.display = "block";
            }
        },
        error: function (xhr, status, error) {
            alert(error);
        }
    });
});

document.getElementById("past_fwd").addEventListener("click", function (event) {
    event.preventDefault();
    $("#past li").remove();
    let request = "past_fwd";
    $.ajax({
        data: {
            req: request
        },
        type: 'POST',
        dataType: 'json',
        url: '/web/sub/ajax.php',
        success: function (response) {
            var content = response.content;
            let list = document.getElementById('past');
            list.innerHTML = content;
            if (content == '') {
                past_fwd.style.display = "none";
            } else {
                past_fwd.style.display = "block";
            }
        },
        error: function (xhr, status, error) {
            alert(error);
        }
    });
});

document.getElementById("comming_back").addEventListener("click", function (event) {
    event.preventDefault();
    $("#comming li").remove();
    let request = "comming_back";
    $.ajax({
        data: {
            req: request
        },
        type: 'POST',
        dataType: 'json',
        url: '/web/sub/ajax.php',
        success: function (response) {
            var content = response.content;
            let list = document.getElementById('comming');
            list.innerHTML = content;
            if (content == '') {
                comming_fwd.style.display = "none";
            } else {
                comming_fwd.style.display = "block";
            }
        },
        error: function (xhr, status, error) {
            alert(error);
        }
    });
});

document.getElementById("comming_fwd").addEventListener("click", function (event) {
    event.preventDefault();
    $("#comming li").remove();
    let request = "comming_fwd";
    $.ajax({
        data: {
            req: request
        },
        type: 'POST',
        dataType: 'json',
        url: '/web/sub/ajax.php',
        success: function (response) {
            var content = response.content;
            let list = document.getElementById('comming');
            list.innerHTML = content;
            if (content == '') {
                comming_fwd.style.display = "none";
            } else {
                comming_fwd.style.display = "block";
            }
        },
        error: function (xhr, status, error) {
            alert(error);
        }
    });
});
