addEventListener("DOMContentLoaded", (event) => {

    const spinner = document.getElementById("overlay");
    if (typeof (spinner) != 'undefined' && spinner != null) {
        setTimeout(() => spinner.hidden = true, 1000);
    }

    const logo = document.getElementById("logo");
    const login = document.getElementById("login");
    const register = document.getElementById("register");
    const logout = document.getElementById("logout");
    const dashboard = document.getElementById("dashboard");

    if (typeof (logo) != 'undefined' && logo != null) {
        logo.addEventListener("click", () => {
            sessionStorage.setItem("current_page", "home");
            window.location.replace("/system/index.php"); //replace current page (no back)
            // window.location.href = "http://www.w3schools.com"; //redirect to new page
        });
    }

    if (typeof (login) != 'undefined' && login != null) {
        login.addEventListener("click", () => {
            window.location.replace("/system/modules/login.php");
        });
    }

    if (typeof (register) != 'undefined' && register != null) {
        register.addEventListener("click", () => {
            window.location.replace("/system/modules/register.php");
        });
    }

    if (typeof (logout) != 'undefined' && logout != null) {
        logout.addEventListener("click", () => {
            window.location.replace("/system/sub/logout.php");
        });
    }

    if (typeof (dashboard) != 'undefined' && dashboard != null) {
        dashboard.addEventListener("click", () => {
            window.location.replace("/system/modules/dashboard.php");
        });
    }

});
