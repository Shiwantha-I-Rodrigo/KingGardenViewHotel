addEventListener("DOMContentLoaded", (event) => {

    const spinner = document.getElementById("overlay");
    if (typeof (spinner) != 'undefined' && spinner != null) {
        setTimeout(() => spinner.hidden = true, 1000);
    }

    const current_page = sessionStorage.getItem("current_page");
    const nav_item = document.getElementById(current_page);
    if (typeof (nav_item) != 'undefined' && nav_item != null) {
        nav_item.classList.toggle("nav-selected");
    }else{
        console.log("session reset")
        sessionStorage.setItem("current_page","home");
        const current_page_start = sessionStorage.getItem("current_page");
        const nav_item = document.getElementById(current_page_start);
        nav_item.classList.toggle("nav-selected");
    }

    const logo = document.getElementById("logo");
    const home = document.getElementById("home");
    const about = document.getElementById("about");
    const rooms = document.getElementById("rooms");
    const blog = document.getElementById("blog");
    const contact = document.getElementById("contact");
    const call = document.getElementById("call");
    const email = document.getElementById("emails");
    const address = document.getElementById("address");
    const design = document.getElementById("design");
    const login = document.getElementById("login");
    const register = document.getElementById("register");
    const logout = document.getElementById("logout");
    const dashboard = document.getElementById("dashboard");

    if (typeof (logo) != 'undefined' && logo != null) {
        logo.addEventListener("click", () => {
            sessionStorage.setItem("current_page", "home");
            window.location.replace("/web/index.php"); //replace current page (no back)
            // window.location.href = "http://www.w3schools.com"; //redirect to new page
        });
    }

    if (typeof (home) != 'undefined' && home != null) {
        home.addEventListener("click", () => {
            sessionStorage.setItem("current_page", "home");
            window.location.replace("/web/index.php");
        });
    }

    if (typeof (about) != 'undefined' && about != null) {
        about.addEventListener("click", () => {
            sessionStorage.setItem("current_page", "about");
            window.location.replace("/web/modules/about.php");
        });
    }

    if (typeof (rooms) != 'undefined' && rooms != null) {
        rooms.addEventListener("click", () => {
            sessionStorage.setItem("current_page", "rooms");
            window.location.replace("/web/modules/rooms.php");
        });
    }

    if (typeof (blog) != 'undefined' && blog != null) {
        blog.addEventListener("click", () => {
            sessionStorage.setItem("current_page", "blog");
            window.location.replace("/web/modules/blog.php");
        });
    }

    if (typeof (contact) != 'undefined' && contact != null) {
        contact.addEventListener("click", () => {
            sessionStorage.setItem("current_page", "contact");
            window.location.replace("/web/modules/contact.php");
        });
    }

    if (typeof (login) != 'undefined' && login != null) {
        login.addEventListener("click", () => {
            window.location.replace("/web/modules/login.php");
        });
    }

    if (typeof (register) != 'undefined' && register != null) {
        register.addEventListener("click", () => {
            window.location.replace("/web/modules/register.php");
        });
    }

    if (typeof (call) != 'undefined' && call != null) {
        call.addEventListener("click", () => {
            sessionStorage.setItem("current_page", "contact");
            window.location.replace("/web/modules/contact.php");
        });
    }

    if (typeof (email) != 'undefined' && email != null) {
        email.addEventListener("click", () => {
            sessionStorage.setItem("current_page", "contact");
            window.location.replace("/web/modules/contact.php");
        });
    }

    if (typeof (address) != 'undefined' && address != null) {
        address.addEventListener("click", () => {
            sessionStorage.setItem("current_page", "contact");
            window.location.replace("/web/modules/contact.php");
        });
    }

    if (typeof (design) != 'undefined' && design != null) {
        design.addEventListener("click", () => {
            sessionStorage.setItem("current_page", "home");
            window.location.replace("/web/index.php");
        });
    }

    if (typeof (logout) != 'undefined' && logout != null) {
        logout.addEventListener("click", () => {
            sessionStorage.setItem("current_page", "home");
            window.location.replace("/web/sub/logout.php");
        });
    }

    if (typeof (dashboard) != 'undefined' && dashboard != null) {
        dashboard.addEventListener("click", () => {
            sessionStorage.setItem("current_page", "dashboard");
            window.location.replace("/web/modules/dashboard.php");
        });
    }

});
