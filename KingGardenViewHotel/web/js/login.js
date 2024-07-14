document.getElementById("clear_btn").addEventListener("click", function (event) {
    event.preventDefault();
    let user_name = document.getElementById("user_name");
    let password = document.getElementById("password");
    user_name.value = "";
    password.value = "";
});

document.getElementById("submit_btn").addEventListener("click", function (event) {
    event.preventDefault();
    let user_name = document.getElementById("user_name");
    let password = document.getElementById("password");
    if (user_name.value == "") {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Username cannot be empty!",
            customClass: {
                popup: 'sw-alert',
                confirmButton: 'sw-alert-btn',
            }
        });
    } else if (password.value == "") {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Password cannot be empty!",
            customClass: {
                popup: 'sw-alert',
                confirmButton: 'sw-alert-btn',
            }
        });
    } else {
        document.login_form.submit();
    }
});