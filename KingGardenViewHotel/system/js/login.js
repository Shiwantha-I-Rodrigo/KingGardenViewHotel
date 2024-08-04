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
        errorAlert("Username cannot be empty!");
    } else if (password.value == "") {
        errorAlert("Password cannot be empty!");
    } else {
        document.login_form.submit();
    }
});

function errorAlert(text){
    Swal.fire({
        icon: "error",
        text: text,
        customClass: {
            popup: 'sw-alert',
            confirmButton: 'sw-alert-btn',
        }
    });
}